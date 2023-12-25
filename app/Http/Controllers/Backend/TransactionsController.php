<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


## Models
use App\Models\User;
use App\Models\LeadsModel;
use App\Models\SourceModel;
use App\Models\TransactionsModel;


class TransactionsController extends Controller
{
   public function __construct()
    {       
        $this->ViewData = $this->JsonData = [];

        $this->ModuleTitle = 'Purchase';
        $this->ModuleView  = 'Backend/Transactions.';
        $this->ModulePath  = 'admin/transactions';
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
                2 => 'name',
                3 => 'email_id',
                4 => 'contact_no',
                5 => 'company_name',
                6 => 'job_title',
                7 => 'licence_price',
                8 => 'payment_type',
                9 => 'id'
            );

        /*--------------------------------------
        |  Model query and filter
        ------------------------------*/

        ## start model query
        $modelQuery = new TransactionsModel();
        $modelQuery = $modelQuery->with(['reports']);
        
        if($request->action != '')
        {
            $modelQuery = $modelQuery->where(function ($query) use ($request)
                                {
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
                $query->orwhere('name', 'LIKE', '%'.$search.'%');   
                $query->orwhere('email_id', 'LIKE', '%'.$search.'%');   
                $query->orwhere('contact_no', 'LIKE', '%'.$search.'%');   
                $query->orwhere('company_name', 'LIKE', '%'.$search.'%');   
                $query->orwhere('job_title', 'LIKE', '%'.$search.'%');   
                $query->orwhere('licence_price', 'LIKE', '%'.$search.'%');   
                $query->orwhere('payment_type', 'LIKE', '%'.$search.'%');   
                $query->orwhere('payment_status', 'LIKE', '%'.$search.'%');   
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
                $data[$key]['id']             = ($key+$start+1);
                $data[$key]['report_id']      = !empty($row->reports) ? '#'.$row->reports->id : '-';
                $data[$key]['name']           = $row->name;
                $data[$key]['email_id']       = $row->email_id;
                $data[$key]['contact_no']     = $row->contact_no;
                $data[$key]['company_name']   = $row->company_name;
                $data[$key]['job_title']      = $row->job_title;
                $data[$key]['licence_price']  = $row->licence_price;
                $data[$key]['payment_type']   = $row->payment_type;

                switch ($row->payment_status)
                {
                    case 'success':
                        $data[$key]['payment_status'] = '<label class="label label-success">Success</label>';
                    break;
                    case 'failed':
                        $data[$key]['payment_status'] = '<label class="label label-danger">Failed</label>';
                    break;
                    case 'cancelled':
                        $data[$key]['payment_status'] = '<label class="label label-danger">Cancelled</label>';
                    break;
                    case 'pending':
                        $data[$key]['payment_status'] = '<label class="label label-warning">Pending</label>';
                    break;
                }

                $data[$key]['created_at']     = Date('Y-m-d', strtotime($row->created_at));

                $view = '<a title="Edit" class="btn btn-default btn-circle" href="'.url($this->ModulePath.'/view', [ ($row->id)]).'"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;';

                $delete = '<a title="Trash" onclick="return actionDelete(this)" data-qsnid="'.($row->id).'" class="btn btn-default btn-circle" href="javascript:void(0)"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                $data[$key]['actions'] = $view.$delete;
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
        $arrModel = TransactionsModel::find($id);
        if($arrModel->delete())
        {
            $this->JsonData['status'] = 'success';
            $this->JsonData['msg']    = 'Transactions deleted successfully.';
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
        $this->ViewData['object']       = TransactionsModel::with(['reports'])->find($id);
        
        if(!empty($this->ViewData['object']))
        {
            return view($this->ModuleView.'view', $this->ViewData);
        }
        else
        {
            return redirect($this->ModulePath.'/');
        }
    }
}
