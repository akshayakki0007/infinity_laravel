<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

## Models
use App\Models\ReportsModel;
use App\Models\CategoryModel;
use App\Models\PublisherModel;

class ReportsController extends Controller
{
    public function __construct()
    {       
        $this->ViewData = $this->JsonData = [];

        $this->ModuleTitle = 'Reports';
        $this->ModuleView  = 'Backend/reports.';
        $this->ModulePath  = 'admin/reports';
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
        //dd($request->all());
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
                1 => 'id',
                2 => 'fk_category_id',
                3 => 'report_title',
                4 => 'status',
                5 => 'created_at',
                6 => 'id'
            );

        /*--------------------------------------
        |  Model query and filter
        ------------------------------*/

        ## start model query
        $modelQuery = new ReportsModel();
        $modelQuery = $modelQuery->with(['category']);
        if($request->action != '')
        {
            $modelQuery = $modelQuery->where(function ($query) use ($request)
                                {
                                    if($request->action == 'search')
                                    {
                                        $arrData = explode('-', $request->search_order_date);
                                        if(count($arrData) > 0)
                                        {
                                            $startData = date("Y-d-m", strtotime(trim($arrData['0'])));
                                            $endDate   = date("Y-d-m", strtotime(trim($arrData['1'])));
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
                $query->orwhere('fk_category_id', 'LIKE', '%'.$search.'%');   
                $query->orwhere('report_title', 'LIKE', '%'.$search.'%');   
                $query->orwhere('status', 'LIKE', '%'.$search.'%');   
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
                            ->get(['id','fk_category_id','report_title','created_at','status']);            
        
        /*--------------------------------------
        |  data binding
        ------------------------------*/
        $data = [];
        if (!empty($object) && sizeof($object) > 0) 
        {   
            foreach ($object as $key => $row) 
            {
                $data[$key]['id']             = ($key+$start+1);
                $data[$key]['report_title']   = '<span title="'.$row->report_title.'">'.$row->report_title.'</span>';
                $data[$key]['fk_category_id'] = !empty($row->category) ? $row->category->name : '-';
                $data[$key]['created_date']   = Date('Y-m-d', strtotime($row->created_at));

                $view = '';

                if($row->status == '0')
                {
                    $data[$key]['status']   = '<a title="Inactive" onclick="return actionUpdateStatus(this)" data-rwid="'.($row->id).'" data-rwst="1"  class="btn btn-default btn-circle" href="javascript:void(0)"><i class="fa fa-check" aria-hidden="true"></i></a>&nbsp';
                }
                else
                {
                    $data[$key]['status']   = '<a title="Inactive" onclick="return actionUpdateStatus(this)" data-rwid="'.($row->id).'" data-rwst="1"  class="btn btn-default btn-circle" href="javascript:void(0)"><i class="fa fa-times" aria-hidden="true"></i></a>&nbsp';
                }

                $edit = '<a title="Edit" class="btn btn-default btn-circle" href="'.url($this->ModulePath.'/edit', [ ($row->id)]).'"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;';
                
                $delete = '<a title="Trash" onclick="return actionDelete(this)" data-qsnid="'.($row->id).'" class="btn btn-default btn-circle" href="javascript:void(0)"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                
                $data[$key]['actions'] = $view.$edit.$delete;
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
        $this->ViewData['arrPublisher'] = PublisherModel::where('status','0')->get();
        $this->ViewData['arrCategory']  = CategoryModel::where('status','0')->get();
        
        return view($this->ModuleView.'create', $this->ViewData);
    }

    /*-----------------------------------------
    |  Function store data
    ---------------------------------------*/
    public function store(Request $request)
    {
        //dd($request->all());
        DB::beginTransaction();

        $request->validate([
            'report_title'      => 'required|min:1|unique:tbl_reports,report_title',
            'slug'              => 'required|min:1|unique:tbl_reports,slug',
            'single_user_price' => 'required',
            'multi_user_price'  => 'required',
            'pages'             => 'required',
            'description'       => 'required',
            'toc'               => 'required',
        ],[
            'report_title.required'      => 'Report title field is required.',
            'slug.required'              => 'Report slug field is required.',
            'single_user_price.required' => 'Single user price field is required.',
            'multi_user_price.required'  => 'Multi user price field is required.',
            'pages.required'             => 'Pages field is required.',
            'description.required'       => 'Description field is required.',
            'toc.required'               => 'Table of contents field is required.',
        ]);

        $arrModel                    = new ReportsModel();
        $arrModel->fk_category_id    = $request->category;
        $arrModel->fk_publisher_id   = $request->publisher;
        $arrModel->report_title      = $request->report_title;
        $arrModel->slug              = $request->slug;
        $arrModel->single_user_price = $request->single_user_price;
        $arrModel->multi_user_price  = $request->multi_user_price;
        $arrModel->pages             = $request->pages;
        $arrModel->description       = $request->description;
        $arrModel->toc               = $request->toc;
        $arrModel->status            = '0';

        if($arrModel->save()) 
        {
            $intLastOnertedId = $arrModel->id;
            DB::commit();
            $this->JsonData['status']   = 'success';
            $this->JsonData['url']      = url($this->ModulePath.'/edit/'.($intLastOnertedId));
            $this->JsonData['msg']      = 'Category added successfully.';
        }
        else
        {
            DB::rollBack();
            $this->JsonData['status']   = 'error';
            $this->JsonData['msg']      = 'Failed to category add, Something went wrong.';
        }

        return response()->json($this->JsonData);
    }

    /*----------------------------------------
    |  Function edit view page
    ----------------------------*/
    public function edit($id)
    {
        $this->ViewData['moduleTitle']  = $this->ModuleTitle;
        $this->ViewData['moduleAction'] = 'Edit '. $this->ModuleTitle;
        $this->ViewData['modulePath']   = $this->ModulePath;
        $this->ViewData['object']       = ReportsModel::find($id);
        $this->ViewData['arrPublisher'] = PublisherModel::where('status','0')->get();
        $this->ViewData['arrCategory']  = CategoryModel::where('status','0')->get();
        
        if(!empty($this->ViewData['object']))
        {
            return view($this->ModuleView.'edit', $this->ViewData);
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

        $request->validate([
            'report_title'      => 'required|min:1|unique:tbl_reports,report_title,'.$id,
            'slug'              => 'required|min:1|unique:tbl_reports,slug,'.$id,
            'single_user_price' => 'required',
            'multi_user_price'  => 'required',
            'pages'             => 'required',
            'description'       => 'required',
            'toc'               => 'required',
        ],[
            'report_title.required'      => 'Report title field is required.',
            'slug.required'              => 'Report slug field is required.',
            'single_user_price.required' => 'Single user price field is required.',
            'multi_user_price.required'  => 'Multi user price field is required.',
            'pages.required'             => 'Pages field is required.',
            'description.required'       => 'Description field is required.',
            'toc.required'               => 'Table of contents field is required.',
        ]);

        $arrModel                    = ReportsModel::find($id);
        $arrModel->fk_category_id    = $request->category;
        $arrModel->fk_publisher_id   = $request->publisher;
        $arrModel->report_title      = $request->report_title;
        $arrModel->slug              = $request->slug;
        $arrModel->single_user_price = $request->single_user_price;
        $arrModel->multi_user_price  = $request->multi_user_price;
        $arrModel->pages             = $request->pages;
        $arrModel->description       = $request->description;
        $arrModel->toc               = $request->toc;

        if($arrModel->save())
        {
            $this->JsonData['status']   = 'success';
            $this->JsonData['url']      = url($this->ModulePath.'/edit/'.$id);
            $this->JsonData['msg']      = 'Category details updated successfully';
        }
        else
        {
            $this->JsonData['status'] = 'error';
            $this->JsonData['msg']    = 'Failed to process request due to internal server error, Please try again later.';
        }
        
        return response()->json($this->JsonData);
    }

    /*--------------------------------------------
    |  Function delete 
    -----------------------------------------*/
    public function delete($id)
    {
        $arrModel = ReportsModel::find($id);

        if($arrModel->delete())
        {
            $this->JsonData['status'] = 'success';
            $this->JsonData['msg']    = 'Category deleted successfully.';
        }
        else
        {
            $this->JsonData['status'] = 'error';
            $this->JsonData['msg']    = 'Failed to process request due to internal server error, Please try again later.';
        }
        
        return response()->json($this->JsonData);
    }
}
