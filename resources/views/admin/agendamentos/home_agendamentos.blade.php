@extends('layouts.app', ["current"=>"agendamentos"])

@section('body')
@php
	$page = "Admin Agendamentos";
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto">
                    <div class="card border-primary text-center centralizado">
                        <div class="card-body">
                            <h5>Todos Agendamentos</h5>
                            <p class="card-text">
                                Gerencie seus cadastros! 
                            </p>
                            <a href="/admin/agendamentos/todos" class="btn btn-primary">Gerenciar</a>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="card border-primary text-center centralizado">
                        <div class="card-body">
                            <h5>Painel Agendamentos</h5>
                            <p class="card-text">
                                Gerencie seus Agendamentos! 
                            </p>
                            <a href="/admin/agendamentos" class="btn btn-primary">Gerenciar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection