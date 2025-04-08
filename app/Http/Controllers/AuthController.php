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

    public function showRegisterForm()
    {
        return view('auth/register');
    }

    public function recibirFormularioRegistro(Request $request)
    {
        // Validar datos
        $validator = Validator::make($request->all(), [
            'username'          => 'required|unique:usuario,username',
            'password'          => 'required|min:8|max:50',
            'email'             => 'required|email|max:50',
            'nombre'            => 'required|max:50',
            'apellido'          => 'required|max:50',
            'documento'         => 'required|string|max:50',
            'tipo_documento'    => 'required|exists:tipo_documento,id|integer',
            // 'fecha_nacimiento'  => 'required|date',
            'sexo'              => 'required|exists:sexo,id|integer',
        ],
        [
            'username.required'         => 'El campo username es obligatorio',
            'username.unique'           => 'El campo username debe ser único',
            'password.required'         => 'La contraseña es requerida',
            'password.min'              => 'La contraseña debe tener mínimo 8 caracteres',
            'password.max'              => 'La contraseña debe tener máximo 50 caracteres',
            'email.required'            => 'El email es requerido',
            'email.email'               => 'El email debe ser un correo electrónico',
            'email.max'                 => 'El email debe tener máximo 50 caracteres',
            'nombre.required'           => 'El nombre es requerido',
            'nombre.max'                => 'El nombre debe tener máximo 50 caracteres',
            'apellido.required'         => 'El apellido es requerido',
            'apellido.max'              => 'El apellido debe tener máximo 50 caracteres',
            // 'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            // 'fecha_nacimiento.date'     => 'La fecha de nacimiento debe ser una fecha válida',
            'sexo.required'             => 'El sexo es requerido',
            'sexo.exists'               => 'El sexo no existe en la tabla sexo',
            'sexo.integer'              => 'El sexo debe ser un número entero',
            'documento.required'        => 'El documento es requerido',
            'documento.string'          => 'El documento debe ser una cadena de texto',
            'documento.max'             => 'El documento debe tener máximo 50 caracteres',
            'tipo_documento.required'   => 'El tipo de documento es requerido',
            'tipo_documento.exists'     => 'El tipo de documento no existe en la tabla tipo_documento',
            'tipo_documento.integer'    => 'El tipo de documento debe ser un número entero',
            // Otros mensajes de error...
        ]);

        if ($validator->fails()) {
            
            $data = [
                "message" => "Error en la validacion de los datos",
                "errors"  => $validator->errors()->all(),
                "success" => false,
            ];
            return redirect('formulario-registro')->with($data);
            return response()->json($data, 400);

            //esta es la salida deseada, pero estamos probando
            // return redirect()->route('formularioRegistro')->with($data);
        }
        // Creamos el usuario
        // ... codigo de creacion de usuario ...

        $data = [
            "message" => "Usuario creado correctamente",
            "success" => true,
        ];
        return redirect()->route('login')->with($data);

        //probamos ver los datos en json
        return response()->json([
            "success" => true,
            "usuario" => $request->all(),
        ], 200);
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
