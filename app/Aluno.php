<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Aluno extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $guard = 'aluno';
    
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function turma(){
        return $this->belongsTo('App\Turma');
    }

    function atividade(){
        return $this->belongsToMany("App\Atividade", "atividade_retornos");
    }

    function responsaveis(){
        return $this->belongsToMany("App\Responsavel", "responsavel_alunos");
    }
}
