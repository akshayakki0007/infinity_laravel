<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

## Models
use App\Models\CategoryModel;

class CategoryController extends Controller
{
    public function __construct()
    {       
        $this->ViewData = $this->JsonData = [];

        $this->ModuleTitle = 'Category';
        $this->ModuleView  = 'Backend/category.';
        $this->ModulePath  = 'admin/category';
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
                1 => 'name',
                2 => 'slug',
                3 => 'status',
                4 => 'id'
            );

        /*--------------------------------------
        |  Model query and filter
        ------------------------------*/

        ## start model query
        $modelQuery = new CategoryModel();

        ## get total count 
        $countQuery = clone($modelQuery);            
        $totalData  = $countQuery->count();

        ## filter options
        if (!empty($search)) 
        {
        
            $modelQuery = $modelQuery->where(function ($query) use($search)
            {
                $query->orwhere('id', 'LIKE', '%'.$search.'%');   
                $query->orwhere('name', 'LIKE', '%'.$search.'%');   
                $query->orwhere('slug', 'LIKE', '%'.$search.'%');   
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
                $data[$key]['name']         = '<span title="'.$row->name.'">'.$row->name.'</span>';
                $data[$key]['slug']         = $row->slug;
                $data[$key]['created_date'] = Date('Y-m-d', strtotime($row->created_at));

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
            'name' => 'required|min:1|unique:tbl_category,name',
            'slug' => 'required|min:1',
        ],[
            'name.required' => 'Name field is required.',
            'slug.required' => 'Name field is required.',
        ]);

        if ($request->hasFile('image'))
        {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }

        $arrModel              = new CategoryModel();
        $arrModel->name        = $request->name;
        $arrModel->slug        = $request->slug;
        $arrModel->description = $request->description;

        if ($request->hasFile('image'))
        {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/category',$filename);
            $arrModel->image = $filename;
        }

        $arrModel->status = '0';
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
        $this->ViewData['object']       = CategoryModel::find($id);
        
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
            'name' => 'required|min:1|unique:tbl_category,name,'.$id,
            'slug' => 'required|min:1',
        ],[
            'name.required' => 'Name field is required.',
            'slug.required' => 'Name field is required.',
        ]);

        if ($request->hasFile('image'))
        {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }

        $arrModel              = CategoryModel::find($id);
        $arrModel->name        = $request->name;
        $arrModel->slug        = $request->slug;
        $arrModel->description = $request->description;

        if ($request->hasFile('image'))
        {
            if(!empty($arrModel->image))
            {
                unlink(storage_path('app/public/category/'.$arrModel->image));
            }

            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/category',$filename);
            $arrModel->image = $filename;
        }

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
    public function destroy($id)
    {
        $arrModel = CategoryModel::find($id);
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

    /*----------------------------------------
    |  Function update status
    ----------------------------------------*/
    public function updateStatus(Request $request, $id)
    {
        $arrBaseModel = CategoryModel::find($id);
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
}
