<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>E-ducAção</title>
        <link rel="shortcut icon" href="/storage/logos/favicon.png"/>
        
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
                font-weight: bold;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth("web")
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        @auth("admin")
                            <a href="{{ url('/admin') }}">Home</a>
                        @else
                            @auth("aluno")
                                <a href="{{ url('/aluno') }}">Home</a>
                            @else
                                @auth("prof")
                                    <a href="{{ url('/prof') }}">Home</a>
                                @else
                                    <!--@if (Route::has('register'))
                                        <a href="{{ route('register') }}">Cadastre-se</a>
                                    @endif-->
                                        <h4>FAÇA LOGIN COMO</h4>
                                        <h4><a href="{{ route('responsavel.login') }}" class="badge badge-dark">RESPONSÁVEL</a>
                                        <a href="{{ route('aluno.login') }}" class="badge badge-dark">ALUNO</a>
                                        <a href="{{ route('outro.login') }}" class="badge badge-dark">COLABORADOR</a>
                                        <!--<a href="{{ route('login') }}" class="badge badge-dark">USUÁRIO</a>-->
                                @endauth
                            @endauth
                        @endauth
                    @endauth
                </div>
            @endif

            <div class="content">
                <h3>Seja Bem-vindo! Ao Sistema</h3>
                <br/>
                <div class="title m-b-md">
                    <img src="/storage/logos/logo.svg" alt="logo_escola" width="20%">
                </div>
                @auth("web")
                    <b> <h3>Você está logado como  {{Auth::user()->name}}  !</h3>
                @else
                    @auth("admin")
                        <b> <h3>Você está logado como  {{Auth::guard('admin')->user()->name}}  !</h3>
                    @else
                        @auth("aluno")
                            <b> <h3>Você está logado como  {{Auth::guard('aluno')->user()->name}}  !</h3>
                        @else
                            @auth("prof")
                                <b> <h3>Você está logado como  {{Auth::guard('prof')->user()->name}}  !</h3>
                            @else
                                <h3>Você não está logado! <br/> Por gentileza faça login!</h3>
                            @endauth
                        @endauth
                    @endauth
                @endauth
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>
