<?php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MedicamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $rules = [
        'nombre' => 'required|string',
        'dosis' => 'required|string',
        'lapso' => 'required|string',
        'duracion' => 'required|string',
    ];

    private $messages = [
        'nombre.required' => 'El campo nombre es obligatorio.',
        'nombre.string' => 'El campo nombre debe ser una cadena.',
        'dosis.required' => 'El campo dosis es obligatorio.',
        'dosis.string' => 'El campo dosis debe ser una cadena.',
        'lapso.required' => 'El campo lapso es obligatorio.',
        'lapso.string' => 'El campo lapso debe ser una cadena.',
        'duracion.required' => 'El campo duracion es obligatorio.',
        'duracion.string' => 'El campo duracion debe ser una cadena.',
    ];

    public function index()
    {
        try {
            $medicamentos = Medicamento::with('Paciente', 'Paciente.Usuario')->get();
            return response()->json([
                "medicamentos" => $medicamentos
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
            Medicamento::create([
                "nombre"=>$request->nombre,
                "dosis"=>$request->dosis,
                "lapso"=>$request->lapso,
                "duracion"=>$request->duracion,
                "paciente_id"=>$paciente->id,
            ]);
            return response()->json([
                "message" => "Medicamento creado exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($medicamento)
    {
        try {
            $dudas = Medicamento::whereId($medicamento)->with('Paciente', 'Paciente.Usuario')->first();
            if (!$dudas) {
                return response()->json([
                    "message" => "Medicamentos no encontrados"
                ], 404);
            }
            return response()->json([
                "medicamentos" => $dudas
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $medicamento)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        try {
            $examen=Medicamento::find($medicamento);
            $examen->update([
                "nombre"=>$request->nombre,
                "dosis"=>$request->dosis,
                "lapso"=>$request->lapso,
                "duracion"=>$request->duracion,
            ]);
            return response()->json([
                "message" => "Medicamento actualizado exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $medicamento)
    {
        try {
            Medicamento::findOrFail($medicamento)->delete();
            return response()->json([
                "message"=>"Medicamento eliminado exitosamente"
            ],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
