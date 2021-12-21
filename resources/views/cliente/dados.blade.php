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
                    <form method="POST" action="/dados">
                        @csrf
                        <div class="col-12 form-floating">
                            <input id="name" type="text" class="form-control" name="name" value="{{$user->name}}" required autocomplete="name" autofocus>
                            <label for="name">Nome (Obrigatório)</label>
                        </div>  
                        <div class="col-12 form-floating">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$user->email}}" required autocomplete="email" placeholder="E-Mail">
                            <label for="email">E-Mail (Obrigatório)</label>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-12 form-floating">
                            <input id="nascimento" type="date" class="form-control" name="nascimento" value="{{$user->nascimento}}">
                            <label for="nascimento">Nascimento (Opcional)</label>
                        </div>
                        <div class="col-12 form-floating">
                            <input name="telefone" class="form-control" id="telefone0" onblur="formataNumeroTelefone(0)" size="60" @if($user->telefone!="") value="{{$user->telefone}}" @else placeholder="Telefone (Obrigatório)" @endif required>
                            <label for="telefone">Telefone (Obrigatório)</label>
                        </div>
                        <div class="col-12 form-floating">
                            <input name="whatsapp" class="form-control" id="whatsapp0" onblur="formataNumeroWhatsapp(0)" size="60" @if($user->whatsapp!="") value="{{$user->whatsapp}}" @else placeholder="WhatsApp (Opcional)" @endif>
                            <label for="whatsapp">WhatsApp (Opcional)</label>
                        </div>
                        <div class="col-12 form-floating">
                            <input type="text" name="facebook" class="form-control" id="facebook" @if($user->facebook!="") value="{{$user->facebook}}" @else placeholder="Facebook (Opcional)" @endif>
                            <label for="facebook">Facebook (Opcional)</label>
                        </div>
                        <div class="col-12 form-floating">
                            <input type="text" name="instagram" class="form-control" id="instagram" @if($user->instagram!="") value="{{$user->instagram}}" @else placeholder="Instagram (Opcional)" @endif>
                            <label for="instagram">Instagram (Opcional)</label>
                        </div>
                        <hr/>
                        <h5>Alterar Senha</h5>
                        <div class="col-12 form-floating">
                            <input id="password-old" type="password" class="form-control" name="password_old" autocomplete="old-password" placeholder="Senha Atual">
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
