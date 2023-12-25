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

class BulkReportsController extends Controller
{
    public function __construct()
    {       
        $this->ViewData = $this->JsonData = [];

        $this->ModuleTitle = 'Reports';
        $this->ModuleView  = 'Backend/Bulk_reports.';
        $this->ModulePath  = 'admin/bulk_reports_download';
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
                1 => 'report_id',
                2 => 'report_title',
                3 => 'fk_category_id',
                4 => 'id'
            );

        /*--------------------------------------
        |  Model query and filter
        ------------------------------*/

        ## start model query
        $modelQuery = new ReportsModel();
        $modelQuery = $modelQuery->with(['category']);

        if($request->search_type == 0)
        {
            $modelQuery = $modelQuery->where(function ($query) use ($request){
                    $query->whereIn('id',explode(',', $request->search_report_id));
                });
        }
        elseif ($request->search_type == 1)
        {
            if(!empty($request->search_from_report_id) && !empty($request->search_to_report_id))
            {
                $modelQuery = $modelQuery->where(function ($query) use ($request){
                        $query->whereBetween('id', [$request->search_from_report_id,$request->search_to_report_id]);
                    });
            }
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
                $query->orwhere('report_title', 'LIKE', '%'.$search.'%');  
                $query->orwhere('fk_category_id', 'LIKE', '%'.$search.'%');
            });
        }

        ## get total filtered
        $filteredQuery = clone($modelQuery);            
        $totalFiltered  = $filteredQuery->count();
        
        ## offset and limit
        $object = $modelQuery->orderBy($filter[$column], $dir)
                            ->skip($start)
                            ->take($length)
                            ->get(['id','fk_category_id','report_title']);            
        
        /*--------------------------------------
        |  data binding
        ------------------------------*/
        $data = [];
        if (!empty($object) && sizeof($object) > 0) 
        {   
            foreach ($object as $key => $row) 
            {
                $data[$key]['id']             = ($key+$start+1);
                $data[$key]['id']             = $row->id;
                $data[$key]['report_title']   = '<span title="'.$row->report_title.'">'.$row->report_title.'</span>';
                $data[$key]['fk_category_id'] = !empty($row->category) ? $row->category->name : '-';
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
    public function download_reports(Request $request)
    {
        DB::beginTransaction();

        $modelQuery = new ReportsModel();
        $modelQuery = $modelQuery->with(['category']);

        if($request->search_type == 0)
        {
            $modelQuery = $modelQuery->where(function ($query) use ($request){
                    $query->whereIn('id',explode(',', $request->search_report_id));
                });
        }
        elseif ($request->search_type == 1)
        {
            if(!empty($request->search_from_report_id) && !empty($request->search_to_report_id))
            {
                $modelQuery = $modelQuery->where(function ($query) use ($request){
                        $query->whereBetween('id', [$request->search_from_report_id,$request->search_to_report_id]);
                    });
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

            $columns[] = "ReportID";
            $columns[] = "Report Title";
            $columns[] = "Cateogory";
            $columns[] = "Publisher Name";
            $columns[] = "Single Amount";
            $columns[] = "Multiple Amount";
            $columns[] = "Report Description / Summary";
            $columns[] = "TOC";
            $columns[] = "Request to sample URL";
            $columns[] = "Ask For Discount URL";
            $columns[] = "Enquiry Before Buying URL";
            $columns[] = "BuyNow URL";
            $columns[] = "Report URL";
            $columns[] = "Created Date";
            
            $callback = function() use($arrLeadObj, $columns)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($arrLeadObj as $arrKey => $row)
                {
                    $arrRowData[]  = 
                        array(
                            'report_id'       => $row->id,
                            'report_tite'     => $row->report_title,
                            'cateogory'       => !empty($row->category) ? $row->category->name : '-' ,
                            'publisher_name'  => !empty($row->publisher) ? $row->publisher->name : '-',
                            'single_amount'   => $row->single_user_price,
                            'multiple_amount' => $row->multi_user_price,
                            'description'     => $row->description,
                            'toc'             => $row->toc,
                            'sample_url'      => url('request_sample.php?id='.$row->id),
                            'discount_url'    => url('ask_for_discount.php?id='.$row->id),
                            'buying_url'      => url('enquiry_before_buying.php?id='.$row->id),
                            'buy_now_url'     => url('checkout.php?id='.$row->id),
                            'report_url'      => url('reports/'.$row->slug.'-'.$row->report_title),
                            'created_at'      => date('Y-m-d H:i:s', strtotime($row->created_at)),
                        );
                }
                
                for ($i=0; $i < count($arrRowData); $i++)
                { 
                    fputcsv($file, 
                        array(
                            $arrRowData[$i]['report_id'],
                            $arrRowData[$i]['report_tite'],
                            $arrRowData[$i]['cateogory'],
                            $arrRowData[$i]['publisher_name'],
                            $arrRowData[$i]['single_amount'],
                            $arrRowData[$i]['multiple_amount'],
                            $arrRowData[$i]['description'],
                            $arrRowData[$i]['toc'],
                            $arrRowData[$i]['sample_url'],
                            $arrRowData[$i]['discount_url'],
                            $arrRowData[$i]['buying_url'],
                            $arrRowData[$i]['buy_now_url'],
                            $arrRowData[$i]['report_url'],
                            $arrRowData[$i]['created_at']
                        )
                    );
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        Session::flash('message', 'Reports not found'); 
        Session::flash('alert-class', 'alert-danger');

        return redirect($this->ModulePath);
    }
}
