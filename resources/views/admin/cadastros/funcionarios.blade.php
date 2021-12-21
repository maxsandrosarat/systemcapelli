@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
@php
	$page = "Admin Funcionários";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/admin/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            <h5 class="card-title">Lista de Funcionários</h5>
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
            <a type="button" class="float-button" data-bs-toggle="modal" data-bs-target="#exampleModalCad" data-toggle="tooltip" data-placement="bottom" title="Cadastrar Novo func">
                <i class="material-icons blue md-60">add_reaction</i>
            </a>
            @if(count($funcs)==0)
                <div class="alert alert-dark" role="alert">
                    @if($view=="inicial")
                        Sem funcionários cadastrados! Faça novo cadastro no botão    <a type="button" href="#"><i class="material-icons blue">add_circle</i></a>   no canto inferior direito.
                    @endif
                    @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/admin/funcs" class="btn btn-success">Nova Busca</a>
                    @endif
                </div>
            @else
            <div class="card border">
                <h5>Filtros: </h5>
                <form class="row gy-2 gx-3 align-items-center" method="GET" action="/admin/funcs/filtro">
                    @csrf 
                    <div class="col-auto form-floating">
                        <input class="form-control mr-sm-2" type="text" placeholder="Nome" name="nome">
                        <label for="nome">Nome</label>
                    </div>
                    <div class="col-auto form-floating">
                        <select class="form-select" id="funcao" name="funcao">
                            <option value="">Selecione</option>
                            @foreach ($funcoes as $funcao)
                                <option value="{{$funcao->id}}">{{$funcao->nome}}</option>
                            @endforeach
                        </select>
                        <label for="funcao">Função</label>
                    </div>  
                    <div class="col-auto">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                    </div>
                </form>
            </div>
            <hr/>
            <h5>Exibindo {{$funcs->count()}} de {{$funcs->total()}} de Funcionários ({{$funcs->firstItem()}} a {{$funcs->lastItem()}})</h5>
            <hr/>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Login</th>
                        <th scope="col">Ativo</th>
                        <th scope="col">Última Atualização</th>
                        <th scope="col">Ações</th>
                        <th>Funções</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($funcs as $func)
                    <tr>
                        <td>{{$func->id}}</td>
                        <td>{{$func->name}}</td>
                        <td>{{$func->email}}</td>
                        <td>
                            @if($func->ativo==1)
                                <b><i class="material-icons green">check_circle</i></b>
                            @else
                                <b><i class="material-icons red">highlight_off</i></b>
                            @endif
                        </td>
                        <td>{{ $func->updated_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <button type="button" class="badge bg-warning" data-bs-toggle="modal" data-bs-target="#exampleModal{{$func->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                <i class="material-icons md-18">edit</i>
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$func->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edição de func</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="/admin/funcs/editar/{{$func->id}}">
                                                @csrf
                                                <div class="col-12 form-floating">
                                                    <input id="name" type="text" class="form-control" name="name" value="{{$func->name}}" required autocomplete="name" autofocus>
                                                    <label for="name">Nome</label>
                                                </div>  
                                                <div class="col-12 form-floating">
                                                    <input id="email" type="email" class="form-control" name="email" value="{{$func->email}}" required autocomplete="email">
                                                    <label for="email">E-Mail</label>
                                                </div>  
                                                <div class="col-12 form-floating">
                                                    <input id="password" type="password" class="form-control" name="password" autocomplete="new-password">
                                                    <label for="password">Senha</label>
                                                </div>  
                                                <div class="col-12 form-floating">
                                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                                    <label for="password-confirm">Confirmação Senha</label>
                                                </div>
                                                <hr/>
                                                <h3>Funções</h3>
                                                <ul class="list-group list-group-flush">
                                                    @foreach ($funcoes as $funcao)
                                                        <li class="list-group-item d-flex" id="fazenda{{$funcao->id}}">
                                                            <input type="checkbox" class="form-check-input me-1" name="funcoes[]" value="{{$funcao->id}}" aria-label="..." @foreach ($func->funcoes as $fc) @if($funcao->id==$fc->id) checked @endif @endforeach>
                                                            {{$funcao->nome}}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-outline-primary">Salvar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($func->ativo==1)
                                <a href="/admin/funcs/ativar/{{$func->id}}" class="badge bg-secondary" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-18 red">disabled_by_default</i></a>
                            @else
                                <a href="/admin/funcs/ativar/{{$func->id}}" class="badge bg-secondary" data-toggle="tooltip" data-placement="right" title="Ativar"><i class="material-icons md-18 green">check_box</i></a>
                            @endif
                        </td>
                        <td>
                            <ul class="list-group">
                            @foreach ($func->funcoes as $funcao)
                                <li class="list-group-item d-flex justify-content-between">{{$funcao->nome}}  <a href="/admin/funcs/desvincularFuncFuncao/{{$func->id}}/{{$funcao->id}}"><i class="material-icons red" data-toggle="tooltip" data-placement="bottom" title="Remover">backspace</i></a></li>
                            @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div class="card-footer">
                {{ $funcs->links() }}
            </div>
            @endif
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModalCad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Funcionário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/admin/funcs">
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
                        <hr/>
                        <h3>Funções</h3>
                        <ul class="list-group list-group-flush">
                            @foreach ($funcoes as $funcao)
                                <li class="list-group-item d-flex" id="fazenda{{$funcao->id}}">
                                    <input type="checkbox" class="form-check-input me-1" name="funcoes[]" value="{{$funcao->id}}" aria-label="...">
                                    {{$funcao->nome}}
                                </li>
                            @endforeach
                        </ul>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-primary">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
