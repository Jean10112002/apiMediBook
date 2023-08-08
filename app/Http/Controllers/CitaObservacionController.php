<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\CitaObservacion;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CitaObservacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $rulesResenia = array(
        'contenido' => 'required|string',
        'cita_id' => 'required',
    );

    // Definimos los mensajes personalizados para cada regla de validación
    private $messages = array(
        'contenido.required' => 'El Contenido es requerido.',
        'contenido.string' => 'Solo texto.',
        'cita_id.required' => 'Es requerido el cita_id.',

    );
    private $rulesReseniaUpdate = array(
        'contenido' => 'required|string',
    );

    // Definimos los mensajes personalizados para cada regla de validación
    private $messagesUpdate = array(
        'contenido.required' => 'El Contenido es requerido.',
        'contenido.string' => 'Solo texto.',
    );

    public function index()
    {
        //
        $CitaObservacion = CitaObservacion::with('Cita')->get();
        return response()->json([
            "CitaObservacion"=>$CitaObservacion
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
            $resenia=CitaObservacion::create([

                "contenido" => $request->contenido,
                "cita_id" => $request->cita_id,
            ]);
            $cita=Cita::where('id',$request->cita_id)->first();
            $cita->update([
                "estado_cita_id"=>3,
            ]);
            return response()->json(["message" => "CitaObservacion creada con exito", $resenia], 200);
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
            $resenia = CitaObservacion::with('Cita')->findOrFail($id);

            return response()->json([
                "Resenia" => $resenia
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'CitaObservacion no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        //
        $validator = Validator::make($request->all(), $this->rulesReseniaUpdate, $this->messagesUpdate);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }

        try {
            $citao=CitaObservacion::find($id);
            $citao->update([
                "contenido" => $request->contenido,
            ]);
            return response()->json(["message" => "CitaObservacion actualizada.", $citao], 200);
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
            $citao=CitaObservacion::find($id)->delete();
            return response()->json([
                "message"=>"Resenia eliminado"
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
