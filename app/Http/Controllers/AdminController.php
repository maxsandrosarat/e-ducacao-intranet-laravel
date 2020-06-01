<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use App\Disciplina;
use App\Turma;
use App\Atividade;
use App\Aluno;
use App\TipoOcorrencia;
use App\Ocorrencia;
use App\Conteudo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\Input;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function index(){
        return view('admin.home_admin');
    }

    public function create()
    {
        return view('auth.admin-register');
    }

    public function store(Request $request)
    {
        $adm = new Admin();
        $adm->name = $request->input('name');
        $adm->email = $request->input('email');
        $adm->password = Hash::make($request->input('password'));
        $adm->save();
        return redirect('/admin');
    }

    //PROF
    public function painelAtividades(){
        $discs = Disciplina::orderBy('nome')->get();
        $turmas = Turma::all();
        $atividades = Atividade::orderBy('id','desc')->paginate(10);
        $tipo = "painel";
        return view('admin.atividade_admin', compact('discs','turmas','atividades','tipo'));
    }

    //PROF
    public function editarAtividade(Request $request, $id)
    {
        $atividade = Atividade::find($id);
        if($request->file('arquivo')!=""){
            $arquivo = $atividade->arquivo;
            Storage::disk('public')->delete($arquivo);
            $path = $request->file('arquivo')->store('atividades','public');
        } else {
            $path = "";
        }
        if($request->input('turma')!=""){
            $atividade->turma_id = $request->input('turma');
        }
        if($request->input('dataPublicacao')!=""){
            $atividade->data_publicacao = $request->input('dataPublicacao');
        }
        if($request->input('dataExpiracao')!=""){
            $atividade->data_expiracao = $request->input('dataExpiracao');
        }
        if($request->input('descricao')!=""){
            $atividade->descricao = $request->input('descricao');
        }
        if($request->input('link')!=""){
            $atividade->link = $request->input('link');
        }
        if($path!=""){
            $atividade->arquivo = $path;
        }
        $atividade->save();
        
        return redirect('/admin/atividade');
    }

    //PROF
    public function downloadAtividade($id)
    {
        $atividade = Atividade::find($id);
        $disc = Disciplina::find($atividade->disciplina_id);
        $turma = Turma::find($atividade->turma_id);
        $nameFile = $turma->serie."º - Atividade ".$atividade->descricao." - ".$disc->nome;
        if(isset($atividade)){
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($atividade->arquivo);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = $nameFile.".".$extension;
            return response()->download($path, $name);
        }
        return redirect('/admin/atividade');
    }

    //PROF
    public function apagarAtividade($id){
        $atividade = Atividade::find($id);
        $arquivo = $atividade->arquivo;
        Storage::disk('public')->delete($arquivo);
        if(isset($atividade)){
            $atividade->delete();
        }
        return redirect('/admin/atividade');
    }

    //PROF
    public function filtro_atividade(Request $request)
    {
        $turma = $request->input('turma');
        $descricao = $request->input('descricao');
        $data = $request->input('data');
        if(isset($turma)){
            if(isset($descricao)){
                if(isset($data)){
                    $atividades = Atividade::where('descricao','like',"%$descricao%")->where('turma_id',"$turma")->where('data_criacao',"$data")->orderBy('id','desc')->get();
                } else {
                    $atividades = Atividade::where('descricao','like',"%$descricao%")->where('turma_id',"$turma")->orderBy('id','desc')->get();
                }
            } else {
                $atividades = Atividade::where('turma_id',"$turma")->orderBy('id','desc')->get();
            }
        } else {
            if(isset($descricao)){
                if(isset($data)){
                    $atividades = Atividade::where('descricao','like',"%$descricao%")->where('data_criacao',"$data")->orderBy('id','desc')->get();
                } else {
                    $atividades = Atividade::where('descricao','like',"%$descricao%")->orderBy('id','desc')->get();
                }
            } else {
                if(isset($data)){
                    $atividades = Atividade::where('data_criacao',"$data")->orderBy('id','desc')->get();
                } else {
                    $atividades = Atividade::orderBy('id','desc')->get();
                }
            }
        }
        $discs = Disciplina::orderBy('nome')->get();
        $turmas = Turma::all();
        $tipo = "filtro";
        return view('admin.atividade_admin', compact('discs','turmas','atividades','tipo'));
    }

    public function tipoOcorrencia()
    {
        $tipos = TipoOcorrencia::all();
        return view('admin.tipo_ocorrencia',compact('tipos'));
    }

    public function tipoOcorrenciaNovo(Request $request)
    {
        $tipo = new TipoOcorrencia();
        $tipo->codigo = $request->input('codigo');
        $tipo->descricao = $request->input('descricao');
        $tipo->tipo = $request->input('tipo');
        $tipo->pontuacao = $request->input('pontuacao');
        $tipo->save();
        return redirect('/tiposOcorrencias');
    }

    public function tipoOcorrenciaEdit(Request $request, $id)
    {
        $tipo = TipoOcorrencia::find($id);
        if(isset($tipo)){
            if($request->input('codigo')!=""){
                $tipo->codigo = $request->input('codigo');
            }
            if($request->input('descricao')!=""){
                $tipo->descricao = $request->input('descricao');
            }
            if($request->input('tipo')!=""){
                $tipo->tipo = $request->input('tipo');
            }
            if($request->input('pontuacao')!=""){
                $tipo->pontuacao = $request->input('pontuacao');
            }
            $tipo->save();
        }
        return redirect('/tiposOcorrencias');
    }

    public function tipoOcorrenciaDelete($id)
    {
        $tipo = TipoOcorrencia::find($id);
        if(isset($tipo)){
            $tipo->delete();
        }
        return redirect('/tiposOcorrencias');
    }

    public function indexOcorrencias(){
        $alunos = Aluno::all();
        $tipos = TipoOcorrencia::all();
        $ocorrencias = Ocorrencia::paginate(10);
        $busca = "nao";
        return view('admin.ocorrencias_admin', compact('alunos','tipos','ocorrencias','busca'));
    }

    public function filtroOcorrencias(Request $request)
    {
        $tipo = $request->input('tipo');
        $aluno = $request->input('aluno');
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        if(isset($tipo)){
            if(isset($aluno)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", "$dataFim"])->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", date("Y/m/d")])->get();
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->whereBetween('data',["", "$dataFim"])->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->get();
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->whereBetween('data',["$dataInicio", "$dataFim"])->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->whereBetween('data',["$dataInicio", date("Y/m/d")])->get();
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->whereBetween('data',["", "$dataFim"])->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->get();
                    }
                }
            }
        } else {
            if(isset($aluno)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", "$dataFim"])->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", date("Y/m/d")])->get();
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->whereBetween('data',["", "$dataFim"])->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->get();
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::whereBetween('data',["$dataInicio", "$dataFim"])->get();
                    } else {
                        $ocorrencias = Ocorrencia::whereBetween('data',["$dataInicio", date("Y/m/d")])->get();
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::whereBetween('data',["", "$dataFim"])->get();
                    } else {
                        return back();
                    }
                }
            }
        }
        $alunos = Aluno::all();
        $tipos = TipoOcorrencia::all();
        $busca = "sim";
        return view('admin.ocorrencias_admin', compact('alunos','tipos','ocorrencias','busca'));
    }

    public function apagarOcorrencia($id)
    {
        $ocorrencia = Ocorrencia::find($id);
        if(isset($ocorrencia)){
            $ocorrencia->delete();
        }
        return back();
    }

    public function index_conteudos_ano(Request $request){
        $ano = $request->input('ano');
        $anos = DB::table('conteudos')->select(DB::raw("ano"))->groupBy('ano')->get();
        return view('admin.home_conteudos',compact('ano','anos'));
    }

    public function index_conteudos($ano){
        if($ano==""){
            $ano = date("Y");
        }
        $anos = DB::table('conteudos')->select(DB::raw("ano"))->groupBy('ano')->get();
        return view('admin.home_conteudos',compact('ano','anos'));
    }

    public function painel_conteudo($ano, $bim, $tipo){
        $validador = Conteudo::where('tipo', "$tipo")->where('bimestre',"$bim")->where('ano',"$ano")->count();
        if($validador==0){
            return back()->with('mensagem', 'Os campos para anexar os Conteúdos não foram gerados, por favor gerar!');
        } else {
            $fundTurmas = Turma::where('turma','A')->where('ensino','fund')->get();
            $medioTurmas = Turma::where('turma','A')->where('ensino','medio')->get();
            $fundDiscs = Disciplina::where('ensino','fund')->get();
            $medioDiscs = Disciplina::where('ensino','medio')->get();
            $contFunds = Conteudo::orderBy('disciplina_id')->where('tipo', "$tipo")->where('bimestre',"$bim")->where('ensino','fund')->where('ano',"$ano")->get();
            $contMedios = Conteudo::orderBy('disciplina_id')->where('tipo', "$tipo")->where('bimestre',"$bim")->where('ensino','medio')->where('ano',"$ano")->get();
            return view('admin.conteudos',compact('tipo','bim','fundTurmas','medioTurmas','fundDiscs','medioDiscs','contFunds','contMedios','ano'));
        }
    }

    public function gerar_conteudo(Request $request){
        $tipos = $request->input('tipos');
        $ano = $request->input('ano');
        $bimestre = $request->input('bimestre');
        $discs = Disciplina::all();
        $turmas = Turma::where('turma','A')->get();
        foreach($tipos as $tipo){
            foreach($turmas as $turma){
                $serie = $turma->serie;
                $ensino = $turma->ensino;
                    foreach($discs as $disc){
                        if($disc->ensino=="fund" && $ensino=="fund"){
                            $validador = Conteudo::where('tipo',"$tipo")->where('bimestre', "$bimestre")->where('ano', "$ano")->where('serie', "$serie")->where('ensino', 'fund')->where('disciplina_id', "$disc->id")->count();
                            if($validador == 0){
                                $cont = new Conteudo();
                                $cont->tipo = $tipo;
                                $cont->bimestre = $bimestre;
                                $cont->ano = $ano;
                                $cont->serie = $serie;
                                $cont->ensino = "fund";
                                $cont->disciplina_id = $disc->id;
                                $cont->save();
                            }
                        } else if($disc->ensino=="medio" && $ensino=="medio"){
                            $validador = Conteudo::where('tipo',"$tipo")->where('bimestre', "$bimestre")->where('ano', "$ano")->where('serie', "$serie")->where('ensino', 'medio')->where('disciplina_id', "$disc->id")->count();
                            if($validador == 0){
                                $cont = new Conteudo();
                                $cont->tipo = $tipo;
                                $cont->bimestre = $bimestre;
                                $cont->ano = $ano;
                                $cont->serie = $serie;
                                $cont->ensino = "medio";
                                $cont->disciplina_id = $disc->id;
                                $cont->save();
                            }
                        }
                    }
            }
        }
        return back()->with('mensagem', 'Conteúdos gerados com sucesso!');
    }

    public function anexar_conteudo(Request $request, $id)
    {
        $path = $request->file('arquivo')->store('conteudos','public');
        $cont = Conteudo::find($id);
        if($cont->arquivo=="" || $cont->arquivo==null){
            $cont->arquivo = $path;
            $cont->save();
        } else {
            $arquivo = $cont->arquivo;
            Storage::disk('public')->delete($arquivo);
            $cont->arquivo = $path;
            $cont->save();
        }
        return back();
    }

    public function download_conteudo($id)
    {
        $cont = Conteudo::find($id);
        $discId = $cont->disciplina_id;
        $disciplina = Disciplina::find($discId);
        $nameFile = "";
        switch ($cont->serie) {
                case 6: $nameFile = "6º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome; break;
                case 7: $nameFile = "7º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome; break;
                case 8: $nameFile = "8º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome; break;
                case 9: $nameFile = "9º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome; break;
                case 1: $nameFile = "1º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome; break;
                case 2: $nameFile = "2º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome; break;
                case 3: $nameFile = "3º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome; break;
                default: $nameFile = "";
        };
        if(isset($cont)){
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($cont->arquivo);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = $nameFile.".".$extension;
            return response()->download($path, $name);
        }
        return back();
    }

    public function apagar_conteudo($id){
        $cont = Conteudo::find($id);
        $arquivo = $cont->arquivo;
        Storage::disk('public')->delete($arquivo);
        $cont->arquivo = "";
        $cont->save();
        return back();
    }
}
