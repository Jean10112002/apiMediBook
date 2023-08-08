<?php

namespace App\Http\Controllers;

use App\Models\Resenia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReseniaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     private $rulesResenia = array(
        'calificacion' => 'required|numeric|integer|between:1,10',
        'comentario_id' => 'required',
        'cita_id' => 'required',
    );

    // Definimos los mensajes personalizados para cada regla de validación
    private $messages = array(
        'calificacion.required' => 'La Calificacion es requerido.',
        'calificacion.numeric' => 'Solo se permite numeros.',
        'calificacion.integer' => 'Solo numeros enteros.',
        'calificacion.between' => 'La calificación debe estar entre 1 y 10.',
        'comentario_id.required' => 'Es requerido el Comentario_id.',
        'cita_id.required' => 'Se requiere la cita_id.',

    );

     private $rulesReseniaUpdate = array(
        'calificacion' => 'required|numeric|integer|between:1,10',
        'comentario_id' => 'required',
    );

    // Definimos los mensajes personalizados para cada regla de validación
    private $messagesUpdate = array(
        'calificacion.required' => 'La Calificacion es requerido.',
        'calificacion.numeric' => 'Solo se permite numeros.',
        'calificacion.integer' => 'Solo numeros enteros.',
        'calificacion.between' => 'La calificación debe estar entre 1 y 10.',
        'comentario_id.required' => 'Es requerido el Comentario_id.',

    );
    public function __construct()
    {
        $this->middleware(['onlyPaciente'])->only('store');
    }
    public function index()
    {
        //
        $resenia = Resenia::with('Cita','Comentario')->get();
        return response()->json([
            "Resenia"=>$resenia
           ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), $this->rulesResenia, $this->messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        try {
            $resenia=Resenia::create([
                "calificacion" => $request->calificacion,
                "comentario_id" => $request->comentario_id,
                "cita_id" => $request->cita_id,
            ]);
            return response()->json(["message" => "Resenia creada con exito", $resenia], 200);
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
        try {
            $resenia = Resenia::with('Cita', 'Comentario')->findOrFail($id);

            return response()->json([
                "Resenia" => $resenia
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Reseña no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        //
        $validator = Validator::make($request->all(), $this->rulesReseniaUpdate, $this->messagesUpdate);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        try {
            $horario=Resenia::find($id);
            $horario->update([
                "calificacion" => $request->calificacion,
                "comentario_id" => $request->comentario_id,
            ]);
            return response()->json(["message" => "Resenia actualizado"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        try {
            $resenia=Resenia::find($id)->delete();
            return response()->json([
                "message"=>"Resenia eliminado"
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
