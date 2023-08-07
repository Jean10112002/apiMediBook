<?php

namespace App\Http\Controllers;

use App\Models\Mensaje;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MensajeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private $rules = [
        'contenido' => 'required|string',
        'usuario_recibido_id' => 'required',
    ];

    private $messages = [
        'contenido.required' => 'El campo contenido es obligatorio.',
        'usuario_recibido_id.required' => 'El campo usuario_recibido_id es obligatorio.',
    ];
    public function index()
    {
        try {
            $mensajes = Mensaje::with('UsuarioEnviado', 'UsuarioRecibido')->get();
            return response()->json([
                "mensajes" => $mensajes
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        try {
            $usuario = Auth::guard('sanctum')->user();
            if ($request->usuario_recibido_id == $usuario->id) {
                return response()->json([
                    "message" => "no puedes enviarte mensajes a ti mismo"
                ], 500);
            }

            Mensaje::create([
                "contenido" => $request->contenido,
                "fecha" => Carbon::now(),
                "usuario_enviado_id" => $usuario->id,
                "usuario_recibido_id" => $request->usuario_recibido_id,
            ]);
            return response()->json([
                "message" => "mensaje enviado exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($mensaje)
    {
        try {
            $mensajes = Mensaje::whereId($mensaje)->with('UsuarioEnviado', 'UsuarioRecibido')->first();
            if (!$mensajes) {
                return response()->json([
                    "message" => "no existen mensajes"
                ], 404);
            }
            return response()->json([
                "mensajes" => $mensajes
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mensaje $mensaje)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mensaje $mensaje)
    {
        //
    }
    public function misMensajes()
    {
        try {
            $usuario = Auth::guard('sanctum')->user();
            /* $mensajes = User::whereId($usuario->id)->with('MensajeRecibido', 'MensajeRecibido.UsuarioEnviado', 'MensajeRecibido.UsuarioRecibido', 'MensajeEnviado', 'MensajeEnviado.UsuarioEnviado', 'MensajeRecibido.UsuarioRecibido')->get(); */

            $usuarioId = $usuario->id;

            $mensajes = Mensaje::where(function ($query) use ($usuarioId) {
                $query->where('usuario_enviado_id', $usuarioId)
                    ->orWhere('usuario_recibido_id', $usuarioId);
            })
                ->with('UsuarioEnviado', 'UsuarioRecibido')
                ->orderBy('fecha', 'asc')
                ->get();

            $mensajesAgrupados = collect();

            foreach ($mensajes as $mensaje) {
                $chatKey = $mensaje->usuario_enviado_id == $usuarioId
                    ? $mensaje->usuario_recibido_id
                    : $mensaje->usuario_enviado_id;

                if (!isset($mensajesAgrupados[$chatKey])) {
                    $mensajesAgrupados[$chatKey] = collect(['messages' => collect()]);
                }

                $mensajesAgrupados[$chatKey]['messages']->push($mensaje);
            }

            $mensajesAgrupados = $mensajesAgrupados->sortByDesc(function ($chat) {
                return $chat['messages']->last()->created_at;
            })->values();

            return response()->json([

                "mensajes_por_chat"=>$mensajesAgrupados
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
