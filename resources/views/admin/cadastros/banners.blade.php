@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
@php
	$page = "Admin Banners";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/admin/cadastros" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            @if(session('mensagem'))
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{session('mensagem')}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
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
            <h5 class="card-title">Banners</h5>
            <a type="button" class="float-button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Criar Novo Banner">
                <i class="material-icons blue md-60">add_photo_alternate</i>
            </a>
            @if(count($banners)==0)
                <div class="alert alert-danger" role="alert">
                    Sem banners cadastrados!
                </div>
            @else
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Ordem</th>
                        <th>Criação</th>
                        <th>Última Atualização</th>
                        <th>Foto</th>
                        <th>Título</th>
                        <th>Descricao</th>
                        <th>Ativo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($banners as $banner)
                    <tr>
                        <td>{{$banner->id}}</td>
                        <td>{{$banner->ordem}}º</td>
                        <td>{{date("d/m/Y H:i", strtotime($banner->created_at))}}</td>
                        <td>{{date("d/m/Y H:i", strtotime($banner->updated_at))}}</td>
                        <td width="120"><button type="button" data-bs-toggle="modal" data-bs-target="#exampleModalFoto{{$banner->id}}">@if($banner->foto!="")<img style="margin:0px; padding:0px;" src="/storage/{{$banner->foto}}" alt="foto_banner" width="50%"> @else <i class="material-icons md-48">no_photography</i> @endif</button></td>
                        <!-- Modal -->
                        <div class="modal fade bd-example-modal-lg" id="exampleModalFoto{{$banner->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if($banner->foto!="") <img src="/storage/{{$banner->foto}}" alt="foto_banner" style="width: 100%"> @else <i class="material-icons md-60">no_photography</i> @endif
                            </div>
                            </div>
                        </div>
                        </div>
                        <td>{{$banner->titulo}}</td>
                        <td><button type="button" class="badge bg-primary" data-bs-toggle="modal" data-bs-target="#exampleModalDesc{{$banner->id}}">Descrição</button></td>
                        <!-- Modal -->
                        <div class="modal fade bd-example-modal-lg" id="exampleModalDesc{{$banner->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Descrição Banner: {{$banner->id}} - {{$banner->titulo}}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                {{$banner->descricao}}
                            </div>
                            </div>
                        </div>
                        </div>
                        <td>
                            @if($banner->ativo==1)
                                <b><i class="material-icons green">check_circle</i></b>
                            @else
                                <b><i class="material-icons red">highlight_off</i></b>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                            <button type="button" class="badge bg-warning" data-bs-toggle="modal" data-bs-target="#exampleModal{{$banner->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                <i class="material-icons md-18 black">edit</i>
                            </button>
                            @if($banner->ativo==1)
                                <a type="button" href="/admin/banners/ativar/{{$banner->id}}" class="badge bg-dark" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-18 red">disabled_by_default</i></a>
                            @else
                                <a type="button" href="/admin/banners/ativar/{{$banner->id}}" class="badge bg-dark" data-toggle="tooltip" data-placement="right" title="Ativar"><i class="material-icons md-18 green">check_box</i></a>
                            @endif
                            <button type="button" class="badge bg-danger" data-bs-toggle="modal" data-bs-target="#modalDelete{{$banner->id}}" data-toggle="tooltip" data-placement="right" title="Excluir"><i class="material-icons md-18">delete</i></button>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$banner->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Banner</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card border">
                                            <div class="card-body">
                                                <form action="/admin/banners/editar/{{$banner->id}}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row g-3 form-group">
                                                        <div class="mb-3">
                                                            <label for="foto" class="form-label">Banner</label>
                                                            <input class="form-control" type="file" id="foto" name="foto" accept=".jpg,.png,.jpeg">
                                                        </div>
                                                        <small><b>Resolução ideal da imagem 750 x 400 ou na mesma proporção<br/>
                                                        Aceito apenas Imagens JPG e PNG (".jpg" e ".png")</b></small>
                                                        <div class="form-floating">
                                                            <select class="form-select" id="ordem" name="ordem">
                                                                <option value="{{$banner->ordem}}">{{$banner->ordem}}º</option>
                                                                @for($i=1; $i<$banner->ordem; $i++)
                                                                <option value="{{$i}}">{{$i}}º</option>
                                                                @endfor
                                                            </select>
                                                            <label for="ordem">Ordem</label>
                                                        </div>
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control" name="titulo" id="titulo" value="{{$banner->titulo}}" required>
                                                            <label for="titulo">Título</label>
                                                        </div>
                                                        <div class="form-floating">
                                                            <textarea class="form-control" name="descricao" id="descricao" style="height: 100px">{{$banner->descricao}}</textarea>
                                                            <label for="descricao">Descrição</label>
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
                            <!-- Modal -->
                                        <div class="modal fade bd-example-modal-lg" id="modalDelete{{$banner->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Excluir Banner: {{$banner->id}} - {{$banner->titulo}}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>Tem certeza que deseja excluir esse banner?</h5>
                                                        <p>Não será possivel reverter esta ação.</p>
                                                        <a href="/admin/banners/apagar/{{$banner->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Inativar">Excluir</a>
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
    <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card border">
                            <div class="card-body">
                                <form action="/admin/banners" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3 form-group">
                                        <div class="mb-3">
                                            <label for="foto" class="form-label">Banner</label>
                                            <input class="form-control" type="file" id="foto" name="foto" accept=".jpg,.png,.jpeg" required>
                                        </div>
                                        <small><b>Resolução ideal da imagem 750 x 400 ou na mesma proporção<br/>
                                        Aceito apenas Imagens JPG e PNG (".jpg" e ".png")</b></small>
                                        @php
                                            $qtd = count($banners);
                                        @endphp
                                        <div class="form-floating">
                                            <select class="form-select" id="ordem" name="ordem">
                                                <option value="">Selecione a ordem</option>
                                                @for($i=1; $i<=$qtd+1; $i++)
                                                <option value="{{$i}}">{{$i}}º</option>
                                                @endfor
                                            </select>
                                            <label for="ordem">Ordem</label>
                                        </div>
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Exemplo: Educação" required>
                                            <label for="titulo">Titulo</label>
                                        </div>
                                        <div class="form-floating">
                                            <textarea class="form-control" name="descricao" id="descricao" style="height: 100px"></textarea>
                                            <label for="descricao">Descrição</label>
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
@endsection