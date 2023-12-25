<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

## Models
use App\Models\PopupModel;

class PopupController extends Controller
{
    public function __construct()
    {       
        $this->ViewData = $this->JsonData = [];

        $this->ModuleTitle = 'Popup';
        $this->ModuleView  = 'Backend/Popup.';
        $this->ModulePath  = 'admin/popup';
    }

    /*----------------------------------------
    |  Function  listing page
    ------------------------------*/
    public function index()
    {
        $this->ViewData['modulePath']   = $this->ModulePath;
        $this->ViewData['moduleTitle']  = $this->ModuleTitle;
        $this->ViewData['moduleAction'] = $this->ModuleTitle;
        $this->ViewData['object']       = PopupModel::find('1');
        
        return view($this->ModuleView.'index', $this->ViewData);
    }

    /*-----------------------------------------
    |  Function update data
    -----------------------------------------*/
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $request->validate([
            'name'        => 'required',
            'description' => 'required',
        ],[
            'name.required'        => 'Name field is required.',
            'description.required' => 'Name field is required.',
        ]);

        $arrModel       = PopupModel::find($id);
        $arrModel->name = $request->name;
        $arrModel->description = $request->description;
        if($arrModel->save())
        {
            $this->JsonData['status'] = 'success';
            $this->JsonData['url']    = url($this->ModulePath.'/edit/'.$id);
            $this->JsonData['msg']    = 'Details updated successfully';
        }
        else
        {
            $this->JsonData['status'] = 'error';
            $this->JsonData['msg']    = 'Failed to process request due to internal server error, Please try again later.';
        }
        
        return response()->json($this->JsonData);
    }
}
