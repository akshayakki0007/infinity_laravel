<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

## Models
use App\Models\SiteSettingModel;

class SiteSettingController extends Controller
{
    public function __construct()
    {       
        $this->ViewData = $this->JsonData = [];

        $this->ModuleTitle = 'Site Setting';
        $this->ModuleView  = 'Backend/site_setting.';
        $this->ModulePath  = 'admin/site_setting';
    }

    /*----------------------------------------
    |  Function  listing page
    ------------------------------*/
    public function index()
    {
        $this->ViewData['modulePath']    = $this->ModulePath;
        $this->ViewData['moduleTitle']   = $this->ModuleTitle;
        $this->ViewData['moduleAction']  = $this->ModuleTitle;
        $this->ViewData['arrSettingObj'] = SiteSettingModel::find('1');
        
        return view($this->ModuleView.'create', $this->ViewData);
    }

    /*-----------------------------------------
    |  Function update data
    -----------------------------------------*/
    /*-----------------------------------------
    |  Function update data
    -----------------------------------------*/
    public function update(Request $request)
    {
        //dd($request->all());
        DB::beginTransaction();

        $request->validate([
            'site_name'   => 'required|min:1',
            'site_email'  => 'required|min:1',
            'site_mobile' => 'required|min:1',
        ],[
            'site_name.required'   => 'Site name field is required.',
            'site_email.required'  => 'Site email field is required.',
            'site_mobile.required' => 'Site mobile field is required.',
        ]);

        $arrModel                 = SiteSettingModel::find(1);
        $arrModel->site_name      = $request->site_name;
        $arrModel->site_email     = $request->site_email;
        $arrModel->site_mobile    = $request->site_mobile;
        $arrModel->site_address   = $request->site_address;
        $arrModel->site_logo      = $request->site_logo;
        $arrModel->discount_amt   = $request->discount;
        $arrModel->email_setting  = json_encode($request->email);
        $arrModel->stripe_setting = json_encode($request->stripe);
        $arrModel->paypal_setting = json_encode($request->paypal);

        if($arrModel->save())
        {
            $this->JsonData['status']   = 'success';
            $this->JsonData['msg']      = 'Setting details updated successfully';
        }
        else
        {
            $this->JsonData['status'] = 'error';
            $this->JsonData['msg']    = 'Failed to process request due to internal server error, Please try again later.';
        }
        
        return response()->json($this->JsonData);
    }
}
