<?php

namespace App\Http\Controllers;

use App\Models\Titulo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TituloController extends Controller
{
    private $rules = array(
        'nombre' => 'required',
        'fecha' => 'required|date|before_or_equal:today',
        'medico_id' => 'required',
    );

    // Definimos los mensajes personalizados para cada regla de validación
    private $messages = array(
        'nombre.required' => 'El nombre es requerido.',
        'fecha.required' => 'La fecha es requerida.',
        'fecha.date' => 'El formato de fecha no es válido.',
        'medico_id.required' => 'El ID del médico es requerido.',
        'fecha.before_or_equal' => 'La fecha no debe ser posterior a la fecha actual.',
    );
    private $rulesUpdate = array(
        'nombre' => 'required',
        'fecha' => 'required|date|before_or_equal:today',
    );

    // Definimos los mensajes personalizados para cada regla de validación
    private $messagesUpdate = array(
        'nombre.required' => 'El nombre es requerido.',
        'fecha.required' => 'La fecha es requerida.',
        'fecha.date' => 'El formato de fecha no es válido.',
        'fecha.before_or_equal' => 'La fecha no debe ser posterior a la fecha actual.',
    );
    public function index()
    {
        //
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
        $tituloRepetido = Titulo::where('nombre', '=', $request->nombre)->where('medico_id','=',$request->medico_id)->first();
        if ($tituloRepetido) {
            return response()->json(["message" => "ya existe ese titulo en tu inventario de titulos"], 500);
        }
        try {
            Titulo::create([
                "nombre" => $request->nombre,
                "fecha" => $request->fecha,
                "medico_id" => $request->medico_id,
            ]);
            return response()->json(["messages" => "titulo creado"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Titulo $titulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$titulo)
    {
        $validator = Validator::make($request->all(), $this->rulesUpdate, $this->messagesUpdate);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        try {
            $titulo=Titulo::find($titulo);
            $titulo->update([
                "nombre" => $request->nombre,
                "fecha" => $request->fecha,
            ]);
            return response()->json(["messages" => "titulo actualizado"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $titulo)
    {
        try {
            $titulo=Titulo::find($titulo)->delete();

            return response()->json(["messages" => "titulo eliminado"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
