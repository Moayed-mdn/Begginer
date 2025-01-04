<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{

    public function salesReports(){
        return $this->belongsTo(SalesReports::class);
    }


}
