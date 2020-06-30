@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Painel de Atividades</h5>
            @if(count($atividades)==0)
                    <div class="alert alert-danger" role="alert">
                        @if($tipo=="painel")
                        Sem atividades cadastradas!
                        @endif
                        @if($tipo=="filtro")
                        Sem resultados da busca!
                        <a href="/admin/atividade" class="btn btn-success">Voltar</a>
                        @endif
                    </div>
            @else
            <div class="card">
                <div class="card border">
                    <h5 class="card-title">Filtros:</h5>
                    <form class="form-inline my-2 my-lg-0" method="GET" action="/admin/atividade/filtro">
                        @csrf
                        <label for="turma">Turma</label>
                            <select class="custom-select" id="turma" name="turma">
                                <option value="">Selecione</option>
                                @foreach ($turmas as $turma)
                                <option value="{{$turma->id}}">{{$turma->serie}}º ANO {{$turma->turma}} (@if($turma->turno=='M') Matutino @else @if($turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                @endforeach
                            </select>
                        <label for="descricao">Descrição Atividade</label>
                            <input class="form-control mr-sm-2" type="text" placeholder="Digite a descrição" name="descricao">
                        <label for="data">Data Criação</label>
                            <input class="form-control mr-sm-2" type="date" name="data">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                    </form>
                </div>
            </div>
            <h5>Exibindo {{$atividades->count()}} de {{$atividades->total()}} de Atividades ({{$atividades->firstItem()}} a {{$atividades->lastItem()}})</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover" style="text-align: center;">
                <thead class="thead-dark">
                    <tr>
                        <th>Turmas</th>
                        <th>Disciplinas</th>
                        <th width="850">Atividade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($atividades as $atividade)
                    <tr>
                        <td>{{$atividade->turma->serie}}º ANO {{$atividade->turma->turma}}</td>
                        <td>{{$atividade->disciplina->nome}}</td>
                        <td style="text-align: center;" width="850">
                            <div class="card">
                                <div class="card-header">
                                    {{$atividade->descricao}}
                                </div>
                                <div class="card-body">
                                  <p class="card-text">Visualizações: {{$atividade->visualizacoes}} | Data Criação: {{date("d/m/Y H:i", strtotime($atividade->created_at))}} @if($atividade->data_publicacao!="") | Data Publicação: {{date("d/m/Y H:i", strtotime($atividade->data_publicacao))}} @endif @if($atividade->data_expiracao!="") | Data Expiração: {{date("d/m/Y H:i", strtotime($atividade->data_expiracao))}}@endif</p>
                                  <a href="{{$atividade->link}}" target="_blank" class="btn btn-primary">Link Videoaula</a>
                                  <a type="button" class="btn btn-success" href="/admin/atividade/download/{{$atividade->id}}"><i class="material-icons md-48">cloud_download</i></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                {{$atividades->links() }}
            </div>
            </div>
            @endif
        </div>
    </div>
    <br>
    <a href="/admin/pedagogico" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection