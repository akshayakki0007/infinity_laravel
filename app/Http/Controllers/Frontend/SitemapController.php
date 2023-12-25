<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

## Models
use App\Models\User;
use App\Models\PagesModel;
use App\Models\ReportsModel;
use App\Models\CategoryModel;
use App\Models\PublisherModel;
use App\Models\SiteSettingModel;

class SitemapController extends Controller
{
    public function __construct()
    {
        $this->ViewData = [];
        $this->JsonData = [];

        $this->ModuleTitle = 'Dashboard';
        $this->ModuleView  = 'Frontend/Sitemap';

        $this->siteSetting = SiteSettingModel::find(1);
    }

    public function index()
    {
        return response()->view($this->ModuleView.'/sitemap')->header('Content-Type', 'text/xml');
    }

    public function pages()
    {
        $this->ViewData['arrPages'] = PagesModel::where('status','0')->orderBy('id','asc')->get(['id','updated_at','slug']);

        return response()->view($this->ModuleView.'/pages_xml',$this->ViewData)->header('Content-Type', 'text/xml');
    }

    public function reports()
    {
        $this->ViewData['arrReports'] = DB::table('tbl_reports')->where('tbl_reports.status','0')->orderBy('id','desc')->first('updated_at');
        $reportCount = DB::table('tbl_reports')->where('tbl_reports.status','0')->count();

        if($reportCount >= 1000)
        {
            $this->ViewData['report_pages'] = ($reportCount / 1000);
            return response()->view($this->ModuleView.'/reports_page',$this->ViewData)->header('Content-Type', 'text/xml');
        }
        else
        {
            $this->ViewData['arrReports'] = DB::table('tbl_reports')->where('status','0')->orderBy('id','desc')->limit('1000')->get(['id','slug']);
            
            return response()->view($this->ModuleView.'/report_xml',$this->ViewData)->header('Content-Type', 'text/xml');

        }
    }

    public function report_page()
    {
        $get_count = \Request::segment(1);
        $arrXmlUrl  = explode(".",$get_count);
        if(count($arrXmlUrl) > 0)
        {
            $arrSegment = explode("-",$arrXmlUrl[0]);
            
            if(count($arrSegment) > 0)
            {
                //$page = ($arrSegment[2] - 1) * 1000;
                $page = ($arrSegment[2]);
                dump($page);
                
                $this->ViewData['arrReports'] = DB::table('tbl_reports')->where('status','0')->orderBy('id','desc')->offset(1000)->limit($page)->get(['id','slug']);

                //$all_data = $this->_home->SelectAllRecords("select * from report where status='1' ORDER BY id DESC LIMIT $page,1000");

                dd($this->ViewData['arrReports']);

                $this->ViewData['report_pages'] = ($reportCount / 1000);
                $this->ViewData['report_pages'] = $reportCount;

                return response()->view($this->ModuleView.'/report_xml',$this->ViewData)->header('Content-Type', 'text/xml');
            }
        }


    }
}
