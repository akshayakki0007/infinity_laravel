<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

## Models
use App\Models\BlogsModel;
use App\Models\CategoryModel;

class BlogsController extends Controller
{
   public function __construct()
    {       
        $this->ViewData = $this->JsonData = [];

        $this->ModuleTitle = 'Blogs';
        $this->ModuleView  = 'Backend/blogs.';
        $this->ModulePath  = 'admin/blogs';
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
                1 => 'title',
                2 => 'fk_category',
                3 => 'author',
                4 => 'status',
                5 => 'id'
            );

        /*--------------------------------------
        |  Model query and filter
        ------------------------------*/

        ## start model query
        $modelQuery = new BlogsModel();
        $modelQuery = $modelQuery->with(['category']);

        ## get total count 
        $countQuery = clone($modelQuery);            
        $totalData  = $countQuery->count();

        ## filter options
        if (!empty($search)) 
        {
        
            $modelQuery = $modelQuery->where(function ($query) use($search)
            {
                $query->orwhere('id', 'LIKE', '%'.$search.'%');
                $query->orwhere('title', 'LIKE', '%'.$search.'%');
                $query->orwhere('fk_category', 'LIKE', '%'.$search.'%');
                $query->orwhere('author', 'LIKE', '%'.$search.'%');
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
                $data[$key]['title']        = '<span title="'.$row->title.'">'.$row->title.'</span>';
                $data[$key]['fk_category']  = !empty($row->category) ? $row->category->name : '-';
                $data[$key]['author']       = $row->author;
                $data[$key]['created_date'] = Date('Y-m-d', strtotime($row->created_at));

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
                
                $data[$key]['actions'] = $edit.$delete;
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
        $this->ViewData['arrCategory']   = CategoryModel::where('status','0')->get();

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
            'title'        => 'required|min:1|unique:tbl_blogs,title',
            'description'  => 'required|min:1',
            'category'     => 'required|min:1',
            'author'       => 'required|min:1',
            'publish_date' => 'required|min:1',
        ],[
            'title.required'        => 'Name field is required.',
            'description.required'  => 'Description field is required.',
            'category.required'     => 'Category field is required.',
            'author.required'       => 'Author field is required.',
            'publish_date.required' => 'Publish date field is required.',
        ]);

        $arrModel                    = new BlogsModel();
        $arrModel->title             = $request->title;
        $arrModel->description       = $request->description;
        $arrModel->short_description = $request->short_description;
        $arrModel->fk_category       = $request->category;
        $arrModel->author            = $request->author;
        $arrModel->publish_date      = $request->publish_date;
        $arrModel->status            = '0';

        if($arrModel->save()) 
        {
            $intLastOnertedId = $arrModel->id;
            DB::commit();
            $this->JsonData['status'] = 'success';
            $this->JsonData['url']    = url($this->ModulePath.'/edit/'.($intLastOnertedId));
            $this->JsonData['msg']    = 'Blog added successfully.';
        }
        else
        {
            DB::rollBack();
            $this->JsonData['status'] = 'error';
            $this->JsonData['msg']    = 'Failed to blog add, Something went wrong.';
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
        $this->ViewData['object']       = BlogsModel::find($id);
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
        //dd($request->all());
        DB::beginTransaction();

        $request->validate([
            'title'        => 'required|min:1|unique:tbl_blogs,title,'.$id,
            'description'  => 'required|min:1',
            'category'     => 'required|min:1',
            'author'       => 'required|min:1',
            'publish_date' => 'required|min:1',
        ],[
            'title.required'        => 'Name field is required.',
            'description.required'  => 'Description field is required.',
            'category.required'     => 'Category field is required.',
            'author.required'       => 'Author field is required.',
            'publish_date.required' => 'Publish date field is required.',
        ]);

        $arrModel                    = BlogsModel::find($id);
        $arrModel->title             = $request->title;
        $arrModel->description       = $request->description;
        $arrModel->short_description = $request->short_description;
        $arrModel->fk_category       = $request->category;
        $arrModel->author            = $request->author;
        $arrModel->publish_date      = $request->publish_date;

        if($arrModel->save())
        {
            $this->JsonData['status'] = 'success';
            $this->JsonData['url']    = url($this->ModulePath.'/edit/'.$id);
            $this->JsonData['msg']    = 'Blog details updated successfully';
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
        $arrModel = BlogsModel::find($id);
        if($arrModel->delete())
        {
            $this->JsonData['status'] = 'success';
            $this->JsonData['msg']    = 'Blog deleted successfully.';
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
        $arrBaseModel = BlogsModel::find($id);
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
