<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use App\Classes\FormatResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends FormatResponse
{

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'password' => 'required',
            'email' => 'required'
        ];

        $messages = [
            'name.required' => 'El nombre es requerido.',
            'password.required' => 'La contraseña es requerida.',
            'email.required' => 'El correo es requerida.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->errors()->getMessages()){
            return response($this->toJson($this->estadoOperacionFallida($validator->getMessageBag()->all())), 401);
        }else{
            try {
                if(User::where('email', $request->email)->first()){
                    return response($this->toJson($this->accesoDenegado('El correo ya existe')), 401);
                }else{
                    $user = new User();
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->password = Hash::make($request->password);
                    $user->save();
                    return $this->toJson($this->estadoExitoso('Usuario creado exitosamente'));
                }
            } catch (Throwable $th) {
                DB::rollback();
                Log::error($th->getMessage());
                return $this->toJson($this->estadoOperacionFallida($th->getMessage() . ' ' . $th->getLine()));
            }
        }
    }


    public function login(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        $messages = [
            'email.required' => 'El correo es requerido.',
            'password.required' => 'La contraseña es requerida.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->errors()->getMessages()){
            return response($this->toJson($this->estadoOperacionFallida($validator->getMessageBag()->all())), 401);
        }else{
            try {
                $credentials = $request->all();
                if ($token = auth('api')->attempt($credentials)){
                    $data = [
                        'token' => $token, 
                        'minutes_to_expire' => 40,
                    ];
                    return $this->toJson($this->estadoExitoso('Sesion iniciada'),$data);
                }else{
                    return response($this->toJson($this->accesoDenegado('Correo o contraseña incorrecta')), 401);
                }
            } catch (Throwable $th) {
                DB::rollback();
                Log::error($th->getMessage());
                return $this->toJson($this->estadoOperacionFallida($th->getMessage() . ' ' . $th->getLine()));
            }
        }
    }
}
