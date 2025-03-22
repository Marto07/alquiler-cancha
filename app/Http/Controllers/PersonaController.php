<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonaController extends Controller
{
    //

    public function index(Request $request)
    {
        $registrosPorPagina = $request->input('registros_por_pagina', 10);
        $paginaActual       = $request->input('pagina');

        $offset = ($paginaActual - 1) * $registrosPorPagina;

        $personas = Persona::skip($offset)->take($registrosPorPagina);
        $totalRegistros = Persona::count();
        $totalPaginas   = ($totalRegistros / $registrosPorPagina);

        if ($request->wantsJson()) {
            return response()->json([
                'registros por pagina'  => $registrosPorPagina,
                'pagina actual'         => $paginaActual,
                'paginas necesarias'    => $totalPaginas,
                'personas'              => $personas,
            ]);
        } else {
            return view('personas_index', compact('personas'));
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'            => 'required|string',
            'apellido'          => 'required|string',
            'fecha_nacimiento'  => 'required|date',
        ], [
            'nombre.required'           => 'El nombre es requerido',
            'apellido.required'         => 'El apellido es requerido',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            'fecha_nacimiento.date'     => 'La fecha de nacimiento debe ser un formato de fecha',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json($validator->errors(), 400);
            }        
        }

        $persona = Persona::create([
            'nombre'            => $request->nombre,
            'apellido'          => $request->apellido,
            'fecha_nacimiento'  => $request->fecha_nacimiento,
        ]);

        if (!$persona) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Error al crear la persona'], 500);
            }
        }

        return response()->json($persona, 201);
    }

    public function show($id)
    {
        $persona = Persona::find($id);
        if (!$persona) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        }
        return response()->json($persona, 201);
    }

    public function update(Request $request, $id)
    {
        $persona = Persona::find($id);
        if (!$persona) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        }

        $persona->update($request->all());
        return response()->json($persona);
    }

    public function destroy($id)
    {
        $persona = Persona::find($id);
        if (is_null($persona)) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        }

        $persona->delete();
        return response()->json(null, 204);

        // O tambien
        $persona->update(["activo" => false]);
    }

}
