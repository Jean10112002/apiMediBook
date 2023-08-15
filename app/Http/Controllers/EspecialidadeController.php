<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class EspecialidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware(['onlyAdmin'])->only('store');
        $this->middleware(['onlyAdmin'])->only('update');
        $this->middleware(['onlyAdmin'])->only('destroy');
    }
    public $rulesEspecialidad = array(
        'nombre' => 'required|string',

    );
    public $mensajes = array(
        'nombre.required' => 'El nombre de la especialidad es requerida',
        'nombre.string' => 'Solo letras.',

    );

    public function index()
    {
        $especialidades = Especialidade::all();
        return response()->json([
            "especialidades" => $especialidades
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), $this->rulesEspecialidad, $this->mensajes);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return response()->json([
                'messages' => $messages
            ], 500);
        }
        $existe = Especialidade::where('nombre', '=', $request->nombre)->first();
        if ($existe) {
            return response()->json([
                "message" => "Ya existe una especialidad con ese nombre, intente con otra"
            ], 500);
        }
        Especialidade::create([

            'nombre' => $request->nombre,
        ]);
        return response()->json([
            'messages' => "Se creo  correctamente"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($especialidade)
    {
        try {
            $dudas = Especialidade::find($especialidade);
            if (!$dudas) {
                return response()->json([
                    "message" => "Especialidad no encontrada"
                ], 404);
            }
            return response()->json([
                "especialidad" => $dudas
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), $this->rulesEspecialidad, $this->mensajes);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return response()->json([
                'messages' => $messages
            ], 500);
        }
        $existe=Especialidade::where('nombre',$request->nombre)->first();
        if($existe){
            return response()->json([
                "message"=>"ya existe una especialidad con ese nombre"
            ],500);
        }
        $especialidad = Especialidade::findOrFail($id);
        $especialidad->update([
            'nombre' => $request->nombre,

        ]);

        return response()->json([
            'messages' => "Se actualizo correctamente"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $especialidad = Especialidade::find($id)->delete();
        return response()->json([
            'messages' => "Se elimino correctamente", 'Especialidad' => $especialidad
        ]);
    }
}
