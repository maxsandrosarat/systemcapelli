@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
@php
	$page = "Admin Cadastros";
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Funções</h5>
                    <p class="card-text">
                        Gerencie suas Funções!
                    </p>
                    <a href="/admin/funcoes" class="btn btn-primary">Gerenciar</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Serviços</h5>
                    <p class="card-text">
                        Gerencie seus Serviços! 
                    </p>
                    <a href="/admin/servicos" class="btn btn-primary">Gerenciar</a>
                </div>
            </div>
        </div>
        {{-- <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Banners</h5>
                    <p class="card-text">
                        Gerencie suas Banners! 
                    </p>
                    <a href="/admin/banners" class="btn btn-primary">Gerenciar</a>
                </div>
            </div>
        </div> --}}
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Funcionários</h5>
                    <p class="card-text">
                        Gerencie seus Funcionários! 
                    </p>
                    <a href="/admin/funcs" class="btn btn-primary">Gerenciar</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Clientes</h5>
                    <p class="card-text">
                        Gerencie seus Clientes!
                    </p>
                    <a href="/admin/usuarios" class="btn btn-primary">Gerenciar</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card border-primary text-center centralizado">
                <div class="card-body">
                    <h5>Admins</h5>
                    <p class="card-text">
                        Administradores
                    </p>
                    <a href="/admin/admin" class="btn btn-primary">Gerenciar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection