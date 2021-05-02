<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    public function AdministrativeUnit(){
        return $this->belongsTo(AdministrativeUnit::class);
    }
}
