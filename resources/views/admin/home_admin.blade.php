@extends('layouts.app', ["current"=>"home"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Administrativo</h5>
                    <p class="card-text">
                        Consultar e cadastrar Estoque, Acessos, ... 
                    </p>
                    <a href="/admin/administrativo" class="btn btn-primary">Administrativo</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Pedagógico</h5>
                    <p class="card-text">
                        Consultar e cadastrar Atividades, Ocorrências, ...
                    </p>
                    <a href="/admin/pedagogico" class="btn btn-primary">Pedagógico</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
