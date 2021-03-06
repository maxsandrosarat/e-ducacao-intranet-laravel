@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
    <div class="card border">
        <div class="card-body">
            @if(session('mensagem'))
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <p>{{session('mensagem')}}</p>
                </div>
            @endif
            <form action="/admin/conteudos" method="GET">
                @csrf
                <label for="ano">Selecione o ano: 
                <select class="custom-select" id="ano" name="ano">
                    <option value="">Selecionar ano</option>
                    @foreach ($anos as $an)
                    <option value="{{$an->ano}}">{{$an->ano}}</option>
                    @endforeach
                  </select></label>
                <input type="submit" class="btn btn-primary" value="Selecionar">
            </form>
            <h5 class="card-title">Conteúdos pelos Professores - {{$ano}}</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr style="text-align: center;">
                        <th>Bimestre</th>
                        <th>Avaliação Parcial - AP</th>
                        <th>Prova Bimestral - PB</th>
                        <th>Simulado - SIM</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">1º</td>
                        <td><a href="/admin/conteudos/painel/{{$ano}}/1/AP" class="btn btn-primary">AP</a></td>
                        <td><a href="/admin/conteudos/painel/{{$ano}}/1/PB" class="btn btn-primary">PB</a></td>
                        <td><a href="/admin/conteudos/painel/{{$ano}}/1/SIM" class="btn btn-primary">SIM</a></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">2º</td>
                        <td><a href="/admin/conteudos/painel/{{$ano}}/2/AP" class="btn btn-primary">AP</a></td>
                        <td><a href="/admin/conteudos/painel/{{$ano}}/2/PB" class="btn btn-primary">PB</a></td>
                        <td><a href="/admin/conteudos/painel/{{$ano}}/2/SIM" class="btn btn-primary">SIM</a></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">3º</td>
                        <td><a href="/admin/conteudos/painel/{{$ano}}/3/AP" class="btn btn-primary">AP</a></td>
                        <td><a href="/admin/conteudos/painel/{{$ano}}/3/PB" class="btn btn-primary">PB</a></td>
                        <td><a href="/admin/conteudos/painel/{{$ano}}/3/SIM" class="btn btn-primary">SIM</a></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">4º</td>
                        <td><a href="/admin/conteudos/painel/{{$ano}}/4/AP" class="btn btn-primary">AP</a></td>
                        <td><a href="/admin/conteudos/painel/{{$ano}}/4/PB" class="btn btn-primary">PB</a></td>
                        <td><a href="/admin/conteudos/painel/{{$ano}}/4/SIM" class="btn btn-primary">SIM</a></td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <br/>
    <!-- Button trigger modal -->
    <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Gerar Conteúdos">
        <i class="material-icons blue md-60">add_circle</i>
    </a>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Gerar Conteúdo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="/admin/conteudos/gerar" method="POST">
            @csrf
            <div class="form-group">
                <label for="ano">Ano: </label>
                <input class="form-control" type="number" name="ano" id="ano" size="4"  required>
                <label for="bimestre">Bimestre: </label>
                <select class="custom-select" id="bimestre" name="bimestre" required>
                    <option value="">Selecione</option>
                    <option value="1">1º</option>
                    <option value="2">2º</option>
                    <option value="3">3º</option>
                    <option value="4">4º</option>
                </select>
                <br/><br/>
                <input type="checkbox" id="AP" name="tipos[]" value="AP" checked>
                <label for="AP">AP</label>
                <input type="checkbox" id="PB" name="tipos[]" value="PB" checked>
                <label for="PB">PB</label>
                <input type="checkbox" id="SIM" name="tipos[]" value="SIM" checked>
                <label for="SIM">SIM</label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Gerar</button>
        </div>
            </form>
        </div>
    </div>
    </div>

    <a href="/admin/pedagogico" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection