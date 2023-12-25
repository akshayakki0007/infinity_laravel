<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PublisherModel;

use DB;
class ModulesModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_modules';

    public function getPermissons($role,$slug)
    {
        $arrResult = DB::table('tbl_modules_access')->where('role',$role)->where('modules',$slug)->first();

        if(!empty($arrResult))
        {
            return $arrResult;
        }
    }
}
