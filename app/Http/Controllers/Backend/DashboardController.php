<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\DashbaordModel;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->ViewData = [];
        $this->JsonData = [];

        $this->ModuleTitle = 'Dashboard';
        $this->ModuleView  = 'Backend/Dashboard.';
        $this->ModulePath  = 'admin/dashboard';
    }

    public function index(Request $request)
    {
        $user_role = Auth::user()->role;
        $this->ViewData['modulePath']     = $this->ModulePath;
        $this->ViewData['moduleTitle']    = $this->ModuleTitle;
        $this->ViewData['moduleAction']   = $this->ModuleTitle;
        $this->ViewData['dashbaordModel'] = new DashbaordModel();
        $this->ViewData['user_role']      = $user_role;
        $this->ViewData['user_name']      = Auth::user()->name;
        
        if($user_role == 'super_admin')
        {
            if(!empty($request->search_date))
            {
                $arrData = explode('-', $request->search_date);

                $arrData['0'] = str_replace('/', '-', trim($arrData['0']));
                $arrData['1'] = str_replace('/', '-', trim($arrData['1']));

                $startData = date("Y-m-d", strtotime(($arrData['0'])));
                $endDate = date("Y-m-d", strtotime(($arrData['1'])));

                ## get Payment count
                $this->ViewData['total_purchase'] = DB::table('tbl_transaction')->where('payment_status','success')->whereBetween('created_at', [$startData.' 00:00:00',$endDate.' 23:59:59'])->count();
                $this->ViewData['purchase_failed_count']    = DB::table('tbl_transaction')->where('payment_status','failed')->whereBetween('created_at', [$startData.' 00:00:00',$endDate.' 23:59:59'])->count();
                $this->ViewData['purchase_cancelled_count'] = DB::table('tbl_transaction')->where('payment_status','cancelled')->whereBetween('created_at', [$startData.' 00:00:00',$endDate.' 23:59:59'])->count();
                $this->ViewData['purchase_stripe_count']    = DB::table('tbl_transaction')->where('payment_type','stripe')->whereBetween('created_at', [$startData.' 00:00:00',$endDate.' 23:59:59'])->count();
                $this->ViewData['purchase_paypal_count']    = DB::table('tbl_transaction')->where('payment_type','paypal')->whereBetween('created_at', [$startData.' 00:00:00',$endDate.' 23:59:59'])->count();
                
                ## get Lead count    
                $this->ViewData['total_leads']        = DB::table('tbl_enquiry')->where('status','0')->whereBetween('created_at', [$startData.' 00:00:00',$endDate.' 23:59:59'])->count();
                $this->ViewData['workable_leads']     = DB::table('tbl_enquiry')->where('lead_status','workable')->where('status','0')->whereBetween('created_at', [$startData.' 00:00:00',$endDate.' 23:59:59'])->count();
                $this->ViewData['non_workable_leads'] = DB::table('tbl_enquiry')->where('lead_status','non_workable')->where('status','0')->whereBetween('created_at', [$startData.' 00:00:00',$endDate.' 23:59:59'])->count();
            }
            else
            {
                ## get Payment count
                $this->ViewData['total_purchase']           = DB::table('tbl_transaction')->where('payment_status','success')->count();
                $this->ViewData['purchase_failed_count']    = DB::table('tbl_transaction')->where('payment_status','failed')->count();
                $this->ViewData['purchase_cancelled_count'] = DB::table('tbl_transaction')->where('payment_status','cancelled')->count();
                $this->ViewData['purchase_stripe_count']    = DB::table('tbl_transaction')->where('payment_type','stripe')->count();
                $this->ViewData['purchase_paypal_count']    = DB::table('tbl_transaction')->where('payment_type','paypal')->count();
                
                ## get Lead count    
                $this->ViewData['total_leads']        = DB::table('tbl_enquiry')->where('status','0')->count();
                $this->ViewData['workable_leads']     = DB::table('tbl_enquiry')->where('lead_status','workable')->where('status','0')->count();
                $this->ViewData['non_workable_leads'] = DB::table('tbl_enquiry')->where('lead_status','non_workable')->where('status','0')->count();
            }

            $this->ViewData['arrSalesObj']  = DB::table('users')->where('role','sales')->where('status','0')->get(['id','name']);
            $this->ViewData['arrSourceObj'] = DB::table('tbl_source')->where('status','0')->get(['id','name']);
        }

        $this->ViewData['startData']   = isset($startData) ? $startData : '';
        $this->ViewData['endDate']     = isset($endDate) ? $endDate : '' ;
        $this->ViewData['search_date'] = isset($request->search_date) ? $request->search_date : '' ;

        return view($this->ModuleView.'index', $this->ViewData);
    }

    public function search_record(Request $request)
    {
        if(!empty($request->search_date))
        {
            $arrData = explode('-', $request->search_date);

            $arrData['0'] = str_replace('/', '-', trim($arrData['0']));
            $arrData['1'] = str_replace('/', '-', trim($arrData['1']));

            $startData = date("Y-m-d", strtotime(($arrData['0'])));
            $endDate = date("Y-m-d", strtotime(($arrData['1'])));
        }
    }
}
