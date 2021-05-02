<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdministrativeUnit extends Model
{
    protected $fillable = [
        'name','faculties_id'
    ];
    public function quotitation(){
        return $this->hasMany(Quotitation::class);
    }

    public function faculty(){
        return $this->belongsTo(Faculty::class);
    }

    public function limiteAmount(){
        return $this->hasMany(LimiteAmount::class);
    }
}
