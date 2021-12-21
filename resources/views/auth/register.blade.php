@extends('layouts.app', ["current"=>"cadastro"])

@section('body')
@php
	$page = "Cadastro";
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Cadastro</h3></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="col-12 form-floating">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nome">
                            <label for="name">Nome</label>
                        </div>
                        <div class="col-12 form-floating">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="E-Mail">
                            <label for="email">E-Mail</label>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-12 form-floating">
                            <input id="senhaForca" onkeyup="validarSenhaForca()" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Senha">
                            <label for="password">Senha</label>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <p class="fs-6 fst-italic">(Mínimo de 8 caracteres)</p>
                        </div>
                        <div class="col-12">
                            <div name="erroSenhaForca" id="erroSenhaForca"></div>
                            <label for="erroSenhaForca">Força Senha</label>
                        </div>
                        <div class="col-12 form-floating">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmação Senha">
                            <label for="password-confirm">Confirmação Senha</label>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-primary">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
