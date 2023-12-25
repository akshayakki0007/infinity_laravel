<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class DashbaordModel extends Model
{
    public function getPiplineStatusCount($status='')
    {
        return DB::table('tbl_enquiry')->where('pipline_status',$status)->where('status','0')->count();
    }

    public function getSalesPerCount($id='')
    {
        return DB::table('tbl_enquiry')->where('fk_sale_id',$id)->where('status','0')->count();
    }

    public function getSourceCount($id='')
    {
        return DB::table('tbl_enquiry')->where('fk_source_id',$id)->where('status','0')->count();
    }
}
