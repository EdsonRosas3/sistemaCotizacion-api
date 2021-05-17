<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\RequestDetail; 
use App\Report; 
use App\CompanyCode;

class RequestQuotitation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nameUnidadGasto','aplicantName','requestDate','amount','status','spending_units_id','administrative_unit_id'
    ];

    public function requestDetails(){
        return $this->hasMany(RequestDetail::class);
    }
    public function reports(){
        return $this->hasMany(Report::class);
    }
    public function companyCodes(){
        return $this->hasMany(CompanyCode::class);
    }

    public function spendingUnits(){
        return $this->belongsTo(SpendingUnit::class);
    }
    

}
