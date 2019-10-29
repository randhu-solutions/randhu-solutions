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
        '/login',
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
        $token = $request->input('token');

        if(!$token){
            $res = [
                'error'=>103,
                'msg'=> __('errors.103')
            ];
            return response()->json($res);
        }
        $sesion = Usersession::where('tokenSesion',$token)->first();
        if(!$sesion){
            $res = [
                'error'=>100,
                'msg'=> __('errors.100')
            ];
            return response()->json($res);
        }
        if(Carbon::parse($sesion->finSesion)->lte(Carbon::now())){
            $update = Usersession::where('idSesion',$sesion->idSesion)->update(['estadoSesion'=>0]);
            $res = [
                'error'=>101,
                'msg'=> __('errors.101')
            ];
            return response()->json($res);
        }
        else{
            $res = [
                'error'=>102,
                'msg'=> __('errors.102')
            ];
//            return response()->json($res);
        }

        return $next($request);
    }


}
