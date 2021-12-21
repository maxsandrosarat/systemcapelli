@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
@php
	$page = "Admin Clientes";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/admin/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            <h5 class="card-title">Lista de Clientes</h5>
            @if(session('mensagem'))
                <div class="alert @if(session('type')=="warning") alert-warning @else alert-success @endif alert-dismissible fade show" role="alert">
                    {{session('mensagem')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(count($errors) > 0)
                <div class="alert alert-danger">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    Erro(s)
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <a type="button" class="float-button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Novo Usuário">
                <i class="material-icons blue md-60">person_add</i>
            </a>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Cadastro de Cliente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body">
                                <form method="POST" action="/admin/usuarios" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-12 form-floating">
                                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nome">
                                        <label for="name">Nome</label>
                                    </div>
                                    <div class="col-12 form-floating">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="E-Mail">
                                        <label for="email">E-Mail</label>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-12 form-floating">
                                        <input id="senhaForca" onkeyup="validarSenhaForca()" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Senha">
                                        <label for="password">Senha</label>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <p class="fs-6 fst-italic">(Mínimo de 8 caracteres)</p>
                                    </div>
                                    <div class="col-12">
                                        <div name="erroSenhaForca" id="erroSenhaForca"></div>
                                        <label for="erroSenhaForca">Força Senha</label>
                                    </div>
                                    <div class="col-12 form-floating">
                                        <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmação Senha">
                                        <label for="password-confirm">Confirmação Senha</label>
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-12 form-floating">
                                        <input id="nascimento" type="date" class="form-control" name="nascimento">
                                        <label for="nascimento">Nascimento</label>
                                    </div>
                                    <div class="col-12 form-floating">
                                        <input name="telefone" class="form-control" id="telefone0" size="60" onblur="formataNumeroTelefone(0)" placeholder="Número com DDD, exemplo: 67991234567" required>
                                        <label for="telefone">Telefone</label>
                                    </div>
                                    <div class="col-12 form-floating">
                                        <input name="whatsapp" class="form-control" id="whatsapp0" size="60" onblur="formataNumeroWhatsapp(0)" placeholder="Número com DDD, exemplo: 67991234567">
                                        <label for="whatsapp">WhatsApp</label>
                                    </div>
                                    <div class="col-12 form-floating">
                                        <input type="text" name="facebook" class="form-control" id="facebook" placeholder="Nome do usuário">
                                        <label for="facebook">Facebook</label>
                                    </div>
                                    <div class="col-12 form-floating">
                                        <input type="text" name="instagram" class="form-control" id="instagram" placeholder="Nome do usuário">
                                        <label for="instagram">Instagram</label>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-outline-primary">Cadastrar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(count($users)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem usuários cadastrados! Faça novo cadastro no botão    <a type="button" href="#"><i class="material-icons blue">add_circle</i></a>   no canto inferior direito.
                        @endif
                        @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/admin/usuarios" class="btn btn-success">Voltar</a>
                        @endif
                    </div>
            @else
            <div class="card border">
                <h5>Filtros: </h5>
                <form class="row gy-2 gx-3 align-items-center" method="GET" action="/admin/usuarios/filtro">
                    @csrf 
                    <div class="col-auto form-floating">
                        <input class="form-control mr-sm-2" type="text" placeholder="Nome" name="nome">
                        <label for="nome">Nome</label>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                    </div>
                </form>
            </div>
            <br>
            <h5>Exibindo {{$users->count()}} de {{$users->total()}} de Cliente(s) ({{$users->firstItem()}} a {{$users->lastItem()}})</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Login</th>
                        <th>Telefone</th>
                        <th>Ativo</th>
                        <th>Última Atualização</th>
                        <th>Outros</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->telefone}}</td>
                        <td>
                            @if($user->ativo==1)
                                <b><i class="material-icons green">check_circle</i></b>
                            @else
                                <b><i class="material-icons red">highlight_off</i></b>
                            @endif
                        </td>
                        <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <button type="button" class="badge bg-light" data-bs-toggle="modal" data-bs-target="#userPlus{{$user->id}}">
                                <i class="material-icons blue md-24">note_add</i>
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="userPlus{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Cliente: {{$user->name}}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="list-group">
                                                <li class="list-group-item">Nascimento: @if($user->nascimento!="") {{date("d/m/Y", strtotime($user->nascimento))}} @endif</li>
                                                <li class="list-group-item">WhatsApp: {{$user->whatsapp}}</li>
                                                <li class="list-group-item">Facebook: {{$user->facebook}}</li>
                                                <li class="list-group-item">Instagram: {{$user->instagram}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="badge bg-warning" data-bs-toggle="modal" data-bs-target="#userEdit{{$user->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                <i class="material-icons md-18">edit</i>
                            </button>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="userEdit{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Edição de Cliente</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card-body">
                                            <form method="POST" action="/admin/usuarios/editar/{{$user->id}}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="col-12 form-floating">
                                                    <input id="name" type="text" class="form-control" name="name" value="{{$user->name}}" required autocomplete="name" autofocus>
                                                    <label for="name">Nome</label>
                                                </div>  
                                                <div class="col-12 form-floating">
                                                    <input id="email" type="email" class="form-control" name="email" value="{{$user->email}}" required autocomplete="email">
                                                    <label for="email">E-Mail</label>
                                                </div>  
                                                <div class="col-12 form-floating">
                                                    <input id="password" type="password" class="form-control" name="password" autocomplete="new-password">
                                                    <label for="password">Senha</label>
                                                </div>
                                                <div class="col-12 form-floating">
                                                    <input id="nascimento" type="date" class="form-control" name="nascimento" value="{{$user->nascimento}}">
                                                    <label for="nascimento">Nascimento</label>
                                                </div>
                                                <div class="col-12 form-floating">
                                                    <input name="telefone" class="form-control" id="telefone{{$user->id}}" size="60" onblur="formataNumeroTelefone({{$user->id}})" value="{{$user->telefone}}">
                                                    <label for="telefone">Telefone</label>
                                                </div>
                                                <div class="col-12 form-floating">
                                                    <input name="whatsapp" class="form-control" id="whatsapp{{$user->id}}" size="60" onblur="formataNumeroWhatsapp({{$user->id}})" value="{{$user->whatsapp}}">
                                                    <label for="whatsapp">WhatsApp</label>
                                                </div>
                                                <div class="col-12 form-floating">
                                                    <input type="text" name="facebook" class="form-control" id="facebook" value="{{$user->facebook}}">
                                                    <label for="facebook">Facebook</label>
                                                </div>
                                                <div class="col-12 form-floating">
                                                    <input type="text" name="instagram" class="form-control" id="instagram" value="{{$user->instagram}}">
                                                    <label for="instagram">Instagram</label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-outline-primary">Salvar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            @if($user->ativo==1)
                                <a href="/admin/usuarios/ativar/{{$user->id}}" class="badge bg-secondary" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-18 red">disabled_by_default</i></a>
                            @else
                                <a href="/admin/usuarios/ativar/{{$user->id}}" class="badge bg-secondary" data-toggle="tooltip" data-placement="right" title="Ativar"><i class="material-icons md-18 green">check_box</i></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                {{$users->links() }}
            </div>
            </div>
            @endif
        </div>
    </div>
@endsection
