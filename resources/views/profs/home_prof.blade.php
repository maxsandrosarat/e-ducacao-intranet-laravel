@extends('layouts.app', ["current"=>"home"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            <div class="card border border-primary mb-3" style="width: 20rem;">
                <div class="card-body">
                    <h5>Atividades</h5>
                    <p class="card-text">
                        Gerenciar as Atividades
                    </p>
                    <a href="/prof/disciplinasAtividades" class="btn btn-primary">Atividades</a>
                </div>
            </div>
            <div class="card border border-primary mb-3" style="width: 20rem;">
                <div class="card-body">
                    <h5>Ocorrências</h5>
                    <p class="card-text">
                        Lançar as Ocorrências
                    </p>
                    <a href="/prof/ocorrencias" class="btn btn-primary">Ocorrências</a>
                </div>
            </div>
            <div class="card border border-primary mb-3" style="width: 20rem;">
                <div class="card-body">
                    <h5>Conteúdos</h5>
                    <p class="card-text">
                        Anexar e baixar os conteúdos
                    </p>
                    <a href="/prof/conteudos/{{date("Y")}}" class="btn btn-primary">Conteúdos</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
