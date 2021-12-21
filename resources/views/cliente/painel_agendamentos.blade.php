@extends('layouts.app', ["current"=>"agendamentos"])

@section('body')
@php
	$page = "Agendamentos";
@endphp
<div class="card border">
    <div class="card-body">
        <a href="/agendamentos" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
        <br/><br/>
        <h5 class="card-title">Agendamentos - Serviço: {{$serv->nome}} @if($func!="") - Funcionário: {{$func->name}} @endif</h5>
            @if(session('mensagem'))
                <div class="alert @if(session('type')=="success") alert-success @else @if(session('type')=="warning") alert-warning @else @if(session('type')=="danger") alert-danger @else alert-info @endif @endif @endif alert-dismissible fade show" role="alert">
                    {{session('mensagem')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
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
                                        $agendClient = false;
                                        $agendFunc = false;
                                        $qtdAged = 0;
                                        $cancelados= 0;
                                        $qtdAgedGeral = 0;
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
                                                                                        if($agend->servico->id == $serv->id){
                                                                                            if($agend->user->id==Auth::user()->id){
                                                                                                $agendClient = true;
                                                                                            }
                                                                                            if($agend->status!="CANCELADO"){
                                                                                                $qtdAged++;
                                                                                            } else {
                                                                                                $cancelados++;
                                                                                            }
                                                                                            $qtdAgedGeral++;
                                                                                        }
                                                                                        if($func!=""){
                                                                                            if($agend->func->id==$func->id){
                                                                                                $agendFunc = true;
                                                                                            }
                                                                                        }
                                                                                    @endphp
                                                                                    @if($agendClient)
                                                                                    <div class="card">
                                                                                        <div class="card-header font-weight-bolder">
                                                                                            Serviço: {{$agend->servico->nome}} <br/>
                                                                                            Valor: {{ 'R$ '.number_format($agend->valor, 2, ',', '.')}} <br/>
                                                                                        </div>
                                                                                        <div class="card-body">
                                                                                            <p class="font-weight-bolder">
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
                                                                                                <a href="/agendamentos/cancelar/{{$agend->id}}" class="badge bg-danger" data-toggle="tooltip" data-placement="right" title="Cancelar"><i class="material-icons md-18">event_busy</i></a>
                                                                                                @endif
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                    @endif
                                                                                    <br/>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <!-- Modal -->
                                                        
                                                        @if($dataSemana<date("Y-m-d") || ($dataSemana==date("Y-m-d") && $hora<date("H:i:s")))
                                                            @if($qtdAgedGeral>0)
                                                                @if($agendClient)
                                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModalQtd{{$i}}{{$j}}" class="btn btn-primary btn-sm">
                                                                        <i class="material-icons black md-12">event_available</i> 
                                                                    </button>
                                                                @else
                                                                    <button type="button" class="btn btn-warning btn-sm" disabled>
                                                                        <i class="material-icons black md-12">event_available</i> 
                                                                    </button>
                                                                @endif
                                                            @else
                                                                <button type="button" class="btn btn-success btn-sm" disabled >
                                                                    <i class="material-icons black md-12">event</i> 
                                                                </button>
                                                            @endif
                                                        @else
                                                            @if($qtdAged==0 && $agendFunc==0)
                                                                <a type="button" @if($func!="") href="/agendamentos/novo/{{$dataSemana}}/{{$hora}}/{{$serv->id}}/{{$func->id}}" @else href="/agendamentos/novo/{{$dataSemana}}/{{$hora}}/{{$serv->id}}" @endif class="btn btn-success btn-sm">
                                                                    <i class="material-icons black md-12">event</i> 
                                                                </a>
                                                            @else
                                                                @if($agendClient)
                                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModalQtd{{$i}}{{$j}}" class="btn btn-primary btn-sm">
                                                                        <i class="material-icons black md-12">event_available</i> 
                                                                    </button>
                                                                @else
                                                                    @if($agendFunc)
                                                                        <button type="button" class="btn btn-warning btn-sm" disabled>
                                                                            <i class="material-icons black md-12">event_available</i> 
                                                                        </button>
                                                                    @else
                                                                        @if($qtdAged>=1 && $qtdAged<$totalFuncs)
                                                                            <a type="button" @if($func!="") href="/agendamentos/novo/{{$dataSemana}}/{{$hora}}/{{$serv->id}}/{{$func->id}}" @else href="/agendamentos/novo/{{$dataSemana}}/{{$hora}}/{{$serv->id}}" @endif class="btn btn-success btn-sm">
                                                                                <i class="material-icons black md-12">event</i> 
                                                                            </a>
                                                                        @else
                                                                            <button type="button" class="btn btn-warning btn-sm" disabled>
                                                                                <i class="material-icons black md-12">event_available</i> 
                                                                            </button>
                                                                        @endif
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
@endsection