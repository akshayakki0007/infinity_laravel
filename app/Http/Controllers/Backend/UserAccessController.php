<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Session;

## Models
use App\Models\User;
use App\Models\ModulesModel;
use App\Models\UserAccessModel;

class UserAccessController extends Controller
{
    public function __construct()
    {       
        $this->ViewData = $this->JsonData = [];

        $this->ModuleTitle = 'User access';
        $this->ModuleView  = 'Backend/user_access.';
        $this->ModulePath  = 'admin/user_access';
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

    /*----------------------------------------
    |  Function edit view page
    ----------------------------*/
    public function access($slug)
    {
        $this->ViewData['moduleTitle']  = $this->ModuleTitle;
        $this->ViewData['moduleAction'] = 'Update access';
        $this->ViewData['modulePath']   = $this->ModulePath;
        $this->ViewData['slug']         = $slug;
        $this->ViewData['arrModules']   = ModulesModel::where('status','0')->where('slug','!=','user_access')->get();

        return view($this->ModuleView.'access', $this->ViewData);
    }

    /*-----------------------------------------
    |  Function update data
    -----------------------------------------*/
    public function update_access(Request $request)
    {
        $intRecordCnt = DB::table('tbl_modules_access')->where('role',$request->role)->count();

        if($intRecordCnt > 0)
        {
            DB::table('tbl_modules_access')->where('role',$request->role)->delete();               
            foreach ($request->modules as $key => $value)
            {   
                DB::table('tbl_modules_access')->insert([
                    'role' => $request->role,
                    'modules' => $value,
                    'list'    => (!empty($request->$value['list'])) ? $request->$value['list'] : '0',
                ]);
            }
        }
        else
        {
            foreach ($request->modules as $key => $value)
            {   
                DB::table('tbl_modules_access')->insert([
                    'role' => $request->role,
                    'modules' => $value,
                    'list'    => (!empty($request->$value['list'])) ? $request->$value['list'] : '0',
                ]);
            }
        }

        Session::flash('message', 'Access added successfully!'); 
        Session::flash('alert-class', 'alert-success'); 
        return redirect($this->ModulePath.'/access/'.$request->role);
    }
}
