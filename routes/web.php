<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function() {
    Route::get('', 'AdminController@index')->name('admin.dashboard');
    Route::get('/novo', 'AdminController@create');
    Route::post('', 'AdminController@store');
    Route::get('/login', 'Auth\AdminLoginController@index')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');

    Route::get('/administrativo', function () {
        return view('admin.home_administrativo');
    })->middleware('auth:admin');
    Route::get('/estoque', function () {
        return view('admin.home_estoque');
    })->middleware('auth:admin');
    Route::get('/pedagogico', function () {
        return view('admin.home_pedagogico');
    })->middleware('auth:admin');

    Route::get('/templates/download/{nome}', 'AdminController@templates');

    
    Route::group(['prefix' => 'atividade'], function() {
        Route::get('/', 'AdminController@painelAtividades');
        Route::post('/', 'AdminController@novaAtividade');
        Route::get('/download/{id}', 'AdminController@downloadAtividade');
        Route::post('/editar/{id}', 'AdminController@editarAtividade');
        Route::get('/apagar/{id}', 'AdminController@apagarAtividade');
        Route::get('/filtro', 'AdminController@filtro_atividade');
    });

    
    Route::group(['prefix' => 'ocorrencias'], function() {
        Route::get('/', 'AdminController@indexOcorrencias');
        Route::get('/apagar/{id}', 'AdminController@apagarOcorrencia');
        Route::get('/filtro', 'AdminController@filtroOcorrencias');
    });

    
    Route::group(['prefix' => 'conteudos'], function() {
        Route::get('/{a}', 'AdminController@index_conteudos');
        Route::get('/', 'AdminController@index_conteudos_ano');
        Route::get('/painel/{a}/{b}/{t}', 'AdminController@painel_conteudo');
        Route::post('/gerar', 'AdminController@gerar_conteudo');
        Route::post('/anexar/{id}', 'AdminController@anexar_conteudo');
        Route::get('/download/{id}', 'AdminController@download_conteudo');
        Route::get('/apagar/{id}', 'AdminController@apagar_conteudo');
    });

});


Route::group(['prefix' => 'aluno'], function() {
    Route::get('/login', 'Auth\AlunoLoginController@index')->name('aluno.login');
    Route::post('/login', 'Auth\AlunoLoginController@login')->name('aluno.login.submit');
    Route::get('/', 'AlunoController@index')->name('aluno.dashboard')->middleware('auth:aluno');

    Route::get('/consulta', 'AlunoController@consulta')->middleware('auth:admin');
    Route::post('/', 'AlunoController@store')->middleware('auth:admin');
    Route::get('/filtro', 'AlunoController@filtro')->middleware('auth:admin');
    Route::post('/editar/{id}', 'AlunoController@update')->middleware('auth:admin');
    Route::get('/apagar/{id}', 'AlunoController@destroy')->middleware('auth:admin');
    Route::post('/file', 'AlunoController@file')->middleware('auth:admin');

    
    Route::group(['prefix' => 'atividade'], function() {
        Route::get('/disciplinas', 'AlunoController@disciplinas')->middleware('auth:aluno');
        Route::get('/{d}', 'AlunoController@painelAtividades')->middleware('auth:aluno');
        Route::get('/download/{id}', 'AlunoController@downloadAtividade')->middleware('auth:aluno');
        Route::post('/retorno/{id}', 'AlunoController@retornoAtividade')->middleware('auth:aluno');
        Route::post('/retorno/editar/{id}', 'AlunoController@editarRetornoAtividade')->middleware('auth:aluno');
    });
    
});


