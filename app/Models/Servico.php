<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    public function funcao(){
        return $this->belongsTo('App\Models\Funcao');
    }

    function promocoes(){
        return $this->hasMany('App\Models\Promocao');
    }
}
