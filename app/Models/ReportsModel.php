<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportsModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_reports';

    public function category()
    {
        return $this->hasOne(CategoryModel::class,'id','fk_category_id');
    }

    public function publisher()
    {
        return $this->hasOne(PublisherModel::class,'id','fk_publisher_id');
    }
}
