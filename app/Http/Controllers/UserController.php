<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Registrar un usuario
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:Masculino,Femenino,Otro',
            'numero_seguro' => 'required|string|max:50',        
            'historial_medico' => 'required|string',            
            'contacto_emergencia' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        $usuario = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'sexo' => $request->sexo,
            'numero_seguro' => $request->numero_seguro,
            'historial_medico' => $request->historial_medico,
            'contacto_emergencia' => $request->contacto_emergencia,
        ]);

        $token = $usuario->createToken('api-token')->plainTextToken;

        return response()->json([
            'mensaje' => 'Usuario registrado con éxito',
            'usuario' => $usuario,
            'token' => $token
        ], 201);
    }

    // Inicio de sesión
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        $usuario = User::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        $token = $usuario->createToken('api-token')->plainTextToken;

        return response()->json([
            'mensaje' => 'Login exitoso',
            'usuario' => $usuario,
            'token' => $token
        ]);
    }

    // Listar todos los usuarios
    public function index()
    {
        $usuarios = User::select('id', 'nombre', 'email', 'fecha_nacimiento', 'sexo', 'numero_seguro', 'historial_medico', 'contacto_emergencia', 'created_at')->get();
        return response()->json($usuarios);
    }

    // Mostrar un usuario
    public function show($id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
        }
        return response()->json($usuario);
    }

    // Actualizar un usuario
    public function update(Request $request, $id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:6|confirmed',
            'fecha_nacimiento' => 'sometimes|required|date',
            'sexo' => 'sometimes|required|in:Masculino,Femenino,Otro',
            'numero_seguro' => 'sometimes|required|string|max:50',        
            'historial_medico' => 'sometimes|required|string',            
            'contacto_emergencia' => 'sometimes|required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        if ($request->has('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        }

        $usuario->update($request->all());

        return response()->json([
            'mensaje' => 'Usuario actualizado',
            'usuario' => $usuario
        ]);
    }

    // Eliminar un usuario
    public function destroy($id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
        }

        $usuario->delete();
        return response()->json(['mensaje' => 'Usuario eliminado']);
    }
}