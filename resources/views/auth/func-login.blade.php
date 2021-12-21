@extends('layouts.app', ["current"=>"login-func"])

@section('body')
@php
	$page = "Login Func";
@endphp
<div class="text-center">
	@auth("web")
        <b> <h3>Você está logado como  {{Auth::user()->name}}  !</h3></b>
		<h5>Acesse sua página principal: <a href="{{ url('/home') }}">Home</a></h5>
		<h5>Ou faça logout: <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></h5>
		<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @else
        @auth("func")
            <b> <h3>Você está logado como  {{Auth::guard('func')->user()->name}}  !</h3></b>
			<h5>Acesse sua página principal: <a href="{{ url('/func') }}">Home</a></h5>
			<h5>Ou faça logout: <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></h5>
			<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            @auth("admin")
                <b> <h3>Você está logado como  {{Auth::guard('admin')->user()->name}}  !</h3></b>
                <h5>Acesse sua página principal: <a href="{{ url('/admin') }}">Home</a></h5>
                <h5>Ou faça logout: <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></h5>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    @if(session('mensagem'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{session('mensagem')}}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <div class="form-floating">
                                        <select class="form-select" name="tipoLogin" id="tipoLogin">
                                            <option value="">Selecione</option>
                                            <option value="func">FUNCIONÁRIO</option>
                                            <option value="admin">ADMINISTRADOR</option>
                                        </select>
                                        <label for="cidade">Tipo de Login</label>
                                    </div>
                                    <div id="principal">
                                        <div class="card" id="func">
                                            
                                            <form method="POST" action="{{ route('func.login.submit') }}">
                                                 @csrf
                                                <br/>
                                                <img class="mb-4" src="/storage/logo.png" alt="logo" width="80px">
                                                <h1 class="h3 mb-3 fw-normal">Funcionário</h1>
                                                <div class="form-floating">
                                                    <input type="email" class="form-control" name="email" id="floatingInput" placeholder="name@example.com">
                                                    <label for="floatingInput">Email</label>
                                                </div>
                                                <div class="form-floating">
                                                    <input type="password" class="form-control" name="password" id="password-func" placeholder="Password">
                                                    <label for="password-func">Senha</label>
                                                    <button id="button-password-func" class="badge bg-success rounded-pill" type="button" data-toggle="tooltip" data-placement="bottom" title="Exibir Senha" onclick="mostrarSenha('password-func','button-password-func','icon-password-func')"><i id="icon-password-func"class="material-icons white">visibility</i></button>
                                                </div>
                                                <br/>
                                                <div class="checkbox mb-3">
                                                    <div class="form-check form-switch">
                                                        <label class="form-check-label" for="flexSwitchCheckDefault">
                                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" value="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                            Manter conectado
                                                        </label>
                                                    </div>
                                                </div>
                                                <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
                                            </form>
                                        </div>
                                        <div class="card" id="admin">
                                            <form method="POST" action="{{ route('admin.login.submit') }}">
                                                @csrf
                                                <br/>
                                                <img class="mb-4" src="/storage/logo.png" alt="logo" width="80px">
                                                <h1 class="h3 mb-3 fw-normal">Administrador</h1>
                                                <div class="form-floating">
                                                    <input type="email" class="form-control" name="email" id="floatingInput" placeholder="name@example.com">
                                                    <label for="floatingInput">Email</label>
                                                </div>
                                                <div class="form-floating">
                                                    <input type="password" class="form-control" name="password" id="password-admin" placeholder="Password">
                                                    <label for="password-admin">Senha</label>
                                                    <button id="button-password-admin" class="badge bg-success rounded-pill" type="button" data-toggle="tooltip" data-placement="bottom" title="Exibir Senha" onclick="mostrarSenha('password-admin','button-password-admin','icon-password-admin')"><i id="icon-password-admin" class="material-icons white">visibility</i></button>
                                                </div>
                                                <br/>
                                                <div class="checkbox mb-3">
                                                    <div class="form-check form-switch">
                                                        <label class="form-check-label" for="flexSwitchCheckDefault">
                                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" value="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                            Manter conectado
                                                        </label>
                                                    </div>
                                                </div>
                                                <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        @endauth
    @endauth
</div>
@endsection