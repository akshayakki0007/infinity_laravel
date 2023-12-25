<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

## Models
use App\Models\User;
use App\Models\LeadsModel;
use App\Models\SourceModel;

class CustomLeadsController extends Controller
{
    public function __construct()
    {       
        $this->ViewData = $this->JsonData = [];

        $this->ModuleTitle = 'Leads';
        $this->ModuleView  = 'Backend/Custom_leads.';
        $this->ModulePath  = 'admin/custom_leads';
    }

    /*----------------------------------------
    |  Function  listing page
    ------------------------------*/
    public function index()
    {
        $this->ViewData['modulePath']   = $this->ModulePath;
        $this->ViewData['moduleTitle']  = $this->ModuleTitle;
        $this->ViewData['moduleAction'] = $this->ModuleTitle;
        $this->ViewData['arrSources'] = SourceModel::where('status','0')->get(['id','name']);
        
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
                1 => 'report_id',
                2 => 'report_id',
                5 => 'country',
                6 => 'fk_source_id',
                7 => 'fk_sale_id',
                8 => 'id'
            );

        /*--------------------------------------
        |  Model query and filter
        ------------------------------*/

        ## start model query
        $modelQuery = new LeadsModel();
        $modelQuery = $modelQuery->with(['reports']);
        
        if($request->action != '')
        {
            $modelQuery = $modelQuery->where(function ($query) use ($request)
                                {
                                    if($request->action == 'today')
                                    {
                                        $date = \Carbon\Carbon::today();
                                        $dateNew = date("Y-m-d", strtotime($date));
                                        $query->whereBetween('created_at', [$dateNew.' 00:00:00',$dateNew.' 23:59:59']);
                                    }

                                    if($request->action == 'search')
                                    {
                                        $arrData = explode('-', $request->search_order_date);
                                        if(count($arrData) > 0)
                                        {
                                            $arrData['0'] = str_replace('/', '-', trim($arrData['0']));
                                            $arrData['1'] = str_replace('/', '-', trim($arrData['1']));

                                            $startData = date("Y-m-d", strtotime(($arrData['0'])));
                                            $endDate = date("Y-m-d", strtotime(($arrData['1'])));

                                            //dd('created_at', [$startData.' 00:00:00',$endDate.' 23:59:59']);
                                            $query->whereBetween('created_at', [$startData.' 00:00:00',$endDate.' 23:59:59']);
                                        }
                                    }

                                    if($request->pipline_status != '')
                                    {
                                        $query->where('pipline_status','=',$request->pipline_status);
                                    }

                                    if($request->lead_status != '')
                                    {
                                        $query->where('lead_status','=',$request->lead_status);
                                    }
                                });
        }

        ## get total count 
        $countQuery = clone($modelQuery);            
        $totalData  = $countQuery->count();

