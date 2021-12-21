<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuncFuncao extends Model
{
    use HasFactory;

    public function func(){
        return $this->belongsTo('App\Models\Func');
    }

    public function funcao(){
        return $this->belongsTo('App\Models\Funcao');
    }
}
