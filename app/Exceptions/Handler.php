<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use \Illuminate\Auth\AuthenticationException;
use App\Exceptions;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }
    
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()){
            return response()->json(['message'=>$exception->getMessage()],401);
        }
        
        $guard = data_get($exception->guards(),0);

        switch($guard){
            case 'admin': $login = "admin.login"; break;
            case 'aluno': $login = "aluno.login"; break;
            case 'prof': $login = "prof.login"; break;
            case 'outro': $login = "outro.login"; break;
            case 'responsavel': $login = "responsavel.login"; break;
            case 'web' : $login = "login"; break;
            default : $login = "login"; break;
        }
        return redirect()->guest(route($login));
    }
}
