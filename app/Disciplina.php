<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disciplina extends Model
{
    
    use SoftDeletes;
    
    function turmas(){
        return $this->belongsToMany("App\Turma", "turma_disciplinas");
    }

    function profs(){
        return $this->belongsToMany("App\Prof", "prof_disciplinas");
    }
}
