@extends('layouts.app', ["current"=>"agendamentos"])

@section('body')
@php
	$page = "Admin Novo Agendamento";
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <br/>
            <a href="/admin/agendamentos" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            <div class="card text-center">
                @if($view=="novo")
                    <div class="card-header"><h3>Novo Agendamento</h3></div>
                    <div class="card-body">
                        <h5 class="card-title">Dia: {{date("d/m/Y", strtotime($data))}} - Hora: {{date('H:i', strtotime($hora))}}</h5>
                        <form method="POST" action="/admin/agendamentos">
                            @csrf
                            <div class="form-group row">
                                <input type="hidden" name="data" id="data" value="{{$data}}" required>
                                <input type="hidden" name="hora" id="hora" value="{{$hora}}" required>
                                <div class="col-12 form-floating">
                                    <select class="form-select" id="servico" name="servico" onchange="valorServico();" required>
                                        <option value="">Selecione</option>
                                        @foreach ($servs as $servico)
                                            <option value="{{$servico->id}}" title="{{$servico->preco}}">{{$servico->nome}}</option>
                                        @endforeach
                                    </select>
                                    <label for="servico">Serviço</label>
                                </div>
                                <div class="col-12 form-floating">
                                    <input type="text" class="form-control" name="valor" id="valor" placeholder="Valor à cobrar" onblur="getValor('valor')" required>
                                    <label for="valor">Valor à cobrar</label>
                                </div>
                                <div id="select-func" class="col-12 form-floating">
                                    <select class="form-select" id="func" name="func" required>
                                        <option value="">Selecione o serviço</option>
                                    </select>
                                    <label for="func">Funcionário</label>
                                </div>
                                <div id="input-cliente" class="col-12 form-floating">
                                    <input class="form-control" list="datalistOptions" name="cliente" id="cliente" placeholder="Cliente" required>
                                    <label class="form-label" for="cliente">Cliente</label>
                                    <datalist id="datalistOptions">
                                        @foreach ($clientes as $cliente)
                                        @if($cliente->ativo==true)
                                        <option value="{{$cliente->email}}">{{$cliente->name}}</option>
                                        @endif
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text title-info">Observação</span>
                                    <textarea class="form-control" name="observacao" id="observacao"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="/admin/agendamentos" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
                                <button type="submit" class="btn btn-outline-primary">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="card-header"><h3>Editar Agendamento</h3></div>
                    <div class="card-body">
                        <h5 class="card-title">Dia: {{date("d/m/Y", strtotime($agend->data))}} - Hora: {{date('H:i', strtotime($agend->hora))}}</h5>
                        <form method="POST" action="/admin/agendamentos">
                            @csrf
                            <div class="form-group row">
                                <input type="hidden" name="id" value="{{$agend->id}}"required>
                                <input type="hidden" name="data" id="data" value="{{$agend->data}}"required>
                                <input type="hidden" name="hora" id="hora" value="{{$agend->hora}}" required>
                                <div class="col-12 form-floating">
                                    <select class="form-select" id="servico" name="servico" onchange="valorServico();" required>
                                        <option value="{{$agend->servico->id}}" title="{{$agend->servico->preco}}">{{$agend->servico->nome}}</option>
                                        @foreach ($servs as $servico)
                                            @if($servico->id==$agend->servico->id)
                                            @else
                                            <option value="{{$servico->id}}" title="{{$servico->preco}}">{{$servico->nome}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <label for="servico">Serviço</label>
                                </div>
                                <div class="col-12 form-floating">
                                    <input type="text" class="form-control" name="valor" id="valor" value="{{$agend->valor}}" onblur="getValor('valor')" required>
                                    <label for="valor">Valor à cobrar</label>
                                </div>
                                <div id="select-func" class="col-12 form-floating">
                                    <select class="form-select" id="func" name="func" required>
                                        <option value="{{$agend->func->id}}">{{$agend->func->name}}</option>
                                    </select>
                                    <label for="func">Funcionário</label>
                                </div>
                                <div id="input-cliente" class="col-12 form-floating">
                                    <input class="form-control" list="datalistOptions" name="cliente" id="cliente" value="{{$agend->user->email}}">
                                    <label class="form-label" for="cliente">Cliente</label>
                                    <datalist id="datalistOptions">
                                        @foreach ($clientes as $cliente)
                                        @if($cliente->ativo==true)
                                        <option value="{{$cliente->email}}">{{$cliente->name}}</option>
                                        @endif
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text title-info">Observação</span>
                                    <textarea class="form-control" name="observacao" id="observacao">{{$agend->observacao}}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="/admin/agendamentos" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
                                <button type="submit" class="btn btn-outline-primary">Alterar</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
{!! csrf_field() !!}
@endsection