        ## filter options
        if (!empty($search)) 
        {
        
            $modelQuery = $modelQuery->where(function ($query) use($search)
            {
                $query->orwhere('id', 'LIKE', '%'.$search.'%');   
                $query->orwhere('report_id', 'LIKE', '%'.$search.'%');   
                $query->orwhere('report_id', 'LIKE', '%'.$search.'%');   
                $query->orwhere('country', 'LIKE', '%'.$search.'%');   
                $query->orwhere('fk_source_id', 'LIKE', '%'.$search.'%');   
                $query->orwhere('fk_sale_id', 'LIKE', '%'.$search.'%');   
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
                            ->get();            
        
        /*--------------------------------------
        |  data binding
        ------------------------------*/
        $data = [];
        if (!empty($object) && sizeof($object) > 0) 
        {   
            foreach ($object as $key => $row) 
            {
                $data[$key]['id']           = ($key+$start+1);
                $data[$key]['report_id']    = !empty($row->reports) ? '#'.$row->reports->id : '-';
                $data[$key]['report_title'] = !empty($row->reports) ? $row->reports->report_title : '-';
                $data[$key]['country']      = $row->country;
                $data[$key]['fk_source_id'] = !empty($row->source) ? $row->source->name : '-';
                $data[$key]['regions']      = !empty($row->regions) ? $row->regions : '-';
                $data[$key]['created_date'] = Date('Y-m-d', strtotime($row->created_at));

                $assign = '<a title="Assign source" class="btn btn-default btn-circle" onclick="return actionSource(this)" data-rwid="'.($row->id).'" data-source-id="'.($row->fk_source_id).'"><i class="fa fa-users" aria-hidden="true"></i></a>&nbsp;';

                $data[$key]['actions'] = $assign;
            }
        }

        ## wrapping up
        $this->JsonData['draw']             = intval($request->draw);
        $this->JsonData['recordsTotal']     = intval($totalData);
        $this->JsonData['recordsFiltered']  = intval($totalFiltered);
        $this->JsonData['data']             = $data;

        return response()->json($this->JsonData);
    }

    /*----------------------------------------
    |  Function create page
    ------------------------------*/
    public function create()
    {
        $this->ViewData['modulePath']    = $this->ModulePath;
        $this->ViewData['moduleTitle']   = $this->ModuleTitle;
        $this->ViewData['moduleAction']  = 'Add '.$this->ModuleTitle;

        return view($this->ModuleView.'create', $this->ViewData);
    }

    /*--------------------------------------------
    |  Function delete 
    -----------------------------------------*/
    public function destroy($id)
    {
        $arrModel = LeadsModel::find($id);
        if($arrModel->delete())
        {
            $this->JsonData['status'] = 'success';
            $this->JsonData['msg']    = 'Leads deleted successfully.';
        }
        else
        {
            $this->JsonData['status'] = 'error';
            $this->JsonData['msg']    = 'Failed to process request due to internal server error, Please try again later.';
        }
        
        return response()->json($this->JsonData);
    }

    /*----------------------------------------
    |  Function update status
    ----------------------------------------*/
    public function updateStatus(Request $request, $id)
    {
        $arrBaseModel = LeadsModel::find($id);
        if(!empty($arrBaseModel))
        {
            $arrBaseModel->status = ($arrBaseModel->status == '1') ? '0' : '1';

            if($arrBaseModel->save())
            {
                $this->JsonData['status'] = 'success';
                $this->JsonData['msg']    = 'Status updated successfully.';
            }
            else
            {
                $this->JsonData['status'] = 'error';
                $this->JsonData['msg']    = 'Failed to process request due to internal server error, Please try again later.';
            }
        }
        else
        {
            $this->JsonData['status'] = 'error';
            $this->JsonData['msg']    = 'Failed to process request due to internal server error, Please try again later.';
        }

        
        return response()->json($this->JsonData);
    }

    /*----------------------------------------
    |  Function edit view page
    ----------------------------*/
    public function view($id)
    {
        $this->ViewData['moduleTitle']  = $this->ModuleTitle;
        $this->ViewData['moduleAction'] = 'Edit '. $this->ModuleTitle;
        $this->ViewData['modulePath']   = $this->ModulePath;
        $this->ViewData['object']       = LeadsModel::with(['reports','sales','source'])->find($id);
        $this->ViewData['arrSources']   = SourceModel::where('status','0')->get();
        
        if(!empty($this->ViewData['object']))
        {
            return view($this->ModuleView.'view', $this->ViewData);
        }
        else
        {
            return redirect($this->ModulePath.'/');
        }
    }

    /*-----------------------------------------
    |  Function update data
    -----------------------------------------*/
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $arrModel                 = LeadsModel::find($id);
        $arrModel->pipline_status = $request->pipline_status;
        $arrModel->lead_status    = $request->lead_status;
        $arrModel->fk_source_id   = $request->source;
        $arrModel->comment        = $request->comment;

        if($arrModel->save())
        {
            $this->JsonData['status']   = 'success';
            $this->JsonData['msg']      = 'Details updated successfully';
        }
        else
        {
            $this->JsonData['status'] = 'error';
            $this->JsonData['msg']    = 'Failed to process request due to internal server error, Please try again later.';
        }
        
        return response()->json($this->JsonData);
    }

    /*-----------------------------------------
    |  Function assigne lead
    -----------------------------------------*/
    public function assign_source(Request $request)
    {
        //dd($request->all());
        DB::beginTransaction();

        $arrModel = LeadsModel::find($request->lead_id);
        $arrModel->fk_source_id = $request->source;
        
        if($arrModel->save())
        {
            $this->JsonData['status']   = 'success';
            $this->JsonData['msg']      = 'Source assign successfully';
        }
        else
        {
            $this->JsonData['status'] = 'error';
            $this->JsonData['msg']    = 'Failed to process request due to internal server error, Please try again later.';
        }
        
        return response()->json($this->JsonData);
    }

    /*-----------------------------------------
    |  Function download reports
    -----------------------------------------*/
    public function download_reports(Request $request)
    {
        //https://www.geeksforgeeks.org/laravel-import-export-excel-file/
        DB::beginTransaction();

        if(!empty($request->download_reports_type))
        {
            $modelQuery = new LeadsModel();
            $modelQuery = $modelQuery->with(['reports','sales','source']);
            if($request->download_reports_type == 'today')
            {
                $date = \Carbon\Carbon::today();
                $dateNew = date("Y-m-d", strtotime($date));
                $modelQuery = $modelQuery->whereBetween('created_at', [$dateNew.' 00:00:00',$dateNew.' 23:59:59']);
            }

            if($request->download_reports_type == 'date_range')
            {
                $arrData = explode('-', $request->search_report_date);
                if(count($arrData) > 0)
                {
                    $arrData['0'] = str_replace('/', '-', trim($arrData['0']));
                    $arrData['1'] = str_replace('/', '-', trim($arrData['1']));

                    $startData = date("Y-m-d", strtotime(($arrData['0'])));
                    $endDate = date("Y-m-d", strtotime(($arrData['1'])));

                    $modelQuery = $modelQuery->whereBetween('created_at', [$startData.' 00:00:00',$endDate.' 23:59:59']);
                }
            }

            $arrLeadObj = $modelQuery->get();
            
            if(count($arrLeadObj) != 0)
            {
                $fileName = 'IBI_'.time().'.csv';
                
                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );

                $columns = array('ID','REOPRT ID','REOPRT TITLE','COUNTRY','REGION','PIPELINE STATUS','WORKABLE STATUS','SALES PERSON','SOURCE','LEAD DATE');
                
                $callback = function() use($arrLeadObj, $columns)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);
                    foreach ($arrLeadObj as $arrKey => $row)
                    {
                        ## report_title
                        $report_id = !empty($row->reports) ? $row->reports->id : '-';
                        $report_tite = !empty($row->reports) ? $row->reports->report_title : '-';

                        ## source
                        $source = !empty($row->source) ? $row->source->name : '-';

                        ## sales_person
                        $sales_person = !empty($row->sales) ? $row->sales->name : '-';

                        $arrRowData[]  = 
                            array(
                                'id'              => $row->id,
                                'report_id'       => $row->report_id,
                                'report_tite'     => $report_tite,
                                'country'         => $row->country,
                                'regions'         => $row->regions,
                                'pipline_status'  => $row->pipline_status,
                                'workable_status' => $row->lead_status,
                                'sales_person'    => $sales_person,
                                'source'          => $source,
                                'created_at'      => date('Y-m-d H:i:s', strtotime($row->created_at)),
                            );
                    }
                    
                    for ($i=0; $i < count($arrRowData); $i++)
                    { 
                        fputcsv($file, 
                            array(
                                $arrRowData[$i]['id'],
                                $arrRowData[$i]['report_id'],
                                $arrRowData[$i]['report_tite'],
                                $arrRowData[$i]['country'],
                                $arrRowData[$i]['regions'],
                                $arrRowData[$i]['pipline_status'],
                                $arrRowData[$i]['workable_status'],
                                $arrRowData[$i]['sales_person'],
                                $arrRowData[$i]['source'],
                                $arrRowData[$i]['created_at']
                            )
                        );
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }

        }
        
        Session::flash('message', 'Leads not found'); 
        Session::flash('alert-class', 'alert-danger'); 
        return redirect($this->ModulePath);
    }
}
