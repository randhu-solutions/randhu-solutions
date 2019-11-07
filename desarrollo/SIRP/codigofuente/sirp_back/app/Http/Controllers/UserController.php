<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\User;
use App\Userprofile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public $status = 200;
    protected $rules = [
        'username' => 'required',
        'rol' => 'required|integer|in:1,2,3',
        'last_name' => 'required|regex:/^[\pL\-]*\s?[\pL\-]*$/',
        'first_name' => 'required|regex:/^[\pL\-]*\s?[\pL\-]*$/',
        'email' => 'required|email',
    ];
    protected $messages = [

    ];

    public function createuser(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, $this->rules, $this->messages);
        $errors = $validator->errors();
        if($validator->fails())
        {
            $res = [
                'success' => 0,
                'msg' => $errors->all()
            ];
            $this->status = 500;
        }
        else {
            $verifyuser = User::where('email',$request->email)->first();
            if ($verifyuser)
            {
                $res = [
                    'success' => 0,
                    'msg' => 'El usuario ya existe'
                ];
                $this->status = 500;
            }
            else
            {

                $user= new User();
                $user->username = $request->username;
                $user->email = $request->email;
                $user->password = LoginController::codificarSha($request->password);
                if ($request->rol == 1) {
                    $user->rol = 'Admin';
                }
                if ($request->rol > 1) {
                    $user->rol = 'Usuario';
                }
                $user->status = 1;

                $profile = new Userprofile();
                $profile->user_id = $user->user_id;
                $profile->last_name = $request->last_name;
                $profile->first_name = $request->first_name;

                if ($user->save()) {
                    $user->profile()->save($profile);
                    $res = [
                        'success' => 1,
                        'data' => $user
                    ];

                } else {
                    $res = [
                        'success' => 0,
                        'msg' => 'Error insertando usuario'
                    ];
                    $this->status = 500;
                }

            }
        }
        return response()->json($res, $this->status);
    }
}
