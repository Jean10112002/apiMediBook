<?php

namespace App\Http\Controllers;

use App\Models\DatosPersonale;
use App\Models\Medico;
use App\Models\Ubicacion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicoController extends Controller
{
    private $rulesRegister = array(
        'nombre' => 'required|string',
        'apellido' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|min:5|max:10',
        'telefono' => 'required|regex:/^[0-9]+$/|digits_between:10,10',
        'fecha' => 'required|date',
        'ci' => 'required|regex:/^[0-9]+$/|digits_between:10,10',
        'canton' => 'required|string',
        'provincia' => 'required|string'
    );

    private $messagesRegister = array(
        'nombre.required' => 'El nombre es requerido.',
        'nombre.string' => 'El nombre debe ser texto.',

        'apellido.required' => 'El apellido es requerido.',
        'apellido.string' => 'El apellido debe ser texto.',

        'email.required' => 'El email es requerido.',
        'email.email' => 'Debe ser un email correcto.',

        'password.required' => 'La contraseña es requerida.',
        'password.min' => 'La contraseña debe tener al menos 5 caracteres.',
        'password.max' => 'La contraseña no puede tener más de 10 caracteres.',

        'telefono.required' => 'El teléfono es requerido.',
        'telefono.regex' => 'El teléfono debe contener solo números.',
        'telefono.digits_between' => 'El teléfono debe tener exactamente 10 dígitos.',

        'fecha.required' => 'La fecha es requerida.',
        'fecha.date' => 'Debe ser una fecha válida.',

        'ci.required' => 'La cédula es requerida.',
        'ci.regex' => 'La cédula debe contener solo números.',
        'ci.digits_between' => 'La cédula debe tener exactamente 10 dígitos.',

        'canton.required' => 'El cantón es requerido.',
        'canton.string' => 'El cantón debe ser texto.',

        'provincia.required' => 'La provincia es requerida.',
        'provincia.string' => 'La provincia debe ser texto.'
    );
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Medico $medico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medico $medico)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medico $medico)
    {
        //
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rulesRegister, $this->messagesRegister);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        $user = User::where("email", "=", $request->email)->first();
        if($user){
            return response()->json([
                "error"=>"Correo ya utilizado"
            ],500);
        }
        $ci = DatosPersonale::where("ci", "=", $request->ci)->first();
        if($ci){
            return response()->json([
                "error"=>"Cedula ya utilizada"
            ],500);
        }
        $telefono = DatosPersonale::where("telefono", "=", $request->telefono)->first();
        if($telefono){
            return response()->json([
                "error"=>"Telefono ya utilizado"
            ],500);
        }
        try{
           // Suponiendo que recibes la fecha en un campo llamado "fecha_nacimiento" del request.
           $fechaNacimiento = $request->input('fecha');

           // Parsea la fecha usando Carbon para que puedas trabajar con ella como objeto Carbon.
           $fechaNacimientoCarbon = Carbon::parse($fechaNacimiento);

           // Obtiene la fecha actual en formato Carbon.
           $fechaActual = Carbon::now();

           // Calcula la edad restando la fecha de nacimiento de la fecha actual y obteniendo los años.
           $edad = $fechaActual->diffInYears($fechaNacimientoCarbon);
            $datosPersonales=DatosPersonale::create([
                "telefono"=>$request->telefono,"edad"=>$edad,"fecha"=>$request->fecha,"ci"=>$request->ci
            ]);
            $ubicacion=Ubicacion::create([
                "canton"=>$request->canton,"provincia"=>$request->provincia
            ]);
            $userNuevo=User::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                "rol_id" => "2",
                "datos_personales_id" => $datosPersonales->id,
                "ubicacion_id" => $ubicacion->id
            ]);
            Medico::create([
                "user_id"=>$userNuevo->id,
                "especialidad_id"=>1
            ]);
            return response()->json([
                "status" => 1,
                "messages" => "¡Registro de usuario exitoso!",
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
