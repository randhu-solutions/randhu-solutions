<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Usersession;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public $status = 200;
    public function dologin(Request $request)
    {

        if(!$request->email){
            $res = [
                'success'=>0,
                'msg'=>'Parametro email no recibido'
            ];
            $this->status = 500;
        }
        elseif(!$request->password){
            $res = [
                'success'=>0,
                'msg'=> 'Parametro password no recibido'
            ];
            $this->status = 500;
        }

        $user = User::where('email',$request->email)
            ->first();
        if(!$user){
            $res = [
                'success'=>0,
                'msg'=> 'Usuario no encontrado'
            ];
            $this->status = 500;
        }
        elseif($user->password==self::codificarSha($request->password)){
            $res = [

            ];

            $user = User::with('profile')->find($user->user_id);
            $token = self::generateToken();
            $expire = Carbon::now()->addHours(12);
            $sesion = new Usersession();
            $sesion->user_id = $user->user_id;
            $sesion->token_session = $token;
            $sesion->end_session = $expire;
            $sesion->save();


            $res = [
                'success'=>1,
                'msg'=>'Login Exitoso',
                'user'=> $user,
                'token'=>$token,
                'expire'=>$expire->toDateTimeString(),
            ];
        }
        else{
            $res = [
                'success'=>0,
                'msg'=> 'contraseña incorrecta'
            ];
            $this->status = 500;
        }

        return response()->json($res,$this->status);
    }

    public static function codificarSha($cadena){
        return hash('sha256',$cadena);
    }

    public static function generateToken(){
        $key = Str::random(40);
        $token = self::codificarSha($key);
        return $token;
    }
}
