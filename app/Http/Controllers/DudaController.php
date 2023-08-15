<?php

namespace App\Http\Controllers;

use App\Models\Duda;
use App\Models\Paciente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DudaController extends Controller
{
    private $rules =
    [
        'contenido' => 'required|string',
    ];
    private $messages = [
        'contenido.required' => 'El campo contenido es obligatorio.',
        'contenido.string' => 'El campo contenido debe ser una cadena.',
    ];
    public function index()
    {
        try {
            $dudas = Duda::with('Paciente', 'ReplyDuda', 'Paciente.Usuario', 'ReplyDuda.Usuario.Rol','Paciente.Usuario.Rol')->get();
            return response()->json([
                "dudas" => $dudas
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
            $paciente = Paciente::where('user_id', $usuario->id)->first();
            Duda::create([
                "fecha" => Carbon::now(),
                "contenido" => $request->contenido,
                "paciente_id" => $paciente->id,
            ]);
            return response()->json([
                "message" => "duda creada exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($duda)
    {
        try {
            $dudas = Duda::whereId($duda)->with('Paciente', 'ReplyDuda', 'Paciente.Usuario', 'Paciente.Usuario', 'ReplyDuda.Usuario')->first();
            if (!$dudas) {
                return response()->json([
                    "message" => "Duda no encontrada"
                ], 404);
            }
            return response()->json([
                "dudas" => $dudas
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $duda)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        try {
            $duda=Duda::find($duda);
            $duda->update([
                "contenido" => $request->contenido,
            ]);
            return response()->json([
                "message" => "duda actualizada exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $duda)
    {
        try {
            Duda::findOrFail($duda)->delete();
            return response()->json([
                "message"=>"duda eliminada exitosamente"
            ],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
