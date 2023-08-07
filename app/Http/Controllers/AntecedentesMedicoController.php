<?php

namespace App\Http\Controllers;

use App\Models\AntecedentesMedico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AntecedentesMedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $rules = [
        'condicion_medica' => 'required|string',
        'alergias' => 'required|string',
        'cirugias_previas' => 'required|string',
        'tipo_sangre' => 'required|string',
        'otros_datos' => 'required|string',
    ];

    private $messages = [
        'condicion_medica.required' => 'El campo condicion_medica es obligatorio.',
        'condicion_medica.string' => 'El campo condicion_medica debe ser una cadena.',
        'alergias.required' => 'El campo alergias es obligatorio.',
        'alergias.string' => 'El campo alergias debe ser una cadena.',
        'cirugias_previas.required' => 'El campo cirugias_previas es obligatorio.',
        'cirugias_previas.string' => 'El campo cirugias_previas debe ser una cadena.',
        'tipo_sangre.required' => 'El campo tipo_sangre es obligatorio.',
        'tipo_sangre.string' => 'El campo tipo_sangre debe ser una cadena.',
        'otros_datos.required' => 'El campo otros_datos es obligatorio.',
        'otros_datos.string' => 'El campo otros_datos debe ser una cadena.',
    ];

    public function index()
    {
        try {
            $medicamentos = AntecedentesMedico::with('Paciente', 'Paciente.Usuario')->get();
            return response()->json([
                "antecedentes_medicos" => $medicamentos
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
            AntecedentesMedico::create([
                "condicion_medica"=>$request->condicion_medica,
                "alergias"=>$request->alergias,
                "cirugias_previas"=>$request->cirugias_previas,
                "tipo_sangre"=>$request->tipo_sangre,
                "otros_datos"=>$request->otros_datos,
                "paciente_id"=>$paciente->id,
            ]);
            return response()->json([
                "message" => "Antecedente medico creado exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $antecedentesMedico)
    {
        try {
            $dudas = AntecedentesMedico::whereId($antecedentesMedico)->with('Paciente', 'Paciente.Usuario')->first();
            if (!$dudas) {
                return response()->json([
                    "message" => "Antecedente medico no encontrados"
                ], 404);
            }
            return response()->json([
                "antecedentes_medicos" => $dudas
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $antecedentesMedico)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        try {
            $examen=AntecedentesMedico::find($antecedentesMedico);
            $examen->update([
                "condicion_medica"=>$request->condicion_medica,
                "alergias"=>$request->alergias,
                "cirugias_previas"=>$request->cirugias_previas,
                "tipo_sangre"=>$request->tipo_sangre,
                "otros_datos"=>$request->otros_datos,
            ]);
            return response()->json([
                "message" => "Antecedente medico actualizado exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $antecedentesMedico)
    {
        try {
            AntecedentesMedico::findOrFail($antecedentesMedico)->delete();
            return response()->json([
                "message"=>"Antecedente medico eliminado exitosamente"
            ],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
