<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        // Validar datos
        $validator = Validator::make($request->all(), [
            'email'      => 'required|email|max:50',
            'password' => 'required|min:8|max:50'
        ],
        [
            'email.required'      => 'El email es requerido',
            'email.email'         => 'El email debe ser un correo electrónico',
            'email.max'           => 'El email debe tener máximo 50 caracteres',
            'password.required' => 'La contraseña es requerida',
            'password.min'      => 'La contraseña debe tener mínimo 8 caracteres',
            'password.max'      => 'La contraseña debe tener máximo 50 caracteres',
        ]);
    
        if ($validator->fails()) {
            $data = [
                "message" => "Error en la validación de los datos",
                "errors"  => $validator->errors(),
                "status"  => 400,
            ];
            return response()->json($data, 400);
        }
    
        // Verificar si el usuario existe y la contraseña es correcta
        $user = $this->authenticate($request);
        return response()->json([
            "input_password" => $request->password,
            "user_password"  => $user->password,
            "hash_check"    => Hash::check($request->password, $user->password),
        ]);

        if (!$user) {
            return response()->json([
                "message" => "Credenciales incorrectas o el usuario no existe",
                "status"  => 401,
            ], 401);
        }
    
        //iniciar la sesion
        session(["usuario" => $user]);

        if (session()->has('usuario')) {
            return response()->json([
                "message" => "Usuario autenticado correctamente",
                "status"  => 200,
            ], 200);
        } else {
            return response()->json([
                "message" => "Error al autenticar al usuario",
                "status"  => 500,
            ], 500);
        }
    }

    public function logout()
    {
        session()->flush();
        return response()->json([
            "message" => "Sesión cerrada correctamente",
            "status"  => 200,
        ], 200);
    }

    private function authenticate(Request $request) {
        // Buscar el usuario por email
        $user = Usuario::with('contacto')
            ->whereHas('contacto', function ($query) use ($request) {
                $query->where('descripcion', $request->email);
            })
            ->first();
            
        if (!$user) {
        
            return 'NO HAY USUARIO';
            // O return false tambien puede ser
        }

        return $user;

        if ($user && Hash::check($request->password, $user->password)) {
            return "Todo correcto";
        } else {
            return "Contraseña incorrecta";
        }
    }
}
