<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionsModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_transaction';

    public function reports()
    {
        return $this->hasOne(ReportsModel::class,'id','report_id');
    }
}
