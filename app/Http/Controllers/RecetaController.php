<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     private $rules = [
        'nombre_medicamento' => 'required|string',
        'cantidad' => 'required|integer',
        'lapso_tiempo' => 'required|string',
        'cita_id' => 'required',
    ];

    private $messages = [
        'nombre_medicamento.required' => 'El campo nombre del medicamento es requerido.',
        'nombre_medicamento.string' => 'El campo nombre del medicamento debe ser una cadena de texto.',
        'cantidad.required' => 'El campo cantidad es requerido.',
        'cantidad.integer' => 'El campo cantidad debe ser un número entero.',
        'lapso_tiempo.required' => 'El campo lapso de tiempo es requerido.',
        'lapso_tiempo.string' => 'El campo lapso de tiempo debe ser una cadena de texto.',
        'cita_id.required' => 'El campo cita es requerido.',
    ];
     private $rulesUpdate = [
        'nombre_medicamento' => 'required|string',
        'cantidad' => 'required|integer',
        'lapso_tiempo' => 'required|string',
    ];

    private $messagesUpdate = [
        'nombre_medicamento.required' => 'El campo nombre del medicamento es requerido.',
        'nombre_medicamento.string' => 'El campo nombre del medicamento debe ser una cadena de texto.',
        'cantidad.required' => 'El campo cantidad es requerido.',
        'cantidad.integer' => 'El campo cantidad debe ser un número entero.',
        'lapso_tiempo.required' => 'El campo lapso de tiempo es requerido.',
        'lapso_tiempo.string' => 'El campo lapso de tiempo debe ser una cadena de texto.',
    ];
    public function __construct()
    {
        $this->middleware(['onlyMedico'])->only('store');
        $this->middleware(['onlyMedico'])->only('destroy');
        $this->middleware(['onlyMedico'])->only('update');
    }
    public function index()
    {
        try {
            $recetas=Receta::with('Cita','Cita.Medico','Cita.Paciente','Cita.Medico.Especialidad','Cita.Medico.Titulo','Cita.Medico.Usuario.Datospersonale','Cita.Paciente.Usuario.Datospersonale')->get();
            return response()->json([
                "recetas"=>$recetas
            ],200);
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

            Receta::create([
                "nombre_medicamento"=>$request->nombre_medicamento,
                "cantidad"=>$request->cantidad,
                "lapso_tiempo"=>$request->lapso_tiempo,
                "cita_id"=>$request->cita_id,
            ]);
            return response()->json([
                "message"=>"receta creada exitosamente"
            ],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $receta)
    {
        try {
            $receta=Receta::whereId($receta)->with('Cita','Cita.Medico','Cita.Paciente','Cita.Medico.Especialidad','Cita.Medico.Titulo','Cita.Medico.Usuario','Cita.Paciente.Usuario')->first();
            if(!$receta){
                return response()->json([
                    "message"=>"Receta no encontrada"
                ],404);
            }
            return response()->json([
                "receta"=>$receta
            ],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $receta)
    {
        $validator = Validator::make($request->all(), $this->rulesUpdate, $this->messagesUpdate);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        try {
            Receta::whereId($receta)->update([
                "nombre_medicamento"=>$request->nombre_medicamento,
                "cantidad"=>$request->cantidad,
                "lapso_tiempo"=>$request->lapso_tiempo,
            ]);
            return response()->json([
                "message"=>"receta actualizada exitosamente"
            ],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($receta)
    {
        try {
            Receta::findOrFail($receta)->delete();

            return response()->json([
                "message"=>"receta eliminada exitosamente"
            ],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
