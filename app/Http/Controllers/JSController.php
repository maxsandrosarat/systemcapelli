<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Func;
use App\Models\FuncFuncao;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Http\Request;

class JSController extends Controller
{
    public function funcs(Request $request)
    {
        $servico = Servico::find($request->id);
        $ffs = FuncFuncao::where('funcao_id',"$servico->funcao_id")->get();
        $funcIds = array();
        foreach($ffs as $ff){
            $funcIds[] = $ff->func_id;
        }
        return Func::whereIn('id', $funcIds)->where('ativo',true)->get();
    }

    public function funcAgends(Request $request){
        $count = Agendamento::where('data',"$request->dia")->where('hora',"$request->hora")->where('func_id',"$request->id")->where('status','PENDENTE')->count();
        return $count;
    }

    public function clienteAgends(Request $request){
        $cliente = User::where('email',"$request->id")->first();
        $count = Agendamento::where('data',"$request->dia")->where('hora',"$request->hora")->where('user_id',"$cliente->id")->where('status','PENDENTE')->count();
        return $count;
    }
}
