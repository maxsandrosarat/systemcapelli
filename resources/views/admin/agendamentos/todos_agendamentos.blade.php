@extends('layouts.app', ["current"=>"agendamentos"])

@section('body')
@php
	$page = "Admin Todos Agendamentos";
@endphp
<div class="card border">
    <div class="card-body">
        <a href="/admin/agendamentos/home" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
        <br/><br/>
        <h5 class="card-title">Todos Agendamentos</h5>
        @if(session('mensagem'))
            <div class="alert @if(session('type')=="success") alert-success @else @if(session('type')=="warning") alert-warning @else @if(session('type')=="danger") alert-danger @else alert-info @endif @endif @endif alert-dismissible fade show" role="alert">
                {{session('mensagem')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(count($agends)==0)
            <div class="alert alert-dark" role="alert">
                @if($view=="inicial")
                    Sem agendamentos cadastrados!
                @endif
                @if($view=="filtro")
                    Sem resultados da busca!
                    <a href="/admin/agendamentos/todos" class="btn btn-sm btn-success">Voltar</a>
                @endif
            </div>
        @else
            <div class="card border">
                <h5>Filtros: </h5>
                    <form class="row gy-2 gx-3 align-items-center" method="GET" action="/admin/agendamentos/todos/filtro">
                        @csrf
                        <div class="col-auto form-floating">
                            <input class="form-control" type="date" name="data" @if($dataAtual!="") value="{{date("Y-m-d", strtotime($dataAtual))}}" @else value="{{date("Y-m-d")}}" @endif>
                            <label for="data">A partir de</label>
                        </div>
                        <div class="col-auto form-floating">
                            <select class="form-select" name="servico">
                                <option value="">Selecione</option>
                                @foreach ($servs as $servico)
                                    <option value="{{$servico->id}}" @if($servico->ativo==false) style="color: red;"  @endif>{{$servico->nome}} @if($servico->ativo==false) (Inativo) @endif</option>
                                @endforeach
                            </select>
                            <label for="servico">Serviço</label>
                        </div>
                        <div class="col-auto form-floating">
                            <select class="form-select" name="func">
                                <option value="">Selecione</option>
                                @foreach ($funcs as $func)
                                    <option value="{{$func->id}}" @if($func->ativo==false) style="color: red;"  @endif>{{$func->name}} @if($func->ativo==false) (Inativo) @endif</option>
                                @endforeach
                            </select>
                            <label for="func">Funcionário</label>
                        </div>
                        <div class="col-auto form-floating">
                            <input class="form-control" list="datalistOptions" name="cliente" placeholder="Cliente">
                            <label class="form-label" for="cliente">Cliente</label>
                            <datalist id="datalistOptions">
                                @foreach ($clientes as $cliente)
                                <option value="{{$cliente->email}}" @if($cliente->ativo==false) style="color: red;"  @endif>{{$cliente->name}} @if($cliente->ativo==false) (Inativo) @endif</option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-auto form-floating">
                            <select class="form-select" name="status">
                                <option value="">Todos</option>
                                <option value="ATENDIDO">Atendido</option>
                                <option value="PENDENTE">Pendente</option>
                                <option value="CANCELADO">Cancelado</option>
                            </select>
                            <label for="status">Status</label>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary position-relative" disabled>
                                Resultados
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{count($agends)}}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
                <hr/>
                <b><h5 class="font-italic">Exibindo {{$agends->count()}} de {{$agends->total()}} de Agendamentos ({{$agends->firstItem()}} a {{$agends->lastItem()}})</u></h5></b>
                <hr/>
                <div class="table-responsive-xl">
                    @foreach ($agends as $agend)
                        <a class="fill-div" data-bs-toggle="modal" data-bs-target="#exampleModal{{$agend->id}}">
                            <div id="my-div" class="bd-callout @if($agend->status=="ATENDIDO") bd-callout-success @else @if($agend->status=="PENDENTE") bd-callout-warning @else @if($agend->status=="CANCELADO") bd-callout-danger @else bd-callout-info @endif @endif @endif">
                                <h4>Dia: {{date("d/m/Y", strtotime($agend->data))}} - Hora: {{date('H:i', strtotime($agend->hora))}}</h4>
                                <p>{{$agend->servico->nome}}</p>
                            </div>
                        </a>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal{{$agend->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Agendamento - Dia: {{date("d/m/Y", strtotime($agend->data))}} - Hora: {{date('H:i', strtotime($agend->hora))}}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Código</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly value="{{$agend->id}}">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Cliente</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly value="{{$agend->user->name}}">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Status</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly value="{{$agend->status}}">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Data & Hora</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly value="{{date("d/m/Y", strtotime($agend->data))}} {{date('H:i', strtotime($agend->hora))}}">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Serviço</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly value="{{$agend->servico->nome}} - R$ {{$agend->valor}}">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Funcionário</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly value="{{$agend->func->name}}">
                                        </div>
                                        @if($agend->observacao!="")
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info">Observação</span>
                                            <textarea class="form-control" aria-label="With textarea" disabled readonly>{{$agend->observacao}}</textarea>
                                        </div>
                                        @endif
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Criou</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly value="{{$agend->criou}}">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Data Criação</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly value="{{date("d/m/Y H:i", strtotime($agend->created_at))}}">
                                        </div>
                                        @if($agend->editou!="")
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Editou</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly style="color:#DAA520" value="{{$agend->editou}}">
                                        </div>
                                        @endif
                                        @if($agend->status=="ATENDIDO")
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Atendeu</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly style="color:green" value="{{$agend->atendeu}}">
                                        </div>
                                        @endif
                                        @if($agend->status=="CANCELADO")
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Cancelou</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly style="color:red" value="{{$agend->cancelou}}">
                                        </div>
                                        @endif
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Última Alteração</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly value="{{date("d/m/Y H:i", strtotime($agend->updated_at))}}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        @if($agend->status=="CANCELADO")
                                            <div class="alert alert-danger" role="alert">
                                                CANCELADO
                                            </div>
                                        @else
                                            @if($agend->status=="ATENDIDO")
                                                <div class="alert alert-success" role="alert">
                                                    ATENDIDO
                                                </div>
                                            @else
                                                <a href="/admin/agendamentos/atendido/{{$agend->id}}" class="badge bg-success" data-toggle="tooltip" data-placement="right" title="Marcar Como Atendido"><i class="material-icons md-18">event_available</i></a>
                                                <a href="/admin/agendamentos/cancelar/{{$agend->id}}" class="badge bg-danger" data-toggle="tooltip" data-placement="right" title="Cancelar"><i class="material-icons md-18">event_busy</i></a>
                                                @if($agend->data<date("Y-m-d") || ($agend->data==date("Y-m-d") && $agend->hora<date("H:i:s"))) 
                                                @else
                                                <a href="/admin/agendamentos/editar/{{$agend->id}}" class="badge bg-warning" data-toggle="tooltip" data-placement="right" title="Editar"><i class="material-icons md-18">edit</i></a>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="card-footer">
                        {{ $agends->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
