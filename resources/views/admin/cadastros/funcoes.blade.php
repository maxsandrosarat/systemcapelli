@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
@php
	$page = "Admin Funções";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/admin/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            <h5 class="card-title">Lista de Funções - Total: {{count($funcoes)}}</h5>
            @if(session('mensagem'))
                <div class="alert @if(session('type')=="warning") alert-warning @else alert-success @endif alert-dismissible fade show" role="alert">
                    {{session('mensagem')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <a type="button" class="float-button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Nova Função">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Função</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card border">
                            <div class="card-body">
                                <form action="/admin/funcoes" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <div class="col-12 form-floating">
                                            <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome" required>
                                            <label for="nome">Nome</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            @if(count($funcoes)==0)
                <div class="alert alert-danger" role="alert">
                    Sem Funções cadastradas!
                </div>
            @else

            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($funcoes as $funcao)
                    <tr>
                        <td>{{$funcao->id}}</td>
                        <td>{{$funcao->nome}}</td>
                        <td>
                            <button type="button" class="badge bg-warning" data-bs-toggle="modal" data-bs-target="#exampleModal{{$funcao->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                <i class="material-icons md-18">edit</i>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$funcao->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Raça</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card border">
                                            <div class="card-body">
                                                <form action="/admin/funcoes/editar/{{$funcao->id}}" method="POST">
                                                    @csrf
                                                    <div class="form-group">
                                                        <div class="col-12 form-floating">
                                                        <input type="text" class="form-control" name="nome" id="nome" value="{{$funcao->nome}}" required>
                                                        <label for="nome">Nome</label>
                                                        <div class="col-12 form-floating">                            
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
    </div>
@endsection
