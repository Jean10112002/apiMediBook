<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $rules = [
        'titulo' => 'required|string',
        'hora_inicio' => 'required|date_format:H:i',
        'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        'medico_id' => 'required',
    ];

    private $messages = [
        'titulo.required' => 'El campo título es requerido.',
        'titulo.string' => 'El campo título debe ser una cadena de texto.',
        'hora_inicio.required' => 'El campo hora de inicio es requerido.',
        'hora_inicio.date_format' => 'El campo hora de inicio debe tener el formato de hora (HH:MM).',
        'hora_fin.required' => 'El campo hora de fin es requerido.',
        'hora_fin.date_format' => 'El campo hora de fin debe tener el formato de hora (HH:MM).',
        'hora_fin.after' => 'La hora de fin debe ser mayor a la hora de inicio.',
        'medico_id.required' => 'El campo médico es requerido.',
    ];
    private $reglasUpdate = [
        'fecha' => 'required|date|after_or_equal:today',
        'hora_inicio' => 'required|date_format:H:i',
        'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
    ];

    private $mensajesUpdate = [
        'fecha.required' => 'El campo fecha es requerido.',
        'fecha.date' => 'El campo fecha debe ser una fecha válida.',
        'fecha.after_or_equal' => 'La fecha debe ser igual o posterior a hoy.',
        'hora_inicio.required' => 'El campo hora de inicio es requerido.',
        'hora_inicio.date_format' => 'El campo hora de inicio debe tener el formato de hora (HH:MM).',
        'hora_fin.required' => 'El campo hora de fin es requerido.',
        'hora_fin.date_format' => 'El campo hora de fin debe tener el formato de hora (HH:MM).',
        'hora_fin.after' => 'La hora de fin debe ser mayor a la hora de inicio.',
    ];
    public function index()
    {
        try {
            $citas = Cita::with('Paciente', 'Medico', 'EstadoCita', 'Receta', 'Resenia', 'CitaObservacion', 'Pago', 'Medico.Especialidad', 'Medico.Titulo', 'Medico.Usuario.DatosPersonale', 'Paciente.AntecedentesMedico', 'Paciente.Medicamento', 'Paciente.Vacuna', 'Paciente.ExamenesMedico', 'Paciente.Usuario.DatosPersonale')->where('estado', '=', 1)->get();
            return response()->json([
                "citas" => $citas
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

        $user = Auth::guard('sanctum')->user()->id;
        $paciente = Paciente::where('user_id', $user)->first();
        try {
            $citaExistente = Cita::where('titulo', $request->titulo)->where('paciente_id', $paciente->id)->first();
            if ($citaExistente) {
                return response()->json([
                    "message" => "Ya tienes una cita con ese titulo"
                ], 500);
            }

            Cita::create([
                "fecha" => Carbon::now()->toDateString(),
                "titulo" => $request->titulo,
                "hora_inicio" => $request->hora_inicio,
                "hora_fin" => $request->hora_fin,
                "medico_id" => $request->medico_id,
                "paciente_id" => $paciente->id,
            ]);
            return response()->json([
                "message" => "cita creada exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($cita)
    {
        try {
            $cita = Cita::with('Paciente', 'Medico', 'EstadoCita', 'Receta', 'Resenia', 'CitaObservacion', 'Pago', 'Medico.Especialidad', 'Medico.Titulo', 'Medico.Usuario.DatosPersonale', 'Paciente.AntecedentesMedico', 'Paciente.Medicamento', 'Paciente.Vacuna', 'Paciente.ExamenesMedico')->whereId($cita)->where('estado', '=', 1)->first();
            if (!$cita) {
                return response()->json([
                    "message" => "cita no encontrada, no existe"
                ], 404);
            }
            return response()->json([
                "cita" => $cita
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $cita)
    {
        $validator = Validator::make($request->all(), $this->reglasUpdate, $this->mensajesUpdate);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        try {
            $cita = Cita::whereId($cita);
            if ($cita) {
                $fechaCita = Carbon::parse($cita->fecha); // Convierte la fecha de la cita en un objeto Carbon
                $fechaActual = Carbon::now();

                if (!$fechaCita->isBefore($fechaActual)) {
                    return response()->json([
                        "message"=>"la fecha ya pasó, no puedes actualizar"
                    ],500);
                }
            } else {
                return response()->json([
                    "message"=>"No se encontró cita"
                ],404);
            }
            Cita::whereId($cita)->update([
                "fecha" => $request->fecha,
                "hora_inicio" => $request->hora_inicio,
                "hora_fin" => $request->hora_fin,
            ]);
            return response()->json([
                "message" => "cita actualizada exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cita)
    {
        try {
            $cita = Cita::whereId($cita);
            if ($cita) {
                $fechaCita = Carbon::parse($cita->fecha); // Convierte la fecha de la cita en un objeto Carbon
                $fechaActual = Carbon::now();
                if (!$fechaCita->isBefore($fechaActual)) {
                    return response()->json([
                        "message"=>"la fecha ya pasó, no puedes cancelarla"
                    ],500);
                }
            } else {
                return response()->json([
                    "message"=>"No se encontró cita"
                ],404);
            }
            $cita->estado = 0;
            $cita->estado_cita_id = 2;
            $cita->save();
            return response()->json([
                "message" => "cita eliminada exitosamente"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
