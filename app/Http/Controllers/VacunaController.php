<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Vacuna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VacunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $rules = [
        'nombre' => 'required|string',
        'fecha' => 'required|date|before_or_equal:today',
    ];

    private $messages = [
        'nombre.required' => 'El campo nombre es obligatorio.',
        'nombre.string' => 'El campo nombre debe ser una cadena.',
        'fecha.required' => 'El campo fecha es obligatorio.',
        'fecha.date' => 'El campo fecha debe ser una fecha válida.',
        'fecha.before_or_equal' => 'El campo fecha debe ser anterior o igual a la fecha actual.',
    ];
    public function __construct()
    {
        $this->middleware(['onlyPaciente'])->only('store');
        $this->middleware(['onlyPaciente'])->only('update');
        $this->middleware(['onlyPaciente'])->only('destroy');
    }
    public function index()
    {
        try {
            $medicamentos = Vacuna::with('Paciente', 'Paciente.Usuario')->get();
            return response()->json([
                "vacunas" => $medicamentos
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
            Vacuna::create([
                "nombre"=>$request->nombre,
                "fecha"=>$request->fecha,
                "paciente_id"=>$paciente->id,
            ]);
            return response()->json([
                "message" => "Vacuna creado exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $vacuna)
    {
        try {
            $dudas = Vacuna::whereId($vacuna)->with('Paciente', 'Paciente.Usuario')->first();
            if (!$dudas) {
                return response()->json([
                    "message" => "Vacuna no encontrados"
                ], 404);
            }
            return response()->json([
                "vacunas" => $dudas
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $vacuna)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        try {
            $vacuna=Vacuna::find($vacuna);
            $vacuna->update([
                "nombre"=>$request->nombre,
                "fecha"=>$request->fecha,
                "duracion"=>$request->duracion,
            ]);
            return response()->json([
                "message" => "Vacuna actualizado exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $vacuna)
    {
        try {
            Vacuna::findOrFail($vacuna)->delete();
            return response()->json([
                "message"=>"Vacuna eliminado exitosamente"
            ],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
