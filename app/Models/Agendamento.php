<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;

    public function servico(){
        return $this->belongsTo('App\Models\Servico');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function func(){
        return $this->belongsTo('App\Models\Func');
    }
}
