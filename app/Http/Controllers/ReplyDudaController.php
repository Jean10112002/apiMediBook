<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\ReplyDuda;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReplyDudaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $rulesUpdate =
    [
        'contenido' => 'required|string',
    ];
    private $messagesUpdate = [
        'contenido.required' => 'El campo contenido es obligatorio.',
        'contenido.string' => 'El campo contenido debe ser una cadena.',
    ];
    private $rules =
    [
        'contenido' => 'required|string',
        'duda_id' => 'required',
    ];
    private $messages = [
        'contenido.required' => 'El campo contenido es obligatorio.',
        'contenido.string' => 'El campo contenido debe ser una cadena.',
        'duda_id.required' => 'El campo duda_id es obligatorio.',
    ];
    public function index()
    {
        try {
            $replyDudas = ReplyDuda::with('Duda','Usuario','Duda.Paciente','Duda.Paciente.Usuario')->get();
            return response()->json([
                "replyDudas" => $replyDudas
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
            ReplyDuda::create([
                "fecha" => Carbon::now(),
                "contenido" => $request->contenido,
                "user_id"=>$usuario->id,
                "duda_id"=>$request->duda_id,
            ]);
            return response()->json([
                "message" => "replyduda creada exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $replyDuda)
    {
        try {
            $dudas = ReplyDuda::whereId($replyDuda)->with('Duda','Usuario','Duda.Paciente','Duda.Paciente.Usuario')->first();
            if (!$dudas) {
                return response()->json([
                    "message" => "ReplyDuda no encontrada"
                ], 404);
            }
            return response()->json([
                "replyDudas" => $dudas
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $replyDuda)
    {
        $validator = Validator::make($request->all(), $this->rulesUpdate, $this->messagesUpdate);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        try {
            $duda=ReplyDuda::find($replyDuda);
            $duda->update([
                "contenido" => $request->contenido,
            ]);
            return response()->json([
                "message" => "replyduda actualizada exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $replyDuda)
    {
        try {
            ReplyDuda::findOrFail($replyDuda)->delete();
            return response()->json([
                "message"=>"ReplyDuda eliminada exitosamente"
            ],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
