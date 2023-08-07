<?php

namespace App\Http\Controllers;

use App\Models\ExamenesMedico;
use App\Models\Paciente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExamenesMedicoController extends Controller
{
    private $rules = [
        'nombre' => 'required|string',
        'fecha' => 'required|date_format:Y-m-d|before:today', // Ajusta el formato de fecha según tus necesidades
        'resultado' => 'required|string',
    ];

    private $messages = [
        'nombre.required' => 'El campo nombre es obligatorio.',
        'nombre.string' => 'El campo nombre debe ser una cadena.',
        'fecha.required' => 'El campo fecha es obligatorio.',
        'fecha.date_format' => 'El campo fecha debe tener el formato YYYY-MM-DD.',
        'fecha.before' => 'El campo fecha debe ser una fecha anterior a hoy',
        'resultado.required' => 'El campo resultado es obligatorio.',
        'resultado.string' => 'El campo resultado debe ser una cadena.',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $dudas = ExamenesMedico::with('Paciente','Paciente.Usuario')->get();
            return response()->json([
                "examenes_medicos" => $dudas
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
            ExamenesMedico::create([
                "nombre"=>$request->nombre,
                "fecha"=>$request->fecha,
                "resultado"=>$request->resultado,
                "paciente_id"=>$paciente->id
            ]);
            return response()->json([
                "message" => "Examen medico creado exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $examenesMedico)
    {
        try {
            $dudas = ExamenesMedico::whereId($examenesMedico)->with('Paciente','Paciente.Usuario')->first();
            if (!$dudas) {
                return response()->json([
                    "message" => "Examenes Medicos no encontrada"
                ], 404);
            }
            return response()->json([
                "examenes_medicos" => $dudas
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $examenesMedico)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        try {
            $examen=ExamenesMedico::find($examenesMedico);
            $examen->update([
                "nombre"=>$request->nombre,
                "fecha"=>$request->fecha,
                "resultado"=>$request->resultado,
            ]);
            return response()->json([
                "message" => "Examen medico actualizado exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $examenesMedico)
    {
        try {
            ExamenesMedico::findOrFail($examenesMedico)->delete();
            return response()->json([
                "message"=>"Examen medico eliminado exitosamente"
            ],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
