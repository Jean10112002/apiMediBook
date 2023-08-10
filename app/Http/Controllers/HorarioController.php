<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HorarioController extends Controller
{
    private $rules = [
        'dia' => 'required',
        'hora_inicio' => 'required|date_format:H:i',
        'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        'medico_id' => 'required',
    ];

    private $messages = [
        'dia.required' => 'El día es requerido.',
        'hora_inicio.required' => 'La hora de inicio es requerida.',
        'hora_inicio.date_format' => 'El formato de hora de inicio no es válido (HH:mm).',
        'hora_fin.required' => 'La hora de fin es requerida.',
        'hora_fin.date_format' => 'El formato de hora de fin no es válido (HH:mm).',
        'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        'medico_id.required' => 'El ID del médico es requerido.',
    ];
    private $rulesUpdate = [
        'hora_inicio' => 'required|date_format:H:i',
        'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
    ];

    private $messagesUpdate = [
        'hora_inicio.required' => 'La hora de inicio es requerida.',
        'hora_inicio.date_format' => 'El formato de hora de inicio no es válido (HH:mm).',
        'hora_fin.required' => 'La hora de fin es requerida.',
        'hora_fin.date_format' => 'El formato de hora de fin no es válido (HH:mm).',
        'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
    ];
    /**
     * Display a listing of the resource.
     */ public function __construct()
    {
        $this->middleware('onlyAdmin')->only('store');
        $this->middleware('onlyAdmin')->only('destroy');
        $this->middleware('onlyAdmin')->only('update');
        $this->middleware('onlyAdmin')->only('show');
    }
    public function index()
    {
        $horario =   Horario::with('Medico.Usuario.DatosPersonale', 'Medico.Usuario.Ubicacion')->get();
        if (!$horario) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }
        return response()->json([
            'Horario' => $horario
        ]);
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
        $diaRepetido = Horario::where('dia', '=', $request->dia)->where('medico_id', '=', $request->medico_id)->first();
        if ($diaRepetido) {
            return response()->json([
                "message" => "Dia ya registrado en el horario"
            ], 500);
        }
        try {
            Horario::create([
                "dia" => $request->dia,
                "hora_inicio" => $request->hora_inicio,
                "hora_fin" => $request->hora_fin,
                "medico_id" => $request->medico_id,
            ]);
            return response()->json(["message" => "horario creado"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $horario = Horario::find($id);
        if (!$horario) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }
        return response()->json([
            'Horario' => $horario
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $horario)
    {
        $validator = Validator::make($request->all(), $this->rulesUpdate, $this->messagesUpdate);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        try {
            $horario = Horario::find($horario);
            $horario->update([
                "hora_inicio" => $request->hora_inicio,
                "hora_fin" => $request->hora_fin,
            ]);
            return response()->json(["message" => "horario actualizado"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($horario)
    {

        try {
            Horario::find($horario)->delete();
            return response()->json([
                "message" => "horario eliminado"
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
