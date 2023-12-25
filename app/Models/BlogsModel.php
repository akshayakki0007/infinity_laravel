<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogsModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_blogs';

    public function category()
    {
        return $this->hasOne(CategoryModel::class,'id','fk_category');
    }
}
