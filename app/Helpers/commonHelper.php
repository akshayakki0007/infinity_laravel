<?php
use Illuminate\Support\Facades\DB;

## Models
use App\Models\UserAccessModel;
/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

function storeImage($imageName='',$path='')
{
	!is_dir($path) && mkdir($path, 0777, true);
}

function getSingleReport($id='',$limit='20')
{
    $arrResult = DB::table('tbl_reports')
                    ->leftjoin('tbl_category', 'tbl_reports.fk_category_id', '=', 'tbl_category.id')
                    ->leftjoin('tbl_publisher', 'tbl_reports.fk_publisher_id', '=', 'tbl_publisher.id')
                    ->where('tbl_reports.id',$id)
                    ->where('tbl_reports.status','0')
                    ->orderBy('tbl_reports.id','desc')
                    ->select('tbl_reports.*','tbl_category.id as catId','tbl_category.id as cat_id','tbl_category.name as cat_name','tbl_category.slug as cat_slug','tbl_publisher.id as publisher_id','tbl_publisher.name as publisher_name')
                    ->first();

    return $arrResult;
}


function getReports($limit='20')
{
    $arrResult = DB::table('tbl_reports')
                    ->leftjoin('tbl_category', 'tbl_reports.fk_category_id', '=', 'tbl_category.id')
                    ->leftjoin('tbl_publisher', 'tbl_reports.fk_publisher_id', '=', 'tbl_publisher.id')
                    ->where('tbl_reports.status','0')
                    ->orderBy('tbl_reports.id','desc')
                    ->limit($limit)
                    ->select('tbl_reports.id as id','tbl_reports.report_title','tbl_reports.slug as report_slug','tbl_category.id as cat_id','tbl_category.name as cat_name','tbl_category.slug as cat_slug','tbl_publisher.id as publisher_id','tbl_publisher.name as publisher_name')
                    ->get();

    return $arrResult;
}

function getCategoryReportCount($categoryId='')
{
    $arrResult = DB::table('tbl_reports')
                    ->where('fk_category_id',$categoryId)
                    ->where('status','0')
                    ->count();

    return $arrResult;
}

function getCategoryReports($categoryId='',$limit='20')
{
    $arrResult = DB::table('tbl_reports')
                    ->leftjoin('tbl_category', 'tbl_reports.fk_category_id', '=', 'tbl_category.id')
                    ->where('tbl_reports.fk_category_id',$categoryId)
                    ->where('tbl_reports.status','0')
                    ->orderBy('tbl_reports.id','desc')
                    ->limit($limit)
                    ->select('tbl_reports.id as id','tbl_reports.report_title','tbl_reports.slug as report_slug','tbl_category.id as catId','tbl_category.id as cat_id','tbl_category.name as cat_name','tbl_category.slug as cat_slug')
                    ->get();

    return $arrResult;
}

function getCategory($categoryId='')
{
    $arrResult = DB::table('tbl_category')
                    ->where('name',$categoryId)
                    ->where('status','0')
                    ->first();

    return $arrResult;
}

/*---------------------------------------------------
|  function get check access
----------------------------------------------------*/
function checkAccess($module,$type)
{
    $role = Auth::user()->role;
    
    if($role == 'super_admin')
    {
        return true;
    }

    $result = DB::table('tbl_modules_access')->where('role',$role)->where('modules',$module)->first($type);
    
    if(!empty($result))
    {
        if($result->$type == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}