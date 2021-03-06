<?php

namespace App\Http\Controllers;

use App\Aluno;
use App\Atividade;
use App\AtividadeRetorno;
use App\Conteudo;
use App\Disciplina;
use App\Prof;
use App\ProfDisciplina;
use App\Turma;
use App\TurmaDisciplina;
use Illuminate\Http\Request;
use App\Ocorrencia;
use App\Responsavel;
use App\ResponsavelAluno;
use App\TipoOcorrencia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfController extends Controller
{
    //ADMIN
    public function consultaProf()
    {
        $profs = Prof::with('disciplinas')->orderBy('name')->paginate(10);
        $discs = Disciplina::all();
        return view('admin.profs', compact('profs','discs'));
    }

    //ADMIN
    public function novoProf(Request $request)
    {
        $prof = new Prof();
        $prof->name = $request->input('name');
        $prof->email = $request->input('email');
        $prof->password = Hash::make($request->input('password'));
        $prof->save();
        $profId = $prof->id;
        $disciplinas = $request->input('disciplinas');
                foreach($disciplinas as $disciplina){
                    $profDisc = new ProfDisciplina();
                    $profDisc->prof_id = $profId;
                    $profDisc->disciplina_id = $disciplina;
                    $profDisc->save();
                }
        return redirect('/prof/consulta');
    }

    //ADMIN
    public function filtroProf(Request $request)
    {
        $nome = $request->input('nome');
        $disc = $request->input('disciplina');
        if(isset($nome)){
            if(isset($disc)){
                $profs = Prof::where('name','like',"%$nome%")->where('disciplina_id',"$disc")->orderBy('name')->get();
            } else {
                $profs = Prof::where('name','like',"%$nome%")->orderBy('name')->get();
            }
        } else {
            if(isset($disc)){
                $profs = Prof::where('disciplina_id',"$disc")->orderBy('name')->get();
            } else {
                return redirect('/prof/consulta');
            }
        }
        $discs = Disciplina::all();
        return view('admin.profs', compact('discs','profs'));
    }

    //ADMIN
    public function editarProf(Request $request, $id)
    {
        $prof = Prof::find($id);
        if(isset($prof)){
            $prof->name =$request->input('name');
            $prof->email =$request->input('email');
            if($request->input('password')!=""){
            $prof->password = Hash::make($request->input('password'));
            }
            $prof->save();
            $disciplinas = $request->input('disciplinas');
            foreach ($disciplinas as $disciplina) {
                $profDiscs = ProfDisciplina::where('prof_id',"$id")->where('disciplina_id',"$disciplina")->get();
                if($profDiscs->count()==0){
                    $profDisc = new ProfDisciplina();
                    $profDisc->prof_id = $id;
                    $profDisc->disciplina_id = $disciplina;
                    $profDisc->save();
                }
            }
        }
        return redirect('/prof/consulta');
    }

    //ADMIN
    public function apagarProf($id)
    {
        $prof = Prof::find($id);
        if(isset($prof)){
            $prof->delete();
        }
        return redirect('/prof/consulta');
    }

    public function apagarDisciplina($prof_id, $disciplina_id)
    {
        ProfDisciplina::where('prof_id',"$prof_id")->where('disciplina_id',"$disciplina_id")->delete();
        return redirect('/prof/consulta');
    }
    
    
    //PROF
    public function index(){
        return view('profs.home_prof');
    }

    public function disciplinasAtividades(){
        $profId = Auth::user()->id;
        $profDiscs = ProfDisciplina::where('prof_id',"$profId")->get();
        return view('profs.atividade_disciplinas', compact('profDiscs'));
    }

    //PROF
    public function painelAtividades($discId){
        $profId = Auth::user()->id;
        $disciplina = Disciplina::find($discId);
        $turmas = TurmaDisciplina::where('disciplina_id',"$discId")->get();
        $atividades = Atividade::where('prof_id',"$profId")->where('disciplina_id',"$discId")->orderBy('id','desc')->paginate(5);
        $tipo = "painel";
        return view('profs.atividade_prof', compact('disciplina','turmas','atividades','tipo'));
    }

    //PROF
    public function novaAtividade(Request $request)
    {
        $profId = Auth::user()->id;
        $discId = $request->input('disciplina');
        $path = $request->file('arquivo')->store('atividades','public');
        $atividade = new Atividade();
        $atividade->prof_id = $profId;
        $atividade->disciplina_id = $discId;
        $atividade->turma_id = $request->input('turma');
        $atividade->retorno = $request->input('retorno');
        if($request->input('dataPublicacao')!=""){
            $atividade->data_publicacao = $request->input('dataPublicacao').' '.$request->input('horaPublicacao');
        }
        if($request->input('dataExpiracao')!=""){
            $atividade->data_expiracao = $request->input('dataExpiracao').' '.$request->input('horaExpiracao');
        }
        $atividade->descricao = $request->input('descricao');
        $atividade->link = $request->input('link');
        $atividade->visualizacoes = 0;
        $atividade->arquivo = $path;
        $atividade->save();
        
        return back()->with('success', 'Atividade cadastrada com Sucesso!');
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
        if($request->input('dataPublicacao')!="" && $request->input('horaPublicacao')!=""){
            $atividade->data_publicacao = $request->input('dataPublicacao').' '.$request->input('horaPublicacao');
        }
        if($request->input('dataExpiracao')!="" && $request->input('horaExpiracao')!=""){
            $atividade->data_expiracao = $request->input('dataExpiracao').' '.$request->input('horaExpiracao');
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
        $atividade->retorno = $request->input('retorno');
        $atividade->save();
        
        return back()->with('success', 'Atividade editada com Sucesso!');
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
        return redirect('/prof/atividade');
    }

    public function downloadRetorno($id)
    {
        $retorno = AtividadeRetorno::find($id);
        $alunoId = $retorno->aluno_id;
        $atividadeId = $retorno->atividade_id;
        $aluno = Aluno::find($alunoId);
        $nomeAluno = $aluno->name;
        $atividade = Atividade::find($atividadeId);
        $descricaoAtividade = $atividade->descricao;
        $turma = Turma::find($atividade->turma_id);
        $nameFile = $turma->serie."º - ".$descricaoAtividade." - ".$nomeAluno;
        if(isset($retorno)){
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($retorno->arquivo);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = $nameFile.".".$extension;
            return response()->download($path, $name);
        }
        return back();
    }
    
    public function retornos($atividade_id){
        $retornos = AtividadeRetorno::where('atividade_id',"$atividade_id")->get();
        $atividade = Atividade::find($atividade_id);
        $descricao = $atividade->descricao;
        return view('profs.retornos', compact('descricao','retornos'));
    }

    //PROF
    public function apagarAtividade($id){
        $atividade = Atividade::find($id);
        $arquivo = $atividade->arquivo;
        Storage::disk('public')->delete($arquivo);
        if(isset($atividade)){
            $atividade->delete();
        }
        
        return back()->with('success', 'Atividade excluída com Sucesso!');
    }

    //PROF
    public function filtroAtividade(Request $request, $discId)
    {
        $profId = Auth::user()->id;
        $disciplina = Disciplina::find($discId);
        $turma = $request->input('turma');
        $descricao = $request->input('descricao');
        $data = $request->input('data');
        if(isset($turma)){
            if(isset($descricao)){
                if(isset($data)){
                    $atividades = Atividade::where('prof_id',"$profId")->where('descricao','like',"%$descricao%")->where('turma_id',"$turma")->where('data_criacao',"$data")->orderBy('id','desc')->get();
                } else {
                    $atividades = Atividade::where('prof_id',"$profId")->where('descricao','like',"%$descricao%")->where('turma_id',"$turma")->orderBy('id','desc')->get();
                }
            } else {
                $atividades = Atividade::where('prof_id',"$profId")->where('turma_id',"$turma")->orderBy('id','desc')->get();
            }
        } else {
            if(isset($descricao)){
                if(isset($data)){
                    $atividades = Atividade::where('prof_id',"$profId")->where('descricao','like',"%$descricao%")->where('data_criacao',"$data")->orderBy('id','desc')->get();
                } else {
                    $atividades = Atividade::where('prof_id',"$profId")->where('descricao','like',"%$descricao%")->orderBy('id','desc')->get();
                }
            } else {
                if(isset($data)){
                    $atividades = Atividade::where('prof_id',"$profId")->where('data_criacao',"$data")->orderBy('id','desc')->get();
                } else {
                    $atividades = Atividade::where('prof_id',"$profId")->orderBy('id','desc')->get();
                }
            }
        }
        $turmas = TurmaDisciplina::where('disciplina_id',"$discId")->get();
        $tipo = "filtro";
        return view('profs.atividade_prof', compact('disciplina','turmas','atividades','tipo'));
    }

    public function disciplinasOcorrencias(){
        $profId = Auth::user()->id;
        $profDiscs = ProfDisciplina::where('prof_id',"$profId")->get();
        return view('profs.ocorrencias_disciplinas', compact('profDiscs'));
    }

    public function turmasOcorrencias($disciplina){
        $turmaDiscs = TurmaDisciplina::where('disciplina_id',"$disciplina")->get();
        return view('profs.ocorrencias_turmas', compact('turmaDiscs','disciplina'));
    }

    public function indexOcorrencias($disciplina, $turma){
        $profId = Auth::user()->id;
        $alunos = Aluno::where('turma_id',"$turma")->get();
        $tipos = TipoOcorrencia::all();
        $ocorrencias = Ocorrencia::where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->paginate(10);
        $busca = "nao";
        return view('profs.ocorrencias_prof', compact('alunos','tipos','disciplina','ocorrencias','busca','turma'));
    }

    public function filtroOcorrencias(Request $request, $disciplina, $turma)
    {
        $profId = Auth::user()->id;
        $tipo = $request->input('tipo');
        $aluno = $request->input('aluno');
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        if(isset($tipo)){
            if(isset($aluno)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", date("Y/m/d")])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->whereBetween('data',["", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->whereBetween('data',["$dataInicio", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->whereBetween('data',["$dataInicio", date("Y/m/d")])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->whereBetween('data',["", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                }
            }
        } else {
            if(isset($aluno)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", date("Y/m/d")])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->whereBetween('data',["", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::whereBetween('data',["$dataInicio", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::whereBetween('data',["$dataInicio", date("Y/m/d")])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::whereBetween('data',["", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                }
            }
        }
        $alunos = Aluno::where('turma_id',"$turma")->get();
        $tipos = TipoOcorrencia::all();
        $busca = "sim";
        return view('profs.ocorrencias_prof', compact('alunos','tipos','disciplina','ocorrencias','busca','turma'));
    }

    public function novasOcorrencias(Request $request){
        $profId = Auth::user()->id;
        $alunos = $request->input('alunos');
        $tipo = $request->input('tipo');
        $disciplina = $request->input('disciplina');
        $data = $request->input('data');
        if($request->input('observacao')==""){
            $observacao = "";
        } else {
            $observacao = $request->input('observacao');
        }
        if($request->input('alunos')==""){
            return back();
        } else {
            foreach($alunos as $aluno) {
                $ocorrencia = new Ocorrencia();
                $ocorrencia->aluno_id = $aluno;
                $ocorrencia->tipo_ocorrencia_id = $tipo;
                $ocorrencia->prof_id = $profId;
                $ocorrencia->disciplina_id = $disciplina;
                $ocorrencia->data = $data;
                $ocorrencia->observacao = $observacao;
                $ocorrencia->save();
            }
        }
        return back();
    }

    public function editarOcorrencia(Request $request, $id)
    {
        $ocorrencia = Ocorrencia::find($id);
        if(isset($ocorrencia)){
            if($request->input('tipo')!=""){
                $ocorrencia->tipo_ocorrencia_id = $request->input('tipo');
            }
            if($request->input('data')!=""){
                $ocorrencia->data = $request->input('data');
            }
            if($request->input('observacao')==""){
                $ocorrencia->observacao = "";
            } else { 
                $ocorrencia->observacao = $request->input('observacao');
            }
            $ocorrencia->save();
        }
        return back();
    }

    public function apagarOcorrencia($disciplina, $turma, $id)
    {
        $ocorrencia = Ocorrencia::find($id);
        if(isset($ocorrencia)){
            $ocorrencia->delete();
        }
        $profId = Auth::user()->id;
        $alunos = Aluno::where('turma_id',"$turma")->get();
        $tipos = TipoOcorrencia::all();
        $ocorrencias = Ocorrencia::where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->paginate(10);
        $tipo = "painel";
        $busca = "nao";
        return view('profs.ocorrencias_prof', compact('alunos','tipos','disciplina','ocorrencias','turma','tipo','busca'));
    }

    public function painelConteudosAno(Request $request){
        $ano = $request->input('ano');
        $anos = DB::table('conteudos')->select(DB::raw("ano"))->groupBy('ano')->get();
        return view('profs.home_conteudos',compact('ano','anos'));
    }

    public function painelConteudos($ano){
        if($ano==""){
            $ano = date("Y");
        }
        $anos = DB::table('conteudos')->select(DB::raw("ano"))->groupBy('ano')->get();
        return view('profs.home_conteudos',compact('ano','anos'));
    }

    public function conteudos($ano, $bim, $tipo){
        $validador = Conteudo::where('tipo', "$tipo")->where('bimestre',"$bim")->where('ano',"$ano")->count();
        if($validador==0){
            return back()->with('mensagem', 'Os campos para anexar os conteúdos não foram gerados, solicite ao Admin ou aguarde!');
        } else {
            $profId = Auth::user()->id;
            $profDiscs = ProfDisciplina::where('prof_id',"$profId")->get();
            $fundTurmas = Turma::where('turma','A')->where('ensino','fund')->get();
            $medioTurmas = Turma::where('turma','A')->where('ensino','medio')->get();
            $fundDiscs = Disciplina::where('ensino','fund')->get();
            $medioDiscs = Disciplina::where('ensino','medio')->get();
            $contFunds = Conteudo::orderBy('disciplina_id')->where('tipo', "$tipo")->where('bimestre',"$bim")->where('ensino','fund')->where('ano',"$ano")->get();
            $contMedios = Conteudo::orderBy('disciplina_id')->where('tipo', "$tipo")->where('bimestre',"$bim")->where('ensino','medio')->where('ano',"$ano")->get();
            return view('profs.conteudos',compact('profDiscs','tipo','bim','fundTurmas','medioTurmas','fundDiscs','medioDiscs','contFunds','contMedios','ano'));
        }
    }

    public function anexarConteudo(Request $request, $id)
    {
        $path = $request->file('arquivo')->store('conteudos','public');
        $cont = Conteudo::find($id);
        if($cont->arquivo=="" || $cont->arquivo==null){
            $cont->arquivo = $path;
            $cont->update();
        } else {
            $arquivo = $cont->arquivo;
            Storage::disk('public')->delete($arquivo);
            $cont->arquivo = $path;
            $cont->update();
        }
        return back();
    }

    public function downloadConteudo($id)
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

    public function apagarConteudo($id){
        $cont = Conteudo::find($id);
        $arquivo = $cont->arquivo;
        Storage::disk('public')->delete($arquivo);
        $cont->arquivo = "";
        $cont->save();
        return back();
    }
}
