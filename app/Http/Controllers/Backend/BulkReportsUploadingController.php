<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use DB;

## Models
use App\Models\ReportsModel;
use App\Models\CategoryModel;
use App\Models\PublisherModel;
use App\Models\CSVFilesModel;

class BulkReportsUploadingController extends Controller
{
    public function __construct()
    {       
        $this->ViewData = $this->JsonData = [];

        $this->ModuleTitle = 'Bulk Reports Uploading';
        $this->ModuleView  = 'Backend/Bulk_reports_uploading.';
        $this->ModulePath  = 'admin/bulk_reports_uploading';
    }

    /*----------------------------------------
    |  Function  listing page
    ------------------------------*/
    public function index()
    {
        $this->ViewData['modulePath']   = $this->ModulePath;
        $this->ViewData['moduleTitle']  = $this->ModuleTitle;
        $this->ViewData['moduleAction'] = $this->ModuleTitle;
        
        return view($this->ModuleView.'index', $this->ViewData);
    }

    /*--------------------------------------------
    |  Function get listing data
    -----------------------------------------*/
    public function getData(Request $request)
    {
        /*--------------------------------------
        |  Variables
        ------------------------------*/
            ## skip and limit
            $start  = $request->start;
            $length = $request->length;

            ## serach value
            $search = $request->search['value']; 

            ## order
            $column = $request->order[0]['column'];
            $dir    = $request->order[0]['dir'];

            ## filter columns
            $filter = array(
                0 => 'id',
                1 => 'file_name',
                2 => 'download_status',
                3 => 'created_at',
                4 => 'id'
            );

        /*--------------------------------------
        |  Model query and filter
        ------------------------------*/

        ## start model query
        $modelQuery = new CSVFilesModel();
        
        ## get total count 
        $countQuery = clone($modelQuery);            
        $totalData  = $countQuery->count();

        ## filter options
        if (!empty($search)) 
        {
            $modelQuery = $modelQuery->where(function ($query) use($search)
            {
                $query->orwhere('id', 'LIKE', '%'.$search.'%');
                $query->orwhere('file_name', 'LIKE', '%'.$search.'%');  
                $query->orwhere('download_status', 'LIKE', '%'.$search.'%');
                $query->orwhere('created_at', 'LIKE', '%'.Date('Y-m-d', strtotime($search)).'%');  
            });
        }

        ## get total filtered
        $filteredQuery = clone($modelQuery);            
        $totalFiltered  = $filteredQuery->count();
        
        ## offset and limit
        $object = $modelQuery->orderBy($filter[$column], $dir)
                            ->skip($start)
                            ->take($length)
                            ->get(['id','file_name','download_status','created_at']);            
        
        /*--------------------------------------
        |  data binding
        ------------------------------*/
        $data = [];
        if (!empty($object) && sizeof($object) > 0) 
        {   
            foreach ($object as $key => $row) 
            {
                $data[$key]['id']           = ($key+$start+1);
                $data[$key]['file_name']    = '<span title="'.$row->file_name.'">'.$row->file_name.'</span>';
                $data[$key]['created_at'] = Date('Y-m-d', strtotime($row->created_at));
                if($row->download_status == '0')
                {
                    $data[$key]['download_status'] = 'NOT UPLOADED';
                    
                    $upload = '<a title="Upload" href="'.url($this->ModulePath.'/upload_sheet', [ ($row->id)]).'" data-qsnid="'.($row->id).'" class="btn btn-default btn-circle" href="javascript:void(0)"><i class="fa fa-upload" aria-hidden="true"></i></a>';
                }
                else
                {
                    $data[$key]['download_status'] = 'UPLOADED';
                    
                    $upload = '<a title="Download" href="'.url($this->ModulePath.'/dowload_sheet', [ ($row->id)]).'"  data-qsnid="'.($row->id).'" class="btn btn-default btn-circle" href="javascript:void(0)"><i class="fa fa-download" aria-hidden="true"></i></a>';
                }

                
                $data[$key]['actions'] = $upload;
            }
        }

        ## wrapping up
        $this->JsonData['draw']             = intval($request->draw);
        $this->JsonData['recordsTotal']     = intval($totalData);
        $this->JsonData['recordsFiltered']  = intval($totalFiltered);
        $this->JsonData['data']             = $data;

        return response()->json($this->JsonData);
    }

