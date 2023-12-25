<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionsCountryModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_regions_country';

    public function regions()
    {
        return $this->hasOne(RegionsModel::class,'id','fk_region_id');
    }
}
