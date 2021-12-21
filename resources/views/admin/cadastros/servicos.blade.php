@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
@php
	$page = "Admin Serviços";
@endphp
    <div class="card border">
        <div class="card-body">
            <a href="/admin/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
            <br/><br/>
            <h5 class="card-title">Lista de Serviços </h5>
            @if(session('mensagem'))
                <div class="alert @if(session('type')=="warning") alert-warning @else alert-success @endif alert-dismissible fade show" role="alert">
                    {{session('mensagem')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <a type="button" class="float-button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Novo Serviço">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Serviço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/admin/servicos" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="col-12 form-floating">
                                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do Serviço" required>
                                    <label for="nome">Nome do Serviço</label>
                                </div>
                                <div class="col-12 form-floating">
                                    <input type="text" class="form-control" name="preco" id="preco" placeholder="Preço do Serviço" onblur="getValor('preco')" required>
                                    <label for="preco">Preço do Serviço</label>
                                </div>
                                <div class="col-12 form-floating">
                                    <input type="time" class="form-control" name="tempo" id="tempo" placeholder="Tempo do Serviço" required>
                                    <label for="tempo">Tempo do Serviço</label>
                                </div>
                                <div class="form-floating">
                                    <select class="form-select" id="floatingSelect" name="funcao" required>
                                        <option value="">Selecione</option>
                                        @foreach ($funcoes as $funcao)
                                        <option value="{{$funcao->id}}">{{$funcao->nome}}</option>
                                        @endforeach
                                        </select>
                                    <label for="floatingSelect">Função</label>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                    </div>
                </form>
                </div>
                </div>
            </div>
            @if(count($servs)==0)
                <div class="alert alert-danger" role="alert">
                    Sem serviços cadastrados!
                </div>
            @else
            <hr/>
            <h5>Exibindo {{$servs->count()}} de {{$servs->total()}} de Serviço(s) ({{$servs->firstItem()}} a {{$servs->lastItem()}})</h5>
            <hr/>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Tempo</th>
                        <th>Função</th>
                        <th>Promoção</th>
                        <th>Ativo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($servs as $serv)
                    <tr>
                        <td style="text-align: center;">{{$serv->id}}</td>
                        <td style="text-align: center;">{{$serv->nome}}</td>
                        <td>{{ 'R$ '.number_format($serv->preco, 2, ',', '.')}}</td>
                        <td>{{date("H:i", strtotime($serv->tempo))}}</td>
                        <td>{{$serv->funcao->nome}}</td>
                        <td>
                            @if($serv->promocao==1)
                            <button type="button" class="btn btn-dark position-relative" data-bs-toggle="modal" data-bs-target="#modalHist{{$serv->id}}">
                                <b><i class="material-icons green">verified</i></b>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{count($serv->promocoes)}}
                                </span>
                            </button>
                            @else
                            <button type="button" class="btn btn-dark position-relative" data-bs-toggle="modal" data-bs-target="#modalHist{{$serv->id}}">
                                <b><i class="material-icons red">gpp_bad</i></b>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{count($serv->promocoes)}}
                                </span>
                            </button>
                            @endif
                            
                            <!-- Modal -->
                            <div class="modal fade" id="modalHist{{$serv->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Serviço: {{$serv->nome}}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if($serv->promocao)
                                                <a type="button" href="/admin/servicos/promocao/finalizar/{{$serv->id}}" class="btn btn-outline-danger byn-sm" data-toggle="tooltip" data-placement="right" title="Inativar">Finalizar Promoção</a>
                                            @else
                                                <button type="button" class="btn btn-outline-primary byn-sm" data-bs-dismiss="modal" aria-label="Close" data-bs-toggle="modal" data-bs-target="#modalPromo{{$serv->id}}">
                                                    Nova Promoção
                                                </button>
                                            @endif
                                            @if(count($serv->promocoes)>0)
                                            <br/><br/>
                                            <div class="table-responsive-xl">
                                                <table class="table table-striped table-ordered table-hover">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>Preço Antigo</th>
                                                            <th>Preço Novo</th>
                                                            <th>Inicio</th>
                                                            <th>Fim</th>
                                                            <th>Ressalva</th>
                                                            <th>Criou</th>
                                                            <th>Criação</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($serv->promocoes as $promocao)
                                                        <tr>
                                                            <td>{{ 'R$ '.number_format($promocao->preco_antigo, 2, ',', '.')}}</td>
                                                            <td>{{ 'R$ '.number_format($promocao->preco_novo, 2, ',', '.')}}</td>
                                                            <td>{{date("d/m/Y H:i", strtotime($promocao->inicio))}}</td>
                                                            <td>{{date("d/m/Y H:i", strtotime($promocao->fim))}}</td>
                                                            <td>
                                                                @if(isset($promocao->ressalva))
                                                                <!-- Button trigger modal -->
                                                                <button type="button" class="badge bg-info" data-bs-toggle="modal" data-bs-target="#modalRes{{$promocao->id}}">
                                                                    <i class="material-icons white">info</i>
                                                                </button>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="modalRes{{$promocao->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Promoção: {{$promocao->id}}</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                Ressalva: {{$promocao->ressalva}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            </td>
                                                            <td>{{$promocao->criou}}</td>
                                                            <td>{{date("d/m/Y H:i", strtotime($promocao->created_at))}}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($serv->promocao==false)
                            <!-- Modal -->
                            <div class="modal fade" id="modalPromo{{$serv->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Serviço: {{$serv->nome}}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/admin/servicos/promocao" method="POST">
                                                @csrf
                                                <div class="row g-3 form-group">
                                                    <input type="hidden" name="servico" value="{{$serv->id}}">
                                                    <div class="col-12 form-floating">
                                                        <input type="text" class="form-control" name="precoAntigo" id="precoAntigo" value="{{$serv->preco}}" required readonly>
                                                        <label for="precoAntigo">Preço Antigo</label>
                                                    </div>
                                                    <div class="col-12 form-floating">
                                                        <input type="text" class="form-control" name="precoNovo" id="precoNovo" placeholder="Novo Preço" onblur="getValor('preco')" required>
                                                        <label for="precoNovo">Novo Preço</label>
                                                    </div>
                                                    <div class="col-md-6 form-floating">
                                                        <input class="form-control" type="date" name="dataInicio" id="dataInicio" required>
                                                        <label for="dataInicio">Data Inicio</label>
                                                    </div>
                                                    <div class="col-md-4 form-floating">
                                                        <input class="form-control" type="time" name="horaInicio" id="horaInicio" required>
                                                        <label for="horaInicio">Hora Inicio</label>
                                                    </div>
                                                    <div class="col-md-6 form-floating">
                                                        <input class="form-control" type="date" name="dataFim" id="dataFim" required>
                                                        <label for="dataFim">Data Fim</label>
                                                    </div>
                                                    <div class="col-md-4 form-floating">
                                                        <input class="form-control" type="time" name="horaFim" id="horaFim" required>
                                                        <label for="horaFim">Hora Fim</label>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text title-info">Ressalva</span>
                                                        <textarea class="form-control" name="ressalva" id="ressalva"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                        <td>
                            @if($serv->ativo==1)
                                <b><i class="material-icons green">check_circle</i></b>
                            @else
                                <b><i class="material-icons red">highlight_off</i></b>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <button type="button" class="badge bg-warning" data-bs-toggle="modal" data-bs-target="#exampleModal{{$serv->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                <i class="material-icons md-18">edit</i>
                            </button>
                            
                            <div class="modal fade" id="exampleModal{{$serv->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Serviço</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="/admin/servicos/editar/{{$serv->id}}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <div class="col-12 form-floating">
                                                    <input type="text" class="form-control" name="nome" id="nome" value="{{$serv->nome}}" required>
                                                    <label for="nome">Nome do Serviço</label>
                                                </div>
                                                <div class="col-12 form-floating">
                                                    <input type="text" class="form-control" name="preco" id="precoE" value="{{$serv->preco}}" onblur="getValor('precoE')" required>
                                                    <label for="preco">Preço do Serviço</label>
                                                </div>  
                                                <div class="col-12 form-floating">
                                                    <input type="time" class="form-control" name="tempo" id="tempo" value="{{$serv->tempo}}" required>
                                                    <label for="tempo">Tempo do Serviço</label>
                                                </div>
                                                <div class="form-floating">
                                                    <select class="form-select" id="floatingSelect" name="funcao">
                                                        <option value="{{$serv->funcao->id}}">{{$serv->funcao->nome}}</option>
                                                        @foreach ($funcoes as $funcao)
                                                        @if ($funcao->id == $serv->funcao->id)
                                                        @else
                                                        <option value="{{$funcao->id}}">{{$funcao->nome}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                    <label for="floatingSelect">Função</label>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                    </div>
                                </form>
                                </div>
                                </div>
                            </div>
                            @if($serv->ativo==1)
                                <a href="/admin/servicos/ativar/{{$serv->id}}" class="badge bg-secondary" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-18 red">disabled_by_default</i></a>
                            @else
                                <a href="/admin/servicos/ativar/{{$serv->id}}" class="badge bg-secondary" data-toggle="tooltip" data-placement="right" title="Ativar"><i class="material-icons md-18 green">check_box</i></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                {{$servs->links() }}
            </div>
            </div>
            @endif
        </div>
    </div>
@endsection