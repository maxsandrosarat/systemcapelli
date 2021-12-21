<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Agendamento;
use App\Models\Banner;
use App\Models\Config;
use App\Models\Func;
use App\Models\Funcao;
use App\Models\FuncFuncao;
use App\Models\Promocao;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function index(){
        return view('admin.home_admin');
    }

    //CADASTROS
    public function cadastros(){
        return view('admin.cadastros.home_cadastros');
    }
    
    //FUNCIONÁRIO
    public function indexFuncionarios()
    {
        $view = "inicial";
        $funcs = Func::paginate(20);
        $funcoes = Funcao::orderBy('nome')->get();
        return view('admin.cadastros.funcionarios',compact('view','funcs','funcoes'));
    }

    public function cadastrarFuncionario(Request $request)
    {
        $request->validate([
            'email' => 'unique:funcs',
            'password' => 'min:8',
            'password_confirmation' => 'required|same:password',
        ], $mensagens =[
            'email.unique' => 'Já existe um usuário com esse login!',
            'password.min' => 'A senha deve conter no mínimo 8 caracteres!',
            'password_confirmation.same' => 'As senhas não conferem!',
        ]);
        $func = new Func();
        $func->name = $request->name;
        $func->email = $request->email;
        $func->password = Hash::make($request->password);
        $func->save();
        $funcoes = $request->funcoes;
        if(isset($funcoes)){
            foreach($funcoes as $funcao_id){
                $ff = new FuncFuncao();
                $ff->func_id = $func->id;
                $ff->funcao_id = $funcao_id;
                $ff->save();
            }
        }
        return back()->with('mensagem', 'Funcionário Cadastrado com Sucesso!')->with('type', 'success');
    }

    public function editarFuncionario(Request $request, $id)
    {
        $func = Func::find($id);
        if(isset($func)){
            $func->name = $request->name;
            $func->email = $request->email;
            if(isset($request->password)){
                $func->password = Hash::make($request->password);
            }
            $func->save();
            if(isset($request->funcoes)){
                $funcoes = $request->funcoes;
                FuncFuncao::where('func_id',"$id")->delete();
                foreach ($funcoes as $funcao_id) {
                    $ffs = FuncFuncao::where('func_id',"$id")->where('funcao_id',"$funcao_id")->get();
                    if($ffs->count()==0){
                        $ff = new FuncFuncao();
                        $ff->func_id = $id;
                        $ff->funcao_id = $funcao_id;
                        $ff->save();
                    }
                }
            }
        }
        return back()->with('mensagem', 'Funcionário Alterado com Sucesso!')->with('type', 'success');
    }

    public function filtroFuncionario(Request $request)
    {
        $query = Func::query();
        if (isset($request->nome)) {
            $query->where('name', 'LIKE', '%' . $request->nome . '%');
        }
        if (isset($request->funcao)) {
            $ffs = FuncFuncao::where('funcao_id',"$request->funcao")->get();
            $funcIds = array();
            foreach($ffs as $ff){
                $funcIds[] = $ff->func_id;
            }
            $query->whereIn('id', $funcIds);
        }
        $funcs = $query->orderBy('name')->paginate(20);
        $view = "filtro";
        $funcoes = Funcao::orderBy('nome')->get();
        return view('admin.cadastros.funcionarios',compact('view','funcs','funcoes'));
    }

    public function desvincularFuncFuncao($func_id, $funcao_id)
    {
        FuncFuncao::where('func_id',"$func_id")->where('funcao_id',"$funcao_id")->delete();
        return back();
    }

    public function ativarFuncionario($id)
    {
        $func = Func::find($id);
        if(isset($func)){
            if($func->ativo==1){
                $func->ativo = false;
                $func->save();
                return back()->with('mensagem', 'Funcionário Inativado com Sucesso!')->with('type', 'success');
            } else {
                $func->ativo = true;
                $func->save();
                return back()->with('mensagem', 'Funcionário Ativado com Sucesso!')->with('type', 'success');
            }
        }
        return back();
    }

    //SERVIÇO
    public function indexServicos()
    {
        $servs = Servico::orderBy('nome')->paginate(20);
        $funcoes = Funcao::orderBy('nome')->get();
        return view('admin.cadastros.servicos',compact('servs','funcoes'));
    }

    public function cadastrarServico(Request $request)
    {
        $serv = new Servico();
        $serv->nome = $request->nome;
        $serv->preco = $request->preco;
        $serv->tempo = $request->tempo;
        $serv->funcao_id = $request->funcao;
        $serv->save();
        return back()->with('mensagem', 'Serviço Cadastrado com Sucesso!')->with('type', 'success');
    }

    public function editarServico(Request $request, $id)
    {
        $serv = Servico::find($id);
        if(isset($serv)){
            $serv->nome = $request->nome;
            $serv->preco = $request->preco;
            $serv->tempo = $request->tempo;
            $serv->funcao_id = $request->funcao;
            $serv->save();
        }
        return back()->with('mensagem', 'Serviço Alterado com Sucesso!')->with('type', 'success');
    }

    public function ativarServico($id)
    {
        $serv = Servico::find($id);
        if(isset($serv)){
            if($serv->ativo==1){
                $serv->ativo = false;
                $serv->save();
                return back()->with('mensagem', 'Serviço Inativado com Sucesso!')->with('type', 'success');
            } else {
                $serv->ativo = true;
                $serv->save();
                return back()->with('mensagem', 'Serviço Ativado com Sucesso!')->with('type', 'success');
            }
        }
        return back();
    }

    public function promocaoServico(Request $request)
    {
        $serv = Servico::find($request->servico);
        $serv->preco = $request->precoNovo;
        $serv->promocao = true;
        $serv->preco_antigo = $request->precoAntigo;
        $serv->inicio = $request->dataInicio.' '.$request->horaInicio;
        $serv->fim = $request->dataFim.' '.$request->horaFim;
        $serv->ressalva = $request->ressalva;
        $serv->save();
        $promo = new Promocao();
        $promo->servico_id = $request->servico;
        $promo->preco_antigo = $request->precoAntigo;
        $promo->preco_novo = $request->precoNovo;
        $promo->inicio = $request->dataInicio.' '.$request->horaInicio;
        $promo->fim = $request->dataFim.' '.$request->horaFim;
        $promo->criou = "Admin: ".Auth::user()->name;
        $promo->ressalva = $request->ressalva;
        $promo->save();
        return back()->with('mensagem', 'Promoção Cadastrada com Sucesso!')->with('type', 'success');
    }

    public function promocaoServicoFinalizar($id)
    {
        $serv = Servico::find($id);
        if(isset($serv)){
            if($serv->promocao==1){
                $serv->promocao = false;
                $serv->preco = $serv->preco_antigo;
                $serv->preco_antigo = NULL;
                $serv->inicio = NULL;
                $serv->fim = NULL;
                $serv->ressalva = NULL;
                $serv->save();
                return back()->with('mensagem', 'Promoção Finalizada com Sucesso!')->with('type', 'success');
            }
        }
        return back();
    }


    //USUÁRIOS
    public function indexUsuarios()
    {
        $view = "inicial";
        $users = User::orderBy('name')->paginate(10);
        $funcoes = Funcao::orderBy('nome')->get();
        return view('admin.cadastros.usuarios', compact('view','funcoes','users'));
    }

    public function cadastrarUsuario(Request $request)
    {
        $request->validate([
            'email' => 'unique:users',
            'password' => 'min:8',
            'password_confirmation' => 'required|same:password',
        ], $mensagens =[
            'email.unique' => 'Já existe um usuário com esse login!',
            'password.min' => 'A senha deve conter no mínimo 8 caracteres!',
            'password_confirmation.same' => 'As senhas não conferem!',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->nascimento = $request->nascimento;
        $user->telefone = $request->telefone;
        $user->whatsapp = $request->whatsapp;
        $user->facebook = $request->facebook;
        $user->instagram = $request->instagram;
        $user->save();
        return back()->with('mensagem', 'Usuário Cadastrado com Sucesso!')->with('type', 'success');
    }

    public function editarUsuario(Request $request, $id)
    {
        $user = User::find($id);
        if(isset($user)){
            $user->name =$request->name;
            $user->email =$request->email;
            if(isset($request->password)){
                $user->password = Hash::make($request->password);
            }
            $user->nascimento = $request->nascimento;
            $user->telefone = $request->telefone;
            $user->whatsapp = $request->whatsapp;
            $user->facebook = $request->facebook;
            $user->instagram = $request->instagram;
            $user->save();
            return back()->with('mensagem', 'Usuário Alterado com Sucesso!')->with('type', 'success');
        }
        return back();
    }

    public function filtroUsuario(Request $request)
    {
        $nome = $request->nome;
        $turma = $request->turma;
        if(isset($nome)){
            if(isset($turma)){
                $users = User::where('ativo',true)->where('name','like',"%$nome%")->where('turma_id',"$turma")->orderBy('name')->paginate(50);
            } else {
                $users = User::where('ativo',true)->where('name','like',"%$nome%")->orderBy('name')->paginate(50);
            }
        } else {
            if(isset($turma)){
                $users = User::where('ativo',true)->where('turma_id',"$turma")->orderBy('name')->paginate(50);
            } else {
                return redirect('/usuarios');
            }
        }
        $view = "filtro";
        return view('admin.cadastros.usuarios', compact('view','users'));
    }

    public function ativarUsuario($id)
    {
        $user = User::find($id);
        if(isset($user)){
            if($user->ativo==1){
                $user->ativo = false;
                $user->save();
                return back()->with('mensagem', 'Usuário Inativado com Sucesso!')->with('type', 'success');
            } else {
                $user->ativo = true;
                $user->save();
                return back()->with('mensagem', 'Usuário Ativado com Sucesso!')->with('type', 'success');
            }
        }
        return back();
    }

    //ADMINS
    public function indexAdmins()
    {
        $admins = Admin::orderBy('name')->paginate(10);
        return view('admin.cadastros.admins', compact('admins'));
    }

    public function novoAdmin(Request $request)
    {
        $request->validate([
            'email' => 'unique:users',
            'password' => 'min:8',
            'password_confirmation' => 'required|same:password',
        ], $mensagens =[
            'email.unique' => 'Já existe um usuário com esse login!',
            'password.min' => 'A senha deve conter no mínimo 8 caracteres!',
            'password_confirmation.same' => 'As senhas não conferem!',
        ]);
        
        $user = new Admin();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return back()->with('mensagem', 'Admin Cadastrado com Sucesso!');
    }

    public function editarAdmin(Request $request, $id)
    {
        $admin = Admin::find($id);
        if(isset($admin)){
            $admin->name =$request->name;
            $admin->email =$request->email;
            if(isset($request->password)){
                $admin->password = Hash::make($request->password);
            }
            $admin->save();
        }
        return back()->with('mensagem', 'Admin Alterado com Sucesso!');
    }

    public function ativarAdmin($id)
    {
        $admin = Admin::find($id);
        if(isset($admin)){
            if($admin->ativo==1){
                $admin->ativo = false;
                $admin->save();
                return back()->with('mensagem', 'Admin Inativado com Sucesso!')->with('type', 'success');
            } else {
                $admin->ativo = true;
                $admin->save();
                return back()->with('mensagem', 'Admin Ativado com Sucesso!')->with('type', 'success');
            }
        }
        return back();
    }

    //FUNÇÃO
    public function indexFuncoes()
    {
        $funcoes = Funcao::orderBy('nome')->get();
        return view('admin.cadastros.funcoes',compact('funcoes'));
    }

    public function cadastrarFuncao(Request $request)
    {
        $funcao = new Funcao();  
        $funcao->nome = $request->nome;
        $funcao->save();
        return back()->with('mensagem', 'Função Cadastrada com Sucesso!')->with('type', 'success');
    }

    public function editarFuncao(Request $request, $id)
    {
        $funcao = Funcao::find($id);
        if(isset($funcao)){
            $funcao->nome = $request->nome;           
            $funcao->save();
        }
        return back()->with('mensagem', 'Função Alterada com Sucesso!')->with('type', 'success');
    }

    //AGENDAMENTOS
    public function agendamentos(){
        return view('admin.agendamentos.home_agendamentos');
    }

    public function indexAgendamentos()
    {
        $view = "inicial";
        $filtrados = 0;
        $count = Config::count();
        if($count==0){
            $diasemana = array('Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado');
            for ($i=0; $i < 7; $i++) { 
                $cfg = new Config();
                $cfg->diaSemana = $diasemana[$i];
                $cfg->save();
            }
        }
        $dataAtual = date("Y-m-d");
        $dataSemana = date('Y-m-d', strtotime($dataAtual. ' + 7 days'));
        $clientes = User::orderBy('name')->get();
        $servs = Servico::orderBy('nome')->get();
        $funcs = Func::orderBy('name')->get();
        $totalFuncs = Func::where('ativo',true)->count();
        $agends = Agendamento::whereBetween('data',["$dataAtual", "$dataSemana"])->orderBy('data')->get();
        $inicio = DB::table('configs')->select(DB::raw("abertura"))->where('abertura','>',"00:00:00")->min("abertura");
        $fim = DB::table('configs')->select(DB::raw("fechamento"))->where('fechamento','>',"00:00:00")->max("fechamento");
        $configs = Config::all();
        return view('admin.agendamentos.agendamentos',compact('view','filtrados','dataAtual','clientes','servs','funcs','totalFuncs','agends','inicio','fim','configs'));
    }

    public function novoAgendamento($data, $hora)
    {
        $dataAtual = date("Y-m-d H:i");
        $dataAgend = $data.' '.$hora;
        if($dataAgend<$dataAtual){
            return back()->with('mensagem', 'Data & Hora não estão mais disponiveis para Agendamento!')->with('type', 'danger');
        }
        $view = "novo";
        $clientes = User::orderBy('name')->get();
        $servs = Servico::orderBy('nome')->get();
        return view('admin.agendamentos.agendamento',compact('view','data','hora','clientes','servs'));
    }

    public function cadastrarAgendamento(Request $request)
    {
        if(isset($request->id)){
            $agend = Agendamento::find($request->id);
            if(isset($agend)){
                if(isset($request->data)){
                    $agend->data = $request->data;
                }
                if(isset($request->hora)){
                    $agend->hora = $request->hora;
                }
                if(isset($request->servico)){
                    $agend->servico_id = $request->servico;
                }
                if(isset($request->valor)){
                    $agend->valor = $request->valor;
                }
                if(isset($request->func)){
                    $agend->func_id = $request->func;
                }
                if(isset($request->cliente)){
                    $client = User::where('email',"$request->cliente")->first();
                    $agend->user_id = $client->id;
                }
                if(isset($request->observacao)){
                    $agend->observacao = $request->observacao;
                }
                $agend->editou = "Admin: ".Auth::user()->name;
                $agend->status = "PENDENTE"; 
                $agend->save();
                return redirect("/admin/agendamentos")->with('mensagem', 'Agendamento Alterado com Sucesso!')->with('type', 'success');
            } else {
                return redirect("/admin/agendamentos")->with('mensagem', 'Agendamento Não Encontrado!')->with('type', 'danger');
            }
        } else {
            $dataAtual = date("Y-m-d H:i");
            $dataAgend = $request->data.' '.$request->hora;
            if($dataAgend<$dataAtual){
                return redirect("/admin/agendamentos")->with('mensagem', 'Data & Hora não estão mais disponiveis para Agendamento!')->with('type', 'danger');
            }
            $agend = new Agendamento();
            if(isset($request->data)){
                $agend->data = $request->data;
            }
            if(isset($request->hora)){
                $agend->hora = $request->hora;
            }
            if(isset($request->servico)){
                $agend->servico_id = $request->servico;
            }
            if(isset($request->valor)){
                $agend->valor = $request->valor;
            }
            if(isset($request->func)){
                $agend->func_id = $request->func;
            }
            if(isset($request->cliente)){
                $client = User::where('email',"$request->cliente")->first();
                $agend->user_id = $client->id;
            }
            if(isset($request->observacao)){
                $agend->observacao = $request->observacao;
            }
            $agend->criou = "Admin: ".Auth::user()->name;
            $agend->status = "PENDENTE"; 
            $agend->save();
            return redirect("/admin/agendamentos")->with('mensagem', 'Agendamento Cadastrado com Sucesso!')->with('type', 'success');
        }
    }

    public function atendidoAgendamento($id)
    {
        $agend = Agendamento::find($id);

        if(isset($agend)){
            $agend->status = "ATENDIDO";
            $agend->atendeu = "Admin: ".Auth::user()->name;
            $agend->save();
        }

        return back()->with('mensagem', 'Agendamento Atendido com Sucesso!')->with('type', 'success');
    }

    public function cancelarAgendamento($id)
    {
        $agend = Agendamento::find($id);

        if(isset($agend)){
            $agend->status = "CANCELADO";
            $agend->cancelou = "Admin: ".Auth::user()->name;
            $agend->save();
        }

        return back()->with('mensagem', 'Agendamento Cancelado com Sucesso!')->with('type', 'success');
    }

    public function editarAgendamento($id)
    {
        $view = "editar";
        $agend = Agendamento::find($id);
        $clientes = User::orderBy('name')->get();
        $servs = Servico::orderBy('nome')->get();
        return view('admin.agendamentos.agendamento',compact('view','agend','clientes','servs'));
    }

    public function filtroAgendamento(Request $request)
    {
        $view = "filtro";
        $filtrados = 0;
        $dataAtual = $request->data;
        $dataSemana = date('Y-m-d', strtotime($dataAtual. ' + 7 days'));
        $clientes = User::orderBy('name')->get();
        $servs = Servico::orderBy('nome')->get();
        $funcs = Func::orderBy('name')->get();
        $totalFuncs = Func::where('ativo',true)->count();
        $query = Agendamento::query();
        $query->whereBetween('data',["$dataAtual", "$dataSemana"]);
        if(isset($request->servico)){
            $filtrados++;
            $query->where('servico_id', "$request->servico");
        }
        if(isset($request->func)){
            $filtrados++;
            $query->where('func_id', "$request->func");
        }
        if(isset($request->cliente)){
            $filtrados++;
            $client = User::where('email',"$request->cliente")->first();
            $query->where('user_id', "$client->id");
        }
        if(isset($request->status)){
            $filtrados++;
            $query->where('status', "$request->status");
        }
        $agends = $query->orderBy('data')->get();
        $inicio = DB::table('configs')->select(DB::raw("abertura"))->where('abertura','>',"00:00:00")->min("abertura");
        $fim = DB::table('configs')->select(DB::raw("fechamento"))->where('fechamento','>',"00:00:00")->max("fechamento");
        $configs = Config::all();
        return view('admin.agendamentos.agendamentos',compact('view','filtrados','dataAtual','clientes','servs','funcs','totalFuncs','agends','inicio','fim','configs'));
    }

    public function config(Request $request)
    {
        $diasemana = array('Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado');
        for ($i=0; $i < 7; $i++) { 
            $config = Config::where('diaSemana',"$diasemana[$i]")->first();
            if(isset($config)){
                $config->abertura = $request->input('abertura'."$diasemana[$i]");
                $config->intervaloInicio = $request->input('intervaloInicio'."$diasemana[$i]");
                $config->intervaloFim = $request->input('intervaloFim'."$diasemana[$i]");
                $config->fechamento = $request->input('fechamento'."$diasemana[$i]");
                $config->save();
            }
        }
        return back(); 
    }

    public function todosAgendamentos()
    {
        $view = "inicial";
        $servs = Servico::orderBy('nome')->get();
        $funcs = Func::orderBy('name')->get();
        $clientes = User::orderBy('name')->get();
        $dataAtual = "";
        $agends = Agendamento::orderBy('data','desc')->paginate(20);
        return view('admin.agendamentos.todos_agendamentos',compact('view','servs','funcs','clientes','dataAtual','agends'));
    }

    public function filtroTodosAgendamento(Request $request)
    {
        $view = "filtro";
        $servs = Servico::orderBy('nome')->get();
        $funcs = Func::orderBy('name')->get();
        $clientes = User::orderBy('name')->get();
        $dataAtual = $request->data;
        $query = Agendamento::query();
        $query->where('data',">=", "$dataAtual");
        if(isset($request->servico)){
            $query->where('servico_id', "$request->servico");
        }
        if(isset($request->func)){
            $query->where('func_id', "$request->func");
        }
        if(isset($request->cliente)){
            $client = User::where('email',"$request->cliente")->first();
            $query->where('user_id', "$client->id");
        }
        if(isset($request->status)){
            $query->where('status', "$request->status");
        }
        $agends = $query->orderBy('data','desc')->paginate(20);
        return view('admin.agendamentos.todos_agendamentos',compact('view','servs','funcs','clientes','dataAtual','agends'));
    }

    //BANNER
    public function indexBanners()
    {
        $banners = Banner::orderBy('ordem')->get();
        return view('admin.cadastros.banners',compact('banners'));
    }

    public function novoBanner(Request $request)
    {
        $count = Banner::where('ativo',true)->count();
        if($count>0){
            $banners = Banner::where('ativo',true)->where('ordem','>=',"$request->ordem")->orderBy('ordem')->get();
            foreach ($banners as $bnr){
                $bn = Banner::find($bnr->id);
                $bn->ordem += 1;
                $bn->save(); 
            }
        }
        $banner = new Banner();
        if($request->file('foto')!=""){
            $path = $request->file('foto')->store('banners','public');
            $banner->foto = $path;
        }
        if(isset($request->titulo)){
            $banner->titulo = $request->titulo;
        }
        if(isset($request->descricao)){
            $banner->descricao = $request->descricao;
        }
        if(isset($request->ordem)){
            $banner->ordem = $request->ordem;
        }
        $banner->save();
        return back()->with('mensagem', 'Banner cadastrado com Sucesso!')->with('type', 'success');
    }

    public function editarBanner(Request $request, $id)
    {
        $banner = Banner::find($id);
        if($banner->ordem>0 && $banner->ordem!=$request->ordem){
            $count = Banner::where('ativo',true)->count();
            if($count>0){
                $banners = Banner::where('ativo',true)->where('ordem','>=',"$request->ordem")->orderBy('ordem')->get();
                $ordem = $request->ordem;
                foreach ($banners as $bnr){
                    if($bnr->id==$id){
                    } else {
                        $ordem++;
                        $bn = Banner::find($bnr->id);
                        $bn->ordem = $ordem;
                        $bn->save();
                    }
                }
            }
            $banner->ordem = $request->ordem;
        }
        if(isset($banner)){
            if($request->file('foto')!=""){
                Storage::disk('public')->delete($banner->foto);
                $path = $request->file('foto')->store('banners','public');
                $banner->foto = $path;
            }
            $banner->titulo = $request->titulo;
            $banner->descricao = $request->descricao;
            $banner->save();
        }
        return back()->with('mensagem', 'Banner alterado com Sucesso!')->with('type', 'success');
    }

    public function ativarBanner($id)
    {
        $count = Banner::where('ativo',true)->count();
        $banner = Banner::find($id);
        if(isset($banner)){
            if($banner->ativo==1){
                $banners = Banner::where('ativo',true)->orderBy('ordem')->get();
                $validate = false;
                foreach ($banners as $bnr){
                    if($bnr->ordem==$banner->ordem){
                        $validate = true;
                    }
                    if($validate){
                        $bn = Banner::find($bnr->id);
                        $bn->ordem -= 1;
                        $bn->save(); 
                    }
                }
                $banner->ativo = false;
                $banner->ordem = 0;
                $banner->save();
                return back()->with('mensagem', 'Banner inativado com Sucesso!')->with('type', 'success');
            } else {
                $banner->ativo = true;
                $banner->ordem = $count + 1;
                $banner->save();
                return back()->with('mensagem', 'Banner ativado com Sucesso!')->with('type', 'success');
            }
        }
        return back();
    }

    public function apagarBanner($id)
    {
        $banner = Banner::find($id);
        $ordem = $banner->ordem;
        if(isset($banner)){
            Storage::disk('public')->delete($banner->foto);
            $banner->delete();
        }
        $banners = Banner::where('ordem','>',"$ordem")->get();
        foreach ($banners as $banner) {
            $ban = Banner::find($banner->id);
            $ban->ordem -= 1;
            $ban->save();
        }
        return back()->with('mensagem', 'Banner excluído com Sucesso!')->with('type', 'success');
    }
}
