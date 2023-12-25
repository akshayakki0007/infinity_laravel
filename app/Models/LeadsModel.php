<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PublisherModel;

class LeadsModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_enquiry';

    public function reports()
    {
        return $this->hasOne(ReportsModel::class,'id','report_id');
    }

    public function sales()
    {
        return $this->hasOne(User::class,'id','fk_sale_id');
    }

    public function source()
    {
        return $this->hasOne(SourceModel::class,'id','fk_source_id');
    }

    public function getPublisher($id)
    {
        return PublisherModel::find($id);
    }
}
