<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */ private $rules = [
        'cantidad' => 'required|numeric',
        'medico_id' => 'required',
        'cita_id' => 'required',
    ];

    private $messages = [
        'cantidad.required' => 'El campo cantidad es obligatorio.',
        'cantidad.numeric' => 'El campo cantidad debe ser un número.',
        'medico_id.required' => 'El campo medico_id es obligatorio.',
        'cita_id.required' => 'El campo cita_id es obligatorio.',
    ];
    public function __construct()
    {
        $this->middleware(['onlyPaciente'])->only('store');
    }
    public function index()
    {
        try {
            $medicamentos = Pago::with('Medico', 'Paciente', 'Cita', 'Medico.Usuario', 'Paciente.Usuario')->get();
            return response()->json([
                "pagos" => $medicamentos
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
            Pago::create([
                "cantidad"=>$request->cantidad,
                "medico_id"=>$request->medico_id,
                "cita_id"=>$request->cita_id,
                "paciente_id" => $paciente->id
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
    public function show($pago)
    {
        try {
            $dudas = Pago::whereId($pago)->with('Medico', 'Paciente', 'Cita', 'Medico.Usuario', 'Paciente.Usuario')->first();
            if (!$dudas) {
                return response()->json([
                    "message" => "Pago no encontrado"
                ], 404);
            }
            return response()->json([
                "pagos" => $dudas
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        //
    }
}
