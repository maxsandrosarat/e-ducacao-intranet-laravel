@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Painel de Conteúdos - Ensino Fundamental - {{$tipo}} - {{$bim}}º Bimestre</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover" style="text-align: center;">
                <thead class="thead-dark">
                    <tr>
                        <th>Disciplinas</th>
                        @foreach ($fundTurmas as $turma)
                        <th>{{$turma->serie}}º ANO</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fundDiscs as $fundDisc)
                    <tr>
                        <td>{{$fundDisc->nome}}</td>
                        @foreach ($fundTurmas as $turma)
                            @foreach ($contFunds as $contFund)
                                @if($contFund->disciplina->nome == $fundDisc->nome && $contFund->serie==$turma->serie)
                                    @if($contFund->arquivo=='') <td style="color:red; text-align: center;"> Pendente <br/> Solicite @else <td style="color:green; text-align: center;"><i class="material-icons md-48 green600">check_circle</i><br/> <a type="button" class="btn btn-success" href="/admin/conteudos/download/{{$contFund->id}}"><i class="material-icons md-48">cloud_download</i></a> @endif
                                @endif
                            @endforeach
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        <div class="card-body">
            <h5 class="card-title">Painel de Conteúdos - Ensino Médio - {{$tipo}} - {{$bim}}º Bimestre</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover" style="text-align: center;">
                <thead class="thead-dark">
                    <tr>
                        <th>Disciplinas</th>
                        @foreach ($medioTurmas as $turma)
                        <th>{{$turma->serie}}º ANO</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medioDiscs as $medioDisc)
                    <tr>
                        <td>{{$medioDisc->nome}}</td>
                        @foreach ($medioTurmas as $turma)
                            @foreach ($contMedios as $contMedio)
                                @if($contMedio->disciplina->nome == $medioDisc->nome && $contMedio->serie==$turma->serie)
                                    @if($contMedio->arquivo=='') <td style="color:red; text-align: center;"> Pendente <br/> Solicite @else <td style="color:green; text-align: center;"><i class="material-icons md-48 green600">check_circle</i><br/> <a type="button" class="btn btn-success" href="/admin/conteudos/download/{{$contMedio->id}}"><i class="material-icons md-48">cloud_download</i></a> @endif
                                @endif        
                            @endforeach
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <br>
    <a href="/admin/conteudos/{{$ano}}" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
