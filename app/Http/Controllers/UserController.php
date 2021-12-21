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

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dados(){
        $user = User::find(Auth::user()->id);
        return view('cliente.dados',compact('user'));
    }

    public function editarDados(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if(isset($user)){
            $user->name =$request->name;
            $user->email =$request->email;
            $user->nascimento = $request->nascimento;
            $user->telefone = $request->telefone;
            $user->whatsapp = $request->whatsapp;
            $user->facebook = $request->facebook;
            $user->instagram = $request->instagram;

            if(isset($request->password)){
                if(Hash::check($request->password_old, $user->password)){
                    $user->password = Hash::make($request->password);
                } else{
                    return back()->with('mensagem', 'Senha atual não confere!')->with('type', 'danger');
                }
            }
            $user->save();

            return back()->with('mensagem', 'Dados Salvos com Sucesso!')->with('type', 'success');
        }
        return back()->with('mensagem', 'Usuário não encontrado!')->with('type', 'warning');
    }

    //AGENDAMENTOS
    public function indexAgendamento()
    {
        $view = "inicial";
        $servs = Servico::orderBy('nome')->get();
        $funcs = Func::orderBy('name')->get();
        $dataAtual = "";
        $agends = Agendamento::where('user_id',Auth::user()->id)->orderBy('data','desc')->paginate(20);
        return view('cliente.index_agendamento',compact('view','servs','funcs','dataAtual','agends'));
    }

    public function painelAgendamentos(Request $request)
    {
        if(isset($request->telefone)){
            $user = User::find(Auth::user()->id);
            $user->telefone = $request->telefone;
            $user->save();
        }
        $count = Config::count();
        if($count==0){
            return back()->with('mensagem', 'Agendamentos não disponiveis no momento!')->with('type', 'warning');
        }
        $dataAtual = $request->data;
        $dataSemana = date('Y-m-d', strtotime($dataAtual. ' + 7 days'));
        $serv = Servico::find($request->servico);
        $func = "";
        $ffs = FuncFuncao::where('funcao_id',"$serv->funcao_id")->get();
        $totalFuncs = 0;
        foreach($ffs as $ff){
            $funcionario = Func::find($ff->func_id);
            if($funcionario->ativo){
                $totalFuncs++;
            }
        }
        if($totalFuncs>=1){
            $query = Agendamento::query();
            $query->whereBetween('data',["$dataAtual", "$dataSemana"]);
            //$query->where('servico_id', "$request->servico");
            if(isset($request->func)){
                $query->where('func_id', "$request->func");
                $func = Func::find($request->func);
            }
            $agends = $query->orderBy('data')->get();
            $inicio = DB::table('configs')->select(DB::raw("abertura"))->where('abertura','>',"00:00:00")->min("abertura");
            $fim = DB::table('configs')->select(DB::raw("fechamento"))->where('fechamento','>',"00:00:00")->max("fechamento");
            $configs = Config::all();
            return view('cliente.painel_agendamentos',compact('dataAtual','serv','func','totalFuncs','agends','inicio','fim','configs'));
        } else {
            return back()->with('mensagem', 'Desculpe, não há nenhum funcionário para atende-lo nesse serviço no momento, escolha outro serviço!')->with('type', 'warning');
        }
    }

    public function novoAgendamento($data, $hora, $serv, $func = null)
    {
        $view = "novo";
        $servico = Servico::find($serv);
        if(isset($func)){
            $funcionario = Func::find($func);
            $funcs = "";
        } else{
            $funcionario = "";
            $ffs = FuncFuncao::where('funcao_id',"$servico->funcao_id")->get();
            $funcIds = array();
            foreach($ffs as $ff){
                $count = Agendamento::where('data',"$data")->where('hora',"$hora")->where('func_id',"$ff->func_id")->where('status','PENDENTE')->count();
                if($count==0){
                    $funcIds[] = $ff->func_id;
                }
            }
            if(count($funcIds)>1){
                $funcs = Func::whereIn('id', $funcIds)->get();
            } else {
                return back()->with('mensagem', 'Desculpe, não há nenhum funcionário disponível para atende-lo nesse horário, escolha outro horário!')->with('type', 'warning');
            }
        }
        
        return view('cliente.agendamento',compact('view','data','hora','servico','funcionario','funcs'));
    }

    public function cadastrarAgendamento(Request $request)
    {
        $validador = Agendamento::where('data', "$request->data")->where('hora', "$request->hora")->where('servico_id', "$request->servico")->where('func_id', "$request->func")->count();
        if($validador == 0){
            $agend = new Agendamento();
            $agend->data = $request->data;
            $agend->hora = $request->hora;
            $agend->servico_id = $request->servico;
            $agend->valor = $request->valor;
            $agend->func_id = $request->func;
            $agend->user_id = Auth::user()->id;
            if(isset($request->observacao)){
                $agend->observacao = $request->observacao;
            }
            $agend->criou = "Cliente: ".Auth::user()->name;
            $agend->status = "PENDENTE"; 
            $agend->save();
            return redirect("/agendamentos")->with('mensagem', 'Agendamento Cadastrado com Sucesso!')->with('type', 'success');
        } else {
            return redirect("/agendamentos")->with('mensagem', 'Este horário não está mais disponivel, escolha outro horário!')->with('type', 'warning');
        }
    }

    public function cancelarAgendamento($id)
    {
        $agend = Agendamento::find($id);
        if(isset($agend)){
            $agend->status = "CANCELADO";
            $agend->cancelou = "Cliente: ".Auth::user()->name;
            $agend->save();
        }
        return redirect("/agendamentos")->with('mensagem', 'Agendamento Cancelado com Sucesso!')->with('type', 'success');
    }

    public function filtroAgendamento(Request $request)
    {
        $view = "filtro";
        $servs = Servico::orderBy('nome')->get();
        $funcs = Func::orderBy('name')->get();
        $dataAtual = $request->data;
        $query = Agendamento::query();
        $query->where('user_id', Auth::user()->id);
        $query->where('data',">=", "$dataAtual");
        if(isset($request->servico)){
            $query->where('servico_id', "$request->servico");
        }
        if(isset($request->func)){
            $query->where('func_id', "$request->func");
        }
        if(isset($request->status)){
            $query->where('status', "$request->status");
        }
        $agends = $query->orderBy('data','desc')->paginate(20);
        return view('cliente.index_agendamento',compact('view','servs','funcs','dataAtual','agends'));
    }
}
