@extends('layouts.app', ["current"=>"agendamentos"])

@section('body')
@php
	$page = "Novo Agendamento";
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <br/>
            <a href="/agendamentos" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            <div class="card text-center">
                    <div class="card-header"><h3>Novo Agendamento</h3></div>
                    <div class="card-body">
                        <h5 class="card-title">Dia: {{date("d/m/Y", strtotime($data))}} - Hora: {{date('H:i', strtotime($hora))}}</h5>
                        <form method="POST" action="/agendamentos">
                            @csrf
                            <div class="form-group row">
                                <input type="hidden" name="data" id="data" value="{{$data}}"required>
                                <input type="hidden" name="hora" id="hora" value="{{$hora}}" required>
                                <div class="col-12 form-floating">
                                    <select class="form-select" id="servico" name="servico" readonly required>
                                        <option value="{{$servico->id}}" selected>{{$servico->nome}}</option>
                                    </select>
                                    <label for="servico">Serviço</label>
                                </div>
                                <div class="col-12 form-floating">
                                    <input type="text" class="form-control" name="valor" id="valor" value="{{$servico->preco}}" readonly required>
                                    <label for="valor">Valor Estimado</label>
                                </div>
                                @if($funcionario!="")
                                <div class="col-12 form-floating">
                                    <select class="form-select" id="func" name="func" readonly required>
                                        <option value="{{$funcionario->id}}" selected>{{$funcionario->name}}</option>
                                    </select>
                                    <label for="func">Funcionário</label>
                                </div>
                                @else
                                <div class="col-12 form-floating">
                                    <select class="form-select" id="func" name="func" required>
                                        <option value="">Selecione</option>
                                        @foreach ($funcs as $func)
                                        <option value="{{$func->id}}">{{$func->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="func">Funcionário</label>
                                </div>
                                @endif
                                <div class="input-group mb-3">
                                    <span class="input-group-text title-info">Observação</span>
                                    <textarea class="form-control" name="observacao" id="observacao"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="/agendamentos" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
                                <button type="submit" class="btn btn-outline-primary">Confirmar</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
</div>
{!! csrf_field() !!}
@endsection