Route::get('/prof/consulta', 'ProfController@consultaProf')->middleware('auth:admin');
Route::post('/prof', 'ProfController@novoProf')->middleware('auth:admin');
Route::get('/prof/filtro', 'ProfController@filtroProf')->middleware('auth:admin');
Route::post('/prof/editar/{id}', 'ProfController@editarProf')->middleware('auth:admin');
Route::get('/prof/apagar/{id}', 'ProfController@apagarProf')->middleware('auth:admin');
Route::get('/prof/apagarDisciplina/{p}/{d}', 'ProfController@apagarDisciplina')->middleware('auth:admin');
Route::get('/prof/login', 'Auth\ProfLoginController@index')->name('prof.login');
Route::post('/prof/login', 'Auth\ProfLoginController@login')->name('prof.login.submit');
Route::get('/prof', 'ProfController@index')->name('prof.dashboard')->middleware('auth:prof');
Route::get('/prof/disciplinasAtividades', 'ProfController@disciplinasAtividades')->middleware('auth:prof');
Route::get('/prof/atividade/{id}', 'ProfController@painelAtividades')->middleware('auth:prof');
Route::post('/prof/atividade', 'ProfController@novaAtividade')->middleware('auth:prof');
Route::get('/prof/atividade/download/{id}', 'ProfController@downloadAtividade')->middleware('auth:prof');
Route::post('/prof/atividade/editar/{id}', 'ProfController@editarAtividade')->middleware('auth:prof');
Route::get('/prof/atividade/apagar/{id}', 'ProfController@apagarAtividade')->middleware('auth:prof');
Route::get('/prof/atividade/filtro/{id}', 'ProfController@filtroAtividade')->middleware('auth:prof');
Route::get('/prof/atividade/retornos/{id}', 'ProfController@retornos')->middleware('auth:prof');
Route::get('/prof/atividade/retorno/download/{id}', 'ProfController@downloadRetorno')->middleware('auth:prof');
Route::get('/prof/listaAtividade', function () {
    return view('profs.home_las');
})->middleware('auth:prof');
Route::get('/prof/listaAtividade/painel/{data}', 'ProfController@painelListaAtividades')->middleware('auth:prof');
Route::post('/prof/listaAtividade/anexar/{id}', 'ProfController@anexarListaAtividade')->middleware('auth:prof');
Route::get('/prof/listaAtividade/download/{id}', 'ProfController@downloadListaAtividade')->middleware('auth:prof');
Route::get('/prof/listaAtividade/apagar/{id}', 'ProfController@apagarListaAtividade')->middleware('auth:prof');
Route::get('/prof/ocorrencias', 'ProfController@disciplinasOcorrencias')->middleware('auth:prof');
Route::get('/prof/ocorrencias/{id}', 'ProfController@turmasOcorrencias')->middleware('auth:prof');
Route::get('/prof/ocorrencias/{disc}/{turma}', 'ProfController@indexOcorrencias')->middleware('auth:prof');
Route::post('/prof/ocorrencias', 'ProfController@novasOcorrencias')->middleware('auth:prof');
Route::post('/prof/ocorrencias/editar/{id}', 'ProfController@editarOcorrencia')->middleware('auth:prof');
Route::get('/prof/ocorrencias/apagar/{disc}/{turma}/{id}', 'ProfController@apagarOcorrencia')->middleware('auth:prof');
Route::get('/prof/ocorrencias/filtro/{disc}/{turma}', 'ProfController@filtroOcorrencias')->middleware('auth:prof');
Route::get('/prof/conteudos', 'ProfController@painelConteudosAno')->middleware('auth:prof');
Route::get('/prof/conteudos/{a}', 'ProfController@painelConteudos')->middleware('auth:prof');
Route::get('/prof/conteudos/painel/{a}/{b}/{t}', 'ProfController@conteudos')->middleware('auth:prof');
Route::post('/prof/conteudos/anexar/{id}', 'ProfController@anexarConteudo')->middleware('auth:prof');
Route::get('/prof/conteudos/download/{id}', 'ProfController@downloadConteudo')->middleware('auth:prof');
Route::get('/prof/conteudos/apagar/{id}', 'ProfController@apagarConteudo')->middleware('auth:prof');

Route::get('/outro/login', 'Auth\OutroLoginController@index')->name('outro.login');
Route::post('/outro/login', 'Auth\OutroLoginController@login')->name('outro.login.submit');
Route::get('/outro', 'OutroController@index')->name('outro.dashboard')->middleware('auth:outro');
Route::get('/outro/novo', 'OutroController@create')->middleware('auth:outro');
Route::post('/outro', 'OutroController@store')->middleware('auth:admin');
Route::get('/outro/consulta', 'OutroController@consulta')->middleware('auth:admin');
Route::get('/outro/filtro', 'OutroController@filtro')->middleware('auth:admin');
Route::post('/outro/editar/{id}', 'OutroController@update')->middleware('auth:admin');
Route::get('/outro/apagar/{id}', 'OutroController@destroy')->middleware('auth:admin');
Route::post('/outro/file', 'OutroController@file')->middleware('auth:admin');

Route::get('/produtos', 'ProdutoController@index');
Route::post('/produtos', 'ProdutoController@store');
Route::post('/produtos/editar/{id}', 'ProdutoController@update');
Route::get('/produtos/apagar/{id}', 'ProdutoController@destroy');
Route::get('/produtos/filtro', 'ProdutoController@filtro');
Route::get('/produtos/pdf', 'ProdutoController@gerarPdf');

Route::get('/categorias', 'CategoriaController@index');
Route::post('/categorias', 'CategoriaController@store');
Route::post('/categorias/editar/{id}', 'CategoriaController@update');
Route::get('/categorias/apagar/{id}', 'CategoriaController@destroy');


Route::get('/entradaSaida', 'EntradaSaidaController@index');
Route::post('/entradaSaida', 'EntradaSaidaController@store');
Route::get('/entradaSaida/filtro_entradaSaida', 'EntradaSaidaController@filtro_entradaSaidaRel');
Route::get('/entradaSaida/pdf', 'EntradaSaidaController@gerarPdf');

Route::get('/listaCompras', 'ListaCompraController@index');
Route::post('/listaCompras', 'ListaCompraController@store');
Route::get('/listaCompras/pdf/{id}', 'ListaCompraController@gerarPdf');

Route::get('/disciplinas', 'DisciplinaController@index');
Route::post('/disciplinas', 'DisciplinaController@store');
Route::get('/disciplinas/apagar/{id}', 'DisciplinaController@destroy');

Route::get('/turmas', 'TurmaController@index');
Route::post('/turmas', 'TurmaController@store');
Route::get('/turmas/apagar/{id}', 'TurmaController@destroy');

Route::get('/turmasDiscs', 'TurmaDisciplinaController@index');
Route::post('/turmasDiscs', 'TurmaDisciplinaController@store');
Route::get('/turmasDiscs/apagar/{t}/{d}', 'TurmaDisciplinaController@destroy');

Route::get('/tiposOcorrencias', 'AdminController@tipoOcorrencia');
Route::post('/tiposOcorrencias', 'AdminController@tipoOcorrenciaNovo');
Route::post('/tiposOcorrencias/editar/{id}', 'AdminController@tipoOcorrenciaEdit');
Route::get('/tiposOcorrencias/apagar/{id}', 'AdminController@tipoOcorrenciaDelete');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
