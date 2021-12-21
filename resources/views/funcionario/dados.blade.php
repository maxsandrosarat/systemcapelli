@extends('layouts.app', ["current"=>"dados"])

@section('body')
@php
	$page = "Meus Dados";
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if(session('mensagem'))
                    <div class="alert @if(session('type')=="success") alert-success @else @if(session('type')=="warning") alert-warning @else @if(session('type')=="danger") alert-danger @else alert-info @endif @endif @endif alert-dismissible fade show" role="alert">
                        {{session('mensagem')}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card-header"><h3>Meus Dados</h3></div>
                <div class="card-body">
                    <form method="POST" action="/func/dados">
                        @csrf
                        <div class="col-12 form-floating">
                            <input id="name" type="text" class="form-control" value="{{$func->name}}" disabled readonly>
                            <label for="name">Nome</label>
                        </div>  
                        <div class="col-12 form-floating">
                            <input id="email" type="email" class="form-control" value="{{$func->email}}" disabled readonly>
                            <label for="email">E-Mail</label>
                        </div>
                        <hr/>
                        <h5>Alterar Senha</h5>
                        <div class="col-12 form-floating">
                            <input id="password-old" type="password" class="form-control" name="password_old" autocomplete="old-password" placeholder="Senha Atual" required>
                            <label for="password-old">Senha Atual</label>
                        </div>
                        <div class="col-12 form-floating">
                            <input id="senhaForca" onkeyup="validarSenhaForca()" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Nova Senha">
                            <label for="password">Nova Senha</label>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <p style="font-style: italic; font-size: 10px;">(Mínimo de 8 caracteres)</p>
                        </div>
                        <div class="col-12">
                            <div name="erroSenhaForca" id="erroSenhaForca"></div>
                            <label for="erroSenhaForca">Força Senha</label>
                        </div>
                        <hr/>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
