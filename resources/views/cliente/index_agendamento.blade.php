@extends('layouts.app', ["current"=>"agendamentos"])

@section('body')
@php
	$page = "Agendamentos";
@endphp
<div class="card border">
    <div class="card-body">
        <div class="row">
            <div class="col" style="text-align: left">
                <h5 class="card-title">Meus Agendamentos</h5>
            </div>
            <div class="col" style="text-align: right">
                <p>
                    <a class="btn btn-outline-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        <i class="material-icons blue md-18">filter_alt</i>
                    </a>
                </p>
            </div>
        </div>
        
        @if(session('mensagem'))
            <div class="alert @if(session('type')=="success") alert-success @else @if(session('type')=="warning") alert-warning @else @if(session('type')=="danger") alert-danger @else alert-info @endif @endif @endif alert-dismissible fade show" role="alert">
                {{session('mensagem')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <a type="button" class="float-button" data-bs-toggle="modal" data-bs-target="#exampleModalNovo" data-toggle="tooltip" data-placement="bottom" title="Adicionar Novo Agendamento">
            <i class="material-icons blue md-60">more_time</i>
        </a>
        @if(count($agends)==0)
            <div class="alert alert-dark" role="alert">
                @if($view=="inicial")
                    Sem agendamentos cadastrados!
                @endif
                @if($view=="filtro")
                    Sem resultados da busca!
                    <a href="/agendamentos" class="btn btn-sm btn-success">Voltar</a>
                @endif
            </div>
        @else
        <div class="collapse" id="collapseExample">
          <div class="card card-body">
            <h5>Filtros: </h5>
                    <form class="row gy-2 gx-3 align-items-center" method="GET" action="/agendamentos/filtro">
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
        </div>
            <hr/>
                <b><h5 class="font-italic">Exibindo {{$agends->count()}} de {{$agends->total()}} de Agendamentos ({{$agends->firstItem()}} a {{$agends->lastItem()}})</u></h5></b>
                <hr/>
                <div class="table-responsive-xl">
                    @foreach ($agends as $agend)
                        <div class="fill-div">
                            <div id="my-div" class="bd-callout @if($agend->status=="ATENDIDO") bd-callout-success @else @if($agend->status=="PENDENTE") bd-callout-warning @else @if($agend->status=="CANCELADO") bd-callout-danger @else bd-callout-info @endif @endif @endif">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal{{$agend->id}}" style="text-decoration: none;"><h4>Dia: {{date("d/m/Y", strtotime($agend->data))}} - Hora: {{date('H:i', strtotime($agend->hora))}}</h4>
                                <p>Serviço: {{$agend->servico->nome}}</p></a>
                            </div>
                        </div>
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
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Status</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly value="{{$agend->status}}">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Data & Hora</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly value="{{date("d/m/Y", strtotime($agend->data))}} {{date('H:i', strtotime($agend->hora))}}">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Serviço</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly value="{{$agend->servico->nome}} (R$ {{$agend->valor}})">
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
                                            <span class="input-group-text title-info" id="inputGroup-sizing-default">Data Criação</span>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-default" disabled readonly value="{{date("d/m/Y H:i", strtotime($agend->created_at))}}">
                                        </div>
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
                                                <a type="button" class="badge bg-danger" href="#" class="btn-close" data-bs-toggle="modal" data-bs-target="#exampleModalDelete{{$agend->id}}" data-toggle="tooltip" data-placement="bottom" title="Cancelar Agendamento"><i class="material-icons md-18">event_busy</i></a>
                                                <!-- Modal Deletar -->
                                                <div class="modal fade" id="exampleModalDelete{{$agend->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Cancelamento de Agendamento: {{$agend->servico->nome}} - {{date("d/m/Y", strtotime($agend->data))}} {{date('H:i', strtotime($agend->hora))}}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h5>Tem certeza que deseja cancelar esse agendamento?</h5>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <a type="button" class="btn btn-danger" href="/agendamentos/cancelar/{{$agend->id}}">Sim</a>
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
<!-- Modal Novo Agendamento -->
<div class="modal fade" id="exampleModalNovo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Novo Agendamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-novo" method="GET" action="/agendamentos/painel">
                    @csrf
                    <div class="form-group row">
                        @if(Auth::user()->telefone=="")
                        <div class="col-12 form-floating">
                            <input class="form-control" type="text" id="telefone0" name="telefone" onblur="formataNumeroTelefone(0)" placeholder="Telefone (Obrigatório)" required>
                            <label for="telefone">Telefone (Obrigatório)</label>
                        </div>
                        @endif
                        <div class="col-12 form-floating">
                            <input class="form-control" type="date" name="data" value="{{date("Y-m-d")}}">
                            <label for="data">A partir de</label>
                        </div>
                        <div class="col-12 form-floating">
                            <select class="form-select" id="servico" name="servico" onchange="valorServico();" required>
                                <option value="">Selecione</option>
                                @foreach ($servs as $servico)
                                    @if($servico->ativo)
                                    <option value="{{$servico->id}}" title="{{$servico->preco}}">{{$servico->nome}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <label for="servico">Serviço</label>
                        </div>
                        <div class="col-12 form-floating">
                            <input type="text" class="form-control" name="valor" id="valor" placeholder="Valor Estimado" onblur="getValor('valor')" required disabled readonly>
                            <label for="valor">Valor Estimado</label>
                        </div>
                        <hr/>
                        <p>*Caso deseje um funcionário específico, caso contrário não selecione!</p>
                        <div class="col-12 form-floating">
                            <select class="form-select" id="func" name="func">
                                <option value="">Selecione o serviço</option>
                            </select>
                            <label for="func">Funcionário</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="form-novo" class="btn btn-outline-primary">Consultar</button>
            </div>
        </div>
    </div>
</div>
{!! csrf_field() !!}
@endsection
