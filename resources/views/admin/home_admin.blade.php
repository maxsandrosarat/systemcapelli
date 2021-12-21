@extends('layouts.app', ["current"=>"home"])

@section('body')
@php
	$page = "Home Admin";
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto">
                    <div class="card border-primary text-center centralizado">
                        <div class="card-body">
                            <h5>Cadastros</h5>
                            <p class="card-text">
                                Gerencie seus cadastros! 
                            </p>
                            <a href="/admin/cadastros" class="btn btn-primary">Gerenciar</a>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="card border-primary text-center centralizado">
                        <div class="card-body">
                            <h5>Agendamentos</h5>
                            <p class="card-text">
                                Gerencie seus Agendamentos! 
                            </p>
                            <a href="/admin/agendamentos/home" class="btn btn-primary">Gerenciar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection