<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use stdClass;

class AuthController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('auth/login');
    }
    public function login(Request $request)
    {
        // Validar datos
        $validator = Validator::make($request->all(), [
            'email'         => 'required|email|max:50',
            'password'      => 'required|min:8|max:50'
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
                "errors"  => $validator->errors()->all(),
                "success" => false,
            ];

            // return response()->json($data, 400);
            return redirect()->route('login')->with($data);
        }
    
        // Verificar si el usuario existe y la contraseña es correcta
        $user = $this->authenticate($request);

        if (!$user) {
            $data = [
                "message" => "Credenciales incorrectas o el usuario no existe",
                "success" => false,
                "status"  => 401,
            ];

            // return response()->view('auth/credencialesIncorrectas', compact('data'), 401);
            return redirect()->route('login')->with($data);
        }
    
        //iniciar la sesion
        session(["usuario" => $user]);

        return redirect('/home');

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
            return null;
        }

        if (Hash::check($request->password, $user->password)) {
            return $user;
        } else {
            return null;
        }
    }
}
