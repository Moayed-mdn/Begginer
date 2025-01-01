<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReports extends Model
{

    public function saleReport(){
        return $this->hasMany(SalesReport::class);
    }
}
