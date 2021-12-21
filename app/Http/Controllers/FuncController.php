<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Config;
use App\Models\Func;
use App\Models\FuncFuncao;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FuncController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:func');
    }
    
    public function index(){
        return view('funcionario.home_func');
    }

    public function dados(){
        $func = Func::find(Auth::user()->id);
        return view('funcionario.dados',compact('func'));
    }

    public function editarDados(Request $request)
    {
        $func = Func::find(Auth::user()->id);
        if(isset($func)){
            if(isset($request->password)){
                if(Hash::check($request->password_old, $func->password)){
                    $func->password = Hash::make($request->password);
                } else{
                    return back()->with('mensagem', 'Senha atual não confere!')->with('type', 'danger');
                }
            }
            $func->save();
            return back()->with('mensagem', 'Dados Salvos com Sucesso!')->with('type', 'success');
        }
        return back()->with('mensagem', 'Usuário não encontrado!')->with('type', 'warning');
    }

    //AGENDAMENTOS
    public function painelAgendamentos(Request $request)
    {
        $count = Config::count();
        if($count==0){
            return back()->with('mensagem', 'Agendamentos não disponiveis no momento!')->with('type', 'warning');
        }
        $dataAtual = date("Y-m-d");
        $dataSemana = date('Y-m-d', strtotime($dataAtual. ' + 7 days'));
        $ffs = FuncFuncao::where('func_id',Auth::user()->id)->get();
        $funcaoIds = array();
        foreach($ffs as $ff){
            $funcaoIds[] = $ff->funcao_id;
        }
        $servs = Servico::whereIn('funcao_id', $funcaoIds)->orderBy('nome')->get();
        $clientes = User::orderBy('name')->get();
        $query = Agendamento::query();
        $query->whereBetween('data',["$dataAtual", "$dataSemana"]);
        $query->where('func_id',Auth::user()->id);
        $agends = $query->orderBy('data')->get();
        $inicio = DB::table('configs')->select(DB::raw("abertura"))->where('abertura','>',"00:00:00")->min("abertura");
        $fim = DB::table('configs')->select(DB::raw("fechamento"))->where('fechamento','>',"00:00:00")->max("fechamento");
        $configs = Config::all();
        return view('funcionario.painel_agendamentos',compact('dataAtual','servs','clientes','agends','inicio','fim','configs'));
    }

    public function atendidoAgendamento($id)
    {
        $agend = Agendamento::find($id);

        if(isset($agend)){
            $agend->status = "ATENDIDO";
            $agend->atendeu = "Func: ".Auth::user()->name;
            $agend->save();
        }

        return back()->with('mensagem', 'Agendamento Atendido com Sucesso!')->with('type', 'success');
    }

    public function cancelarAgendamento($id)
    {
        $agend = Agendamento::find($id);

        if(isset($agend)){
            $agend->status = "CANCELADO";
            $agend->cancelou = "Func: ".Auth::user()->name;
            $agend->save();
        }

        return back()->with('mensagem', 'Agendamento Cancelado com Sucesso!')->with('type', 'success');
    }

    public function filtroAgendamento(Request $request)
    {
        $dataAtual = $request->data;
        $dataSemana = date('Y-m-d', strtotime($dataAtual. ' + 7 days'));
        $ffs = FuncFuncao::where('func_id',Auth::user()->id)->get();
        $funcaoIds = array();
        foreach($ffs as $ff){
            $funcaoIds[] = $ff->funcao_id;
        }
        $servs = Servico::whereIn('funcao_id', $funcaoIds)->orderBy('nome')->get();
        $clientes = User::orderBy('name')->get();
        $query = Agendamento::query();
        $query->whereBetween('data',["$dataAtual", "$dataSemana"]);
        $query->where('func_id',Auth::user()->id);
        if(isset($request->servico)){
            $query->where('servico_id', "$request->servico");
        }
        if(isset($request->cliente)){
            $client = User::where('email',"$request->cliente")->first();
            $query->where('user_id', "$client->id");
        }
        if(isset($request->status)){
            $query->where('status', "$request->status");
        }
        $agends = $query->orderBy('data')->get();
        $inicio = DB::table('configs')->select(DB::raw("abertura"))->where('abertura','>',"00:00:00")->min("abertura");
        $fim = DB::table('configs')->select(DB::raw("fechamento"))->where('fechamento','>',"00:00:00")->max("fechamento");
        $configs = Config::all();
        return view('funcionario.painel_agendamentos',compact('dataAtual','servs','clientes','agends','inicio','fim','configs'));
    }
}
