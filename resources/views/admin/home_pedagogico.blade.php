@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            <div class="card border border-primary mb-3" style="width: 20rem;">
                <div class="card-body">
                    <h5>Recados</h5>
                    <p class="card-text">
                        Gerar Campos e Consultar Recados
                    </p>
                    <a href="/admin/recados" class="btn btn-primary">Recados</a>
                </div>
            </div>
            <div class="card border border-primary mb-3" style="width: 20rem;">
                <div class="card-body">
                    <h5>Atividades</h5>
                    <p class="card-text">
                        Gerenciar as Atividades
                    </p>
                    <a href="/admin/atividade" class="btn btn-primary">Atividades</a>
                </div>
            </div>
            <div class="card border border-primary mb-3" style="width: 20rem;">
                <div class="card-body">
                    <h5>Ocorrências</h5>
                    <p class="card-text">
                        Consultar as Ocorrências
                    </p>
                    <a href="/admin/ocorrencias" class="btn btn-primary">Ocorrências</a>
                </div>
            </div>
            <div class="card border border-primary mb-3" style="width: 20rem;">
                <div class="card-body">
                    <h5>Conteúdos</h5>
                    <p class="card-text">
                        Gerar Campos e Consultar Conteúdos
                    </p>
                    <a href="/admin/conteudos/{{date("Y")}}" class="btn btn-primary">Conteúdos</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection