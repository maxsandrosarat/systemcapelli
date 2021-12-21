@extends('layouts.app', ["current"=>"home"])

@section('body')
@php
	$page = "Home";
@endphp
<div class="container">
  <br/>
  <div class="row align-items-md-stretch">
    <div class="col">
      <div class="h-100 p-5 text-white bg-dark rounded-3">
        <h1 class="display-5 fw-bold text-center">Olá, {{Auth::user()->name}}!</h1>
        <p class="fs-4 text-center">SEJA BEM-VINDO(A) AO SYSTEM CAPELLI (Sistema de Agendamentos)</p>
        <div class="text-center">
        <a href="/agendamentos" type="button" class="btn btn-outline-light"><b>Agende seu Horário</b></a>
        </div>
      </div>
    </div>
  </div>
@if(count($promocoes)>0)
    <h2 class="promocao h2 text-center">Serviços em Promoção</h2>
    <div class="row justify-content-center">
      @foreach ($promocoes as $servico)
      <div class="col-auto">
          <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
              <div class="card-header border-light"><h3><b>*{{$servico->nome}}*</b></h3> <h6 class="promocao h6">Em Promoção</h6></div>
              <div class="card-body">
                  <h5 class="card-title">Por Apenas {{ 'R$ '.number_format($servico->preco, 2, ',', '.')}}</h5>
                  <small><s>{{ 'R$ '.number_format($servico->preco_antigo, 2, ',', '.')}}</s></small>
                  @isset($servico->ressalva)
                    <p class="card-text"><small>*{{$servico->ressalva}}</small></p>
                  @endisset
              </div>
              <div class="card-footer border-light"><small>Validade: {{date("d/m/Y", strtotime($servico->inicio))}} - {{date("d/m/Y", strtotime($servico->fim))}}</small></div>
          </div>
      </div>
      @endforeach
    </div>
@endif
</div>
{{-- <div class="container-fluid">
  <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      @foreach ($banners as $banner)
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{$banner->ordem -1}}" @if($banner->ordem==1) class="active" @endif aria-current="true" aria-label="Slide {{$banner->ordem}}"></button>
      @endforeach
    </div>
    <div class="carousel-inner">
      @foreach ($banners as $banner)
      <div class="carousel-item @if($banner->ordem==1) active @endif">
        <img src="/storage/{{$banner->foto}}" class="d-block w-100" alt="foto_banner{{$banner->id}}">
        <div class="carousel-caption d-none d-md-block">
          <h5>{{$banner->titulo}}</h5>
          <p>{!!html_entity_decode($banner->descricao)!!}</p>
        </div>
      </div>
      @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</div> --}}
@endsection