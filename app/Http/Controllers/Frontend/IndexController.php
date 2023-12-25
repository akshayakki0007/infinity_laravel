<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

## Services
use App\Services\EmailServices;

## Models
use App\Models\User;
use App\Models\BlogsModel;
use App\Models\PagesModel;
use App\Models\ReportsModel;
use App\Models\CountryModel;
use App\Models\CategoryModel;
use App\Models\PublisherModel;
use App\Models\ContactUsModel;
use App\Models\SiteSettingModel;
use App\Models\RegionsCountryModel;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->ViewData = [];
        $this->JsonData = [];

        $this->ModuleTitle = 'Dashboard';
        $this->ModuleView  = 'Frontend/';

        $this->siteSetting = SiteSettingModel::find(1);
        $this->emailServices = new EmailServices();
    }

    public function index()
    {
        $intCatTopSelling  = CategoryModel::where('slug','top-selling')->first(['id']);
        $intCatTopTrending = CategoryModel::where('slug','top-trending')->first(['id']);
        $intCatRecenAdded  = CategoryModel::where('slug','recently-added')->first(['id']);
        $intTopRated  = CategoryModel::where('slug','top-rated')->first(['id']);

        $this->ViewData['moduleTitle']    = $this->siteSetting->site_name;
        $this->ViewData['moduleAction']   = $this->siteSetting->site_name;
        $this->ViewData['arrCategory']    = CategoryModel::where('status','0')->get(['id','name','slug']);
        $this->ViewData['arrTopSelling']  = getCategoryReports($intCatTopSelling->id,3);
        $this->ViewData['arrTopTrending'] = getCategoryReports($intCatTopTrending->id,3);
        $this->ViewData['arrRecenAdded']  = getCategoryReports($intCatRecenAdded->id,3);
        $this->ViewData['arrTopRated']    = getCategoryReports($intTopRated->id,3);
        $this->ViewData['arrReports']     = getReports(5);
        
        //dd($this->ViewData);
        return view($this->ModuleView.'/index', $this->ViewData);
    }

    public function pages($slug)
    {
        $this->ViewData['arrCountry']   = CountryModel::get(['id','name','nicename','phonecode']);
        $this->ViewData['arrSetting']   = $this->siteSetting;
        if($slug == 'contact')
        {
            $this->ViewData['moduleTitle']  = 'Contact Us';
            $this->ViewData['moduleAction'] = 'Contact Us';

            return view($this->ModuleView.'/contactus_page', $this->ViewData);
        }

        $this->ViewData['arrPages'] = $arrPages = PagesModel::where('slug',$slug)->first();
        
        if(!empty($arrPages))
        {
            $this->ViewData['moduleTitle']  = $arrPages->name;
            $this->ViewData['moduleAction'] = $arrPages->name;
            
            return view($this->ModuleView.'/pages', $this->ViewData);
        }
        else
        {
            return redirect()->route('home');
        }
    }

    public function contact_us(Request $request)
    {
        //dd($request->all());

        $arrRegionsCountry = RegionsCountryModel::with(['regions'])->where('name',$request->country_name)->first();

        $contactUsModel               = new ContactUsModel();
        $contactUsModel->name         = $request->name;
        $contactUsModel->email_id     = $request->email_id;
        $contactUsModel->contact_no   = $request->contact_no;
        $contactUsModel->company_name = $request->company_name;
        $contactUsModel->job_title    = $request->job_title;
        $contactUsModel->country      = $request->country;
        $contactUsModel->description  = $request->description;
        $contactUsModel->regions      = !empty($arrRegionsCountry->regions) ? $arrRegionsCountry->regions->name : '-';

        if($contactUsModel->save())
        {
            ## Send email to admin
            $this->emailServices->send_email_to_admin('Admin contact us email',$contactUsModel->id,'contactus');
            
            $redirectUrl = url('thank_you.php?id='.$request->report_id);
            return redirect($redirectUrl);
        }
    }

    public function search(Request $request)
    {
        if(empty($request->search_report))
        {
            return redirect()->route('home');
        }

        $this->ViewData['moduleTitle']  = $this->ModuleTitle;
        $this->ViewData['moduleAction'] = $this->ModuleTitle;
        $this->ViewData['search_report'] = $request->search_report;
        $this->ViewData['arrReports']   = DB::table('tbl_reports')
                                                ->leftjoin('tbl_category', 'tbl_reports.fk_category_id', '=', 'tbl_category.id')
                                                ->leftjoin('tbl_publisher', 'tbl_reports.fk_publisher_id', '=', 'tbl_publisher.id')
                                                ->where('tbl_reports.report_title', 'LIKE', "%".$request->search_report."%")
                                                ->where('tbl_reports.status','0')
                                                ->select('tbl_reports.*','tbl_category.id as catId','tbl_category.id as cat_id','tbl_category.name as cat_name','tbl_category.slug as cat_slug','tbl_publisher.id as publisher_id','tbl_publisher.name as publisher_name')
                                                ->get();

        
        return view($this->ModuleView.'/search_reports', $this->ViewData);
    }

    public function blogs()
    {
        $this->ViewData['moduleTitle']  = $this->siteSetting->site_name;
        $this->ViewData['moduleAction'] = $this->siteSetting->site_name;
        $this->ViewData['arrBlogs']     = BlogsModel::with(['category'])->where('status','0')->get();
        
        return view($this->ModuleView.'/blogs', $this->ViewData);
    }

    public function single_blog($slug)
    {
        $this->ViewData['moduleTitle']  = $this->siteSetting->site_name;
        $this->ViewData['moduleAction'] = $this->siteSetting->site_name;
        $this->ViewData['arrBlog']      = BlogsModel::with(['category'])->where('title',$slug)->where('status','0')->first();
        
        return view($this->ModuleView.'/single_blog', $this->ViewData);
    }
}
