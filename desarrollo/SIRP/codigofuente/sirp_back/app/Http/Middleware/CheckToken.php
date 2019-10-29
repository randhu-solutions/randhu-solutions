<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Auth\LoginController;
use App\Usersession;
use App\User;
use Carbon\Carbon;

class CheckToken
{
    protected $except = [
        '/usuario/login',
        '/usuario/registrar'
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('token');

        if(!$token){
            $res = [
                'success'=>0,
                'msg'=> 'Falta el parámetro Token'
            ];
            return response()->json($res, 500);
        }
        $session = Usersession::where('token_session',$token)->first();
        if(!$session){
            $res = [
                'success'=>0,
                'msg'=> 'Token Inválido'
            ];
            return response()->json($res, 500);
        }
        if(Carbon::parse($session->end_session)->lte(Carbon::now())){
            $update = Usersession::where('session_id',$session->session_id)->update(['status'=>0]);
            $res = [
                'success'=>0,
                'msg'=> 'Token Expirado.'
            ];
            return response()->json($res, 500);
        }
        else{
            $res = [
                'success'=>1,
                'msg'=> 'Token Válido.'
            ];
//            return response()->json($res);
        }

        return $next($request);
    }


}
