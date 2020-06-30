<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turma extends Model
{
    use SoftDeletes;
    
    function disciplinas(){
        return $this->belongsToMany("App\Disciplina", "turma_disciplinas");
    }
}