    /*-----------------------------------------
    |  Function download reports
    -----------------------------------------*/
    public function upload_report(Request $request)
    {
        DB::beginTransaction();

        $file = $request->file('report_file');

        $strFileOriginalName = $file->getClientOriginalName();
        $ext                 = '.'.$file->getClientOriginalExtension();
        $uniqueFileName      = str_replace($ext, "_".time() . $ext, $file->getClientOriginalName());
        $destinationPath     = public_path('/upload/reports/');
        $filepath            = $file->move($destinationPath, $uniqueFileName);

        $csvFilesModel                  = new CSVFilesModel();
        $csvFilesModel->file_name       = $uniqueFileName;
        $csvFilesModel->download_status = '0';
        $csvFilesModel->status          = '0';
        if($csvFilesModel->save()) 
        {
            DB::commit();
            $this->JsonData['status']   = 'success';
            $this->JsonData['msg']      = 'File uploaded successfully.';
        }
        else
        {
            DB::rollBack();
            $this->JsonData['status']   = 'error';
            $this->JsonData['msg']      = 'Failed to upload, Something went wrong.';
        }

        return response()->json($this->JsonData);
    }

    /*-----------------------------------------
    |  Function download reports
    -----------------------------------------*/
    public function dowload_sheet($id)
    {
        $arrObject = CSVFilesModel::select(['id','file_name'])->find($id);
        $file      = public_path('/upload/reports/'.$arrObject->file_name);
        
        return response()->download($file);
    }

    /*----------------------------------------
    |  Function edit view page
    ----------------------------*/
    public function upload_sheet($id)
    {
        $arrHeader = [];
        $arrObject = CSVFilesModel::select(['id','file_name'])->find($id);
        $file = public_path('/upload/reports/'.$arrObject->file_name);
        $handle = fopen($file, "r");
        $intCount = 0;
        while(($arrFilesop = fgetcsv($handle, 1000, ",")) !== false)
        { 
            if($intCount == 0)
            {
                for($i=0;$i<count($arrFilesop);$i++)
                {
                    array_push($arrHeader,$arrFilesop[$i]);
                }
            }  
            break;  
        }
        
        $this->ViewData['moduleTitle'] = $this->ViewData['moduleAction'] = 'Upload sheet';
        $this->ViewData['modulePath']  = $this->ModulePath;
        $this->ViewData['arrObject']   = $arrObject;
        $this->ViewData['arrHeader']   = $arrHeader;
        $this->ViewData['arrPublisher']   = PublisherModel::where('status','0')->get();

        return view($this->ModuleView.'upload_sheet', $this->ViewData);
    }

    /*-----------------------------------------
    |  Function download reports
    -----------------------------------------*/
    public function upload_sheet_report(Request $request,$id)
    {
        $id          = $request->id;
        $reportTitle = $request->reportTitle;
        $pages       = $request->pages;
        $pub_date    = $request->pub_date;
        $content     = $request->content;
        $summary     = $request->summary;
        $category    = $request->category;
        $single      = $request->single;
        $multi       = $request->multi;
        $publisher   = $request->publisher;

        $reportCnt = ReportsModel::count();
        
        $arrObject = CSVFilesModel::select(['id','file_name'])->find($id);
        $file      = public_path('/upload/reports/'.$arrObject->file_name);
        $handle    = fopen($file, "r");
        $intCnt    = 0;
        $error     = 1;

        while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
        {
            //dump($filesop);
            if($filesop[$reportTitle] != "")
            {
                if($intCnt != 0)
                { 
                    if($reportTitle != "") 
                    {
                        ## get category
                        $arrCategory = getCategory($filesop[$category]);

                        ## Save reports
                        $report_title  = $filesop[$reportTitle];
                        $categoryId    = !empty($arrCategory) ? $arrCategory->id : 0;
                        $intPages      = $filesop[$pages];
                        $singleUser    = $filesop[$single];
                        $multiUser     = $filesop[$multi];
                        $publishDate   = $filesop[$pub_date];
                        $publisherId   = $publisher;
                        $reportToc     = trim($filesop[$content]);
                        $reportSummary = trim($filesop[$summary]);
                        
                        $reportsModel                    = new ReportsModel();
                        $reportsModel->fk_category_id    = $categoryId;
                        $reportsModel->fk_publisher_id   = $publisherId;
                        $reportsModel->report_title      = $report_title;
                        $reportsModel->slug              = $report_title;
                        $reportsModel->single_user_price = $singleUser;
                        $reportsModel->multi_user_price  = $multiUser;
                        $reportsModel->pages             = $intPages;
                        $reportsModel->description       = $reportSummary;
                        $reportsModel->toc               = $reportToc;
                        $reportsModel->status            = '0';
                        $reportsModel->save();
                        $lastInsertId = $reportsModel->id;

                        ## Update Csv reocrd
                        CSVFilesModel::where('id',$id)->update([
                            'report_start_id' => $reportCnt+1,
                            'report_last_id'  => $lastInsertId,
                            'download_status' => '1',
                        ]);
                    }
                }
         
                if($intCnt == 3000)
                {
                    break;
                }
                
                $intCnt = $intCnt + 1;
            }

            $error = 0;
        }

        if($error == 1)
        {
            Session::flash('message', 'File Upload successfully'); 
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            Session::flash('message', 'File Upload successfully'); 
            Session::flash('alert-class', 'alert-danger');
        }

        return redirect($this->ModulePath);
    }
}
