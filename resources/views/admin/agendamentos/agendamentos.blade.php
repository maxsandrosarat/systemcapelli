@extends('layouts.app', ["current"=>"agendamentos"])

@section('body')
@php
	$page = "Admin Agendamentos";
@endphp
<div class="card border">
    <div class="card-body">
        <a href="/admin/agendamentos/home" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
        <br/><br/>
        <h5 class="card-title">Agendamentos
            <button type="button" class="btn btn-primary position-relative" disabled>
                Resultados
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{count($agends)}}
                </span>
            </button>
        </h5> 
            @if(session('mensagem'))
                <div class="alert @if(session('type')=="success") alert-success @else @if(session('type')=="warning") alert-warning @else @if(session('type')=="danger") alert-danger @else alert-info @endif @endif @endif alert-dismissible fade show" role="alert">
                    {{session('mensagem')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <a type="button" class="float-button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Nova Função">
                <i class="material-icons blue md-48">settings</i>
            </a>
        <div class="card border">
            <h5>Filtros: <span @if($filtrados>0) class="badge bg-warning" @else class="badge bg-secondary" @endif>{{$filtrados}}</span></h5>
            <form class="row gy-2 gx-3 align-items-center" method="GET" action="/admin/agendamentos/filtro">
                @csrf
                <div class="col-auto form-floating">
                    <input class="form-control" type="date" name="data" value="{{$dataAtual}}">
                    <label for="data">A partir de</label>
                </div>
                <div class="col-auto form-floating">
                    <select class="form-select" name="servico">
                        <option value="">Selecione</option>
                        @foreach ($servs as $servico)
                        <option value="{{$servico->id}}" @if($servico->ativo==false) style="color: red;"  @endif>{{$servico->nome}} @if($servico->ativo==false) (Inativo) @endif</option>
                        @endforeach
                    </select>
                    <label for="servico">Serviço</label>
                </div>
                <div class="col-auto form-floating">
                    <select class="form-select" name="func">
                        <option value="">Selecione</option>
                        @foreach ($funcs as $func)
                        <option value="{{$func->id}}" @if($func->ativo==false) style="color: red;"  @endif>{{$func->name}} @if($func->ativo==false) (Inativo) @endif</option>
                        @endforeach
                    </select>
                    <label for="func">Funcionário</label>
                </div>
                <div class="col-auto form-floating">
                    <input class="form-control" list="datalistOptions" name="cliente" placeholder="Cliente">
                    <label class="form-label" for="cliente">Cliente</label>
                    <datalist id="datalistOptions">
                        @foreach ($clientes as $cliente)
                        <option value="{{$cliente->email}}" @if($cliente->ativo==false) style="color: red;"  @endif>{{$cliente->name}} @if($cliente->ativo==false) (Inativo) @endif</option>
                        @endforeach
                    </datalist>
                </div>
                <div class="col-auto form-floating">
                    <select class="form-select" name="status">
                        <option value="">Todos</option>
                        <option value="ATENDIDO">Atendido</option>
                        <option value="PENDENTE">Pendente</option>
                        <option value="CANCELADO">Cancelado</option>
                    </select>
                    <label for="status">Status</label>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                </div>
                @if($filtrados>0)
                <div class="col-auto">
                    <a type="button" href="/admin/agendamentos" class="btn btn-primary my-2 my-sm-0">Limpar Filtro</a>
                </div>
                @endif
            </form>
        </div>
        <div class="table-responsive-xl">
            <table class="table table-bordered" style="text-align: center;">
                <thead class="thead-dark thead-bordered">
                    <tr>
                        <th class="fixar">Horários</th>
                        @for ($i = 0; $i < 7; $i++)
                        @php
                            $dataSemana = date('Y-m-d', strtotime($dataAtual. ' + '.$i.' days'));
                            $diasemana = array('Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado');
                            $diasemana_numero = date('w', strtotime($dataSemana)); 
                        @endphp
                        <th>{{date("d/m/Y", strtotime($dataSemana))}} <br/>({{$diasemana[$diasemana_numero]}})</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @php
                        $tempo = (date('H', strtotime( "$fim" )) - date('H', strtotime( "$inicio"))) + 1;
                    @endphp
                    @for ($i = 0; $i < $tempo; $i++)
                        <tr>
                            @php
                                $hora = date('H:i:s', strtotime($inicio) + strtotime( "$i:00:00" ) - strtotime( "00:00:00" ));
                            @endphp
                            <td id="primeiraColuna" class="fixar">{{date('H:i', strtotime($hora))}}</td>
                            @for ($j = 0; $j < 7; $j++)
                                @foreach ($configs as $config)
                                    @php
                                        $qtdAged = 0;
                                        $cancelados = 0;
                                        $qtdAgendGeral = 0;
                                        $dataSemana = date('Y-m-d', strtotime($dataAtual. ' + '.$j.' days'));
                                        $diasemana = array('Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado');
                                        $diasemana_numero = date('w', strtotime($dataSemana)); 
                                    @endphp
                                    @if($diasemana[$diasemana_numero]==$config->diaSemana)
                                        @if($hora>=$config->intervaloInicio && $hora<=$config->intervaloFim)
                                            <td><h4>Intervalo</h4></td>
                                        @else
                                            <td id="celulas">
                                                @if($config->abertura=="00:00:00" && $config->fechamento=="00:00:00" || $hora<$config->abertura || $hora>$config->fechamento)
                                                    <button type="button" class="btn btn-danger btn-sm" disabled>
                                                        <i class="material-icons black md-12">event_busy</i>
                                                    </button>
                                                @else
                                                    <!-- Modal -->
                                                        <div class="modal fade" id="exampleModalQtd{{$i}}{{$j}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Agendamento - Dia: {{date("d/m/Y", strtotime($dataSemana))}} - Hora: {{date('H:i', strtotime($hora))}}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        @foreach ($agends as $agend)
                                                                            @if($agend->data==$dataSemana && $agend->hora==$hora)
                                                                                @php
                                                                                    if($agend->status!="CANCELADO"){
                                                                                        $qtdAged++;
                                                                                    } else {
                                                                                        $cancelados++;
                                                                                    }
                                                                                    $qtdAgendGeral++;
                                                                                @endphp
                                                                                <div class="card">
                                                                                    <div class="card-header font-weight-bolder">
                                                                                        <b>Cliente: {{$agend->user->name}}</b> <br/>
                                                                                        E-mail: {{$agend->user->email}} <br/>
                                                                                        Telefone: {{$agend->user->telefone}}
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <p class="font-weight-bolder">
                                                                                            Serviço: {{$agend->servico->nome}} <br/>
                                                                                            Valor: {{ 'R$ '.number_format($agend->valor, 2, ',', '.')}} <br/>
                                                                                            <b>Funcionário: {{$agend->func->name}}</b>
                                                                                            @if($agend->observacao!="")
                                                                                            <div class="table-responsive">
                                                                                                <div class="text-nowrap">
                                                                                                    Observação: {{$agend->observacao}}
                                                                                                </div>
                                                                                            </div>
                                                                                            @endif
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="card-footer">
                                                                                        @if($agend->status=="CANCELADO")
                                                                                        <div class="alert alert-danger" role="alert">
                                                                                            CANCELADO
                                                                                        </div>
                                                                                        @else
                                                                                            @if($agend->status=="ATENDIDO")
                                                                                            <div class="alert alert-success" role="alert">
                                                                                                ATENDIDO
                                                                                            </div>
                                                                                            @else
                                                                                                <a href="/admin/agendamentos/atendido/{{$agend->id}}" class="badge bg-success" data-toggle="tooltip" data-placement="right" title="Marcar Como Atendido"><i class="material-icons md-18">event_available</i></a>
                                                                                                <a href="/admin/agendamentos/cancelar/{{$agend->id}}" class="badge bg-danger" data-toggle="tooltip" data-placement="right" title="Cancelar"><i class="material-icons md-18">event_busy</i></a>
                                                                                                @if($dataSemana<date("Y-m-d") || ($dataSemana==date("Y-m-d") && $hora<date("H:i:s"))) 
                                                                                                @else
                                                                                                <a href="/admin/agendamentos/editar/{{$agend->id}}" class="badge bg-warning" data-toggle="tooltip" data-placement="right" title="Editar"><i class="material-icons md-18">edit</i></a>
                                                                                                @endif
                                                                                            @endif
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                                <br/>
                                                                            @endif
                                                                        @endforeach
                                                                        @if($dataSemana<date("Y-m-d") || ($dataSemana==date("Y-m-d") && $hora<date("H:i:s")))
                                                                        @else
                                                                            @if($filtrados>0)
                                                                                <a href="/admin/agendamentos/" class="badge bg-primary">Limpar Filtro</a>
                                                                            @else
                                                                                <a href="/admin/agendamentos/novo/{{$dataSemana}}/{{$hora}}" class="badge bg-primary" data-toggle="tooltip" data-placement="right" title="Novo Agendamento"><i class="material-icons white md-12">more_time</i></a>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <!-- Modal -->
                                                    @if($dataSemana<date("Y-m-d") || ($dataSemana==date("Y-m-d") && $hora<date("H:i:s"))) 
                                                        @if($qtdAged<=0 && $cancelados==0)
                                                            <button type="button" class="btn btn-success btn-sm" disabled>
                                                                <span class="badge badge-pill badge-light">{{$qtdAged}}</span>
                                                            </button>
                                                        @else 
                                                            @if($qtdAged<=0 && $cancelados>0)
                                                                <button type="button" class="btn @if($qtdAged<=0 && $cancelados==0) btn-success @else @if($qtdAged<=0 && $cancelados>0) btn-cancel @else @if($qtdAged>=1 && $qtdAged<$totalFuncs) btn-warning @else btn-danger @endif @endif @endif btn-sm"  data-bs-toggle="modal" data-bs-target="#exampleModalQtd{{$i}}{{$j}}">
                                                                    <span class="badge badge-pill badge-light">{{$qtdAged}}</span>
                                                                </button>
                                                            @else 
                                                                @if($qtdAged>=1 && $qtdAged<$totalFuncs)
                                                                    <button type="button" class="btn btn-warning btn-sm"  data-bs-toggle="modal" data-bs-target="#exampleModalQtd{{$i}}{{$j}}">
                                                                        <span class="badge badge-pill badge-light">{{$qtdAged}}</span>
                                                                    </button>
                                                                @else
                                                                    <button type="button" class="btn btn-danger btn-sm"  data-bs-toggle="modal" data-bs-target="#exampleModalQtd{{$i}}{{$j}}">
                                                                        <span class="badge badge-pill badge-light">{{$qtdAged}}</span>
                                                                    </button>
                                                                @endif 
                                                            @endif 
                                                        @endif
                                                    @else
                                                        @if($qtdAged<=0 && $cancelados==0)
                                                            <button type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal" data-bs-target="#exampleModalQtd{{$i}}{{$j}}">
                                                                <span class="badge badge-pill badge-light">{{$qtdAged}}</span>
                                                            </button>
                                                        @else 
                                                            @if($qtdAged<=0 && $cancelados>0)
                                                                <button type="button" class="btn @if($qtdAged<=0 && $cancelados==0) btn-success @else @if($qtdAged<=0 && $cancelados>0) btn-cancel @else @if($qtdAged>=1 && $qtdAged<$totalFuncs) btn-warning @else btn-danger @endif @endif @endif btn-sm"  data-bs-toggle="modal" data-bs-target="#exampleModalQtd{{$i}}{{$j}}">
                                                                    <span class="badge badge-pill badge-light">{{$qtdAged}}</span>
                                                                </button>
                                                            @else 
                                                                @if($qtdAged>=1 && $qtdAged<$totalFuncs)
                                                                    <button type="button" class="btn btn-warning btn-sm"  data-bs-toggle="modal" data-bs-target="#exampleModalQtd{{$i}}{{$j}}">
                                                                        <span class="badge badge-pill badge-light">{{$qtdAged}}</span>
                                                                    </button>
                                                                @else
                                                                    <button type="button" class="btn btn-danger btn-sm"  data-bs-toggle="modal" data-bs-target="#exampleModalQtd{{$i}}{{$j}}">
                                                                        <span class="badge badge-pill badge-light">{{$qtdAged}}</span>
                                                                    </button>
                                                                @endif 
                                                            @endif 
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                        @endif
                                    @endif
                                @endforeach
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Configuração de Horários</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card border">
                            <div class="card-body">
                                <h6>*Para o dia fechado, mantenha 00:00 em todos horários.</h6>
                                <form action="/admin/agendamentos/config" method="POST">
                                    @csrf
                                    <div class="table-responsive">
                                        <table class="table table-bordered border-primary">
                                            <thead class="table-dark">
                                                <tr>
                                                    <td>Dia</td>
                                                    <td>Abertura</td>
                                                    <td>Intervalo (Ínicio)</td>
                                                    <td>Intervalo (Fim)</td>
                                                    <td>Fechamento</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($configs as $config)
                                                <tr>
                                                    <td>{{$config->diaSemana}}</td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="time" class="form-control" name="abertura{{$config->diaSemana}}" id="abertura{{$config->diaSemana}}" value="{{$config->abertura}}" required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="time" class="form-control" name="intervaloInicio{{$config->diaSemana}}" id="intervaloInicio{{$config->diaSemana}}" value="{{$config->intervaloInicio}}" required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="time" class="form-control" name="intervaloFim{{$config->diaSemana}}" id="intervaloFim{{$config->diaSemana}}" value="{{$config->intervaloFim}}" required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="time" class="form-control" name="fechamento{{$config->diaSemana}}" id="fechamento{{$config->diaSemana}}" value="{{$config->fechamento}}" required>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
@endsection