<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use Stripe;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

## Services
use App\Services\EmailServices;

## Models
use App\Models\User;
use App\Models\LeadsModel;
use App\Models\ReportsModel;
use App\Models\CountryModel;
use App\Models\CategoryModel;
use App\Models\PublisherModel;
use App\Models\SiteSettingModel;
use App\Models\TransactionsModel;
use App\Models\RegionsCountryModel;

class ThankYouController extends Controller
{
    public function __construct()
    {
        $this->ViewData = [];
        $this->JsonData = [];

        $this->ModuleTitle = 'Reports';
        $this->ModuleView  = 'Frontend/';

        $this->siteSetting = SiteSettingModel::find(1);
        $this->emailServices = new EmailServices();
    }

    public function index()
    {
        $this->ViewData['moduleTitle']  = 'Thank You';
        $this->ViewData['moduleAction'] = 'Thank You';
        $this->ViewData['siteSetting']  = $this->siteSetting;

        Session::flush();
        
        return view($this->ModuleView.'/thankyou', $this->ViewData);
    }

    public function payment_thank(Request $request)
    {
        $this->ViewData['moduleTitle']  = $this->ViewData['moduleAction'] = 'Thank You';
        $this->ViewData['siteSetting']  = $this->siteSetting;
        $this->ViewData['response']     = $request->response;
        
        Session::flush();
        
        return view($this->ModuleView.'/payment_thank', $this->ViewData);
    }

    public function payment_failed()
    {
        $this->ViewData['moduleTitle']  = 'Failed';
        $this->ViewData['moduleAction'] = 'Failed';
        $this->ViewData['siteSetting']  = $this->siteSetting;

        Session::flush();
        
        return view($this->ModuleView.'/payment_failed', $this->ViewData);
    }
}
