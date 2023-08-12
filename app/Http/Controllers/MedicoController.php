<?php

namespace App\Http\Controllers;

use App\Models\DatosPersonale;
use App\Models\Medico;
use App\Models\Ubicacion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MedicoController extends Controller
{
    private $rulesRegister = array(
        'nombre' => 'required|string',
        'apellido' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|min:5|max:10',
        'telefono' => 'required|regex:/^[0-9]+$/|digits_between:10,10',
        'fecha' => 'required|date|before_or_equal:today',
        'ci' => 'required|regex:/^[0-9]+$/|digits_between:10,10',
        'canton' => 'required|string',
        'provincia' => 'required|string',
        'especialidad_id' => 'required'
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
        'provincia.string' => 'La provincia debe ser texto.',
        'especialidad_id.required' => 'La especialidad es requerida.',
    );
    private $rulesRegisterUpdate = array(
        'nombre' => 'required|string',
        'apellido' => 'required|string',
        'fecha' => 'required|date',
        'canton' => 'required|string',
        'provincia' => 'required|string'
    );

    private $messagesUpdate = array(
        'nombre.required' => 'El nombre es requerido.',
        'nombre.string' => 'El nombre debe ser texto.',

        'apellido.required' => 'El apellido es requerido.',
        'apellido.string' => 'El apellido debe ser texto.',

        'fecha.required' => 'La fecha es requerida.',
        'fecha.date' => 'Debe ser una fecha válida.',

        'canton.required' => 'El cantón es requerido.',
        'canton.string' => 'El cantón debe ser texto.',

        'provincia.required' => 'La provincia es requerida.',
        'provincia.string' => 'La provincia debe ser texto.'
    );
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('onlyAdmin')->only('update');
        $this->middleware('onlyAdmin')->only('destroy');
    }
    public function index()
    {
        $medicos = Medico::with('Usuario', 'Usuario.Rol', 'Usuario.DatosPersonale', 'Usuario.Ubicacion', 'Especialidad', 'Cita', 'Horario', 'Titulo', 'Pago')->get();
        return response()->json(["medicos" => $medicos]);
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
    public function show($medico)
    {
        $medico = Medico::with('Usuario', 'Usuario.Rol', 'Usuario.DatosPersonale', 'Usuario.Ubicacion', 'Especialidad', 'Cita', 'Horario', 'Titulo', 'Pago')->whereId($medico)->first();
        if (!$medico) {
            return response()->json([
                "message" => "Medico no encontrado"
            ], 500);
        }
        return response()->json([
            "medico" => $medico
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->rulesRegisterUpdate, $this->messagesUpdate);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        try {
            $medico = Medico::find($id);
            $fechaNacimiento = $request->input('fecha');

            // Parsea la fecha usando Carbon para que puedas trabajar con ella como objeto Carbon.
            $fechaNacimientoCarbon = Carbon::parse($fechaNacimiento);

            // Obtiene la fecha actual en formato Carbon.
            $fechaActual = Carbon::now();

            // Calcula la edad restando la fecha de nacimiento de la fecha actual y obteniendo los años.
            $edad = $fechaActual->diffInYears($fechaNacimientoCarbon);
            $medico->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'fecha' => $request->fecha,
                'canton' => $request->canton,
                'provincia' => $request->provincia,
                'edad' => $edad
            ]);
            return response()->json(["message" => "medico actualizado"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $medico)
    {
        try {
            Medico::find($medico)->delete();
            return response()->json(["message" => "medico eliminado"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rulesRegister, $this->messagesRegister);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        $user = User::where("email", "=", $request->email)->first();
        if ($user) {
            return response()->json([
                "error" => "Correo ya utilizado"
            ], 500);
        }
        $ci = DatosPersonale::where("ci", "=", $request->ci)->first();
        if ($ci) {
            return response()->json([
                "error" => "Cedula ya utilizada"
            ], 500);
        }
        $telefono = DatosPersonale::where("telefono", "=", $request->telefono)->first();
        if ($telefono) {
            return response()->json([
                "error" => "Telefono ya utilizado"
            ], 500);
        }
        $nombre = User::where("nombre", "=", $request->nombre)->first();
        if ($nombre) {
            if ($nombre->apellido == $request->apellido) {
                return response()->json([
                    "error" => "Nombres y apellidos ya utilizados"
                ], 500);
            }
        }
        try {
            // Suponiendo que recibes la fecha en un campo llamado "fecha_nacimiento" del request.
            $fechaNacimiento = $request->input('fecha');

            // Parsea la fecha usando Carbon para que puedas trabajar con ella como objeto Carbon.
            $fechaNacimientoCarbon = Carbon::parse($fechaNacimiento);

            // Obtiene la fecha actual en formato Carbon.
            $fechaActual = Carbon::now();

            // Calcula la edad restando la fecha de nacimiento de la fecha actual y obteniendo los años.
            $edad = $fechaActual->diffInYears($fechaNacimientoCarbon);
            $datosPersonales = DatosPersonale::create([
                "telefono" => $request->telefono, "edad" => $edad, "fecha" => $request->fecha, "ci" => $request->ci
            ]);
            $ubicacion = Ubicacion::create([
                "canton" => $request->canton, "provincia" => $request->provincia
            ]);
            $userNuevo = User::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                "rol_id" => "2",
                "datos_personales_id" => $datosPersonales->id,
                "ubicacion_id" => $ubicacion->id
            ]);
            Medico::create([
                "user_id" => $userNuevo->id,
                "especialidad_id" => $request->especialidad_id
            ]);
            return response()->json([
                "status" => 1,
                "messages" => "¡Registro de usuario exitoso!",
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function informacionTotal()
    {
        try {
            $usuario = Auth::guard('sanctum')->user();
            $medic = Medico::where('user_id', $usuario->id)->first();
            $medico = Medico::whereId($medic->id)->with(
                'Cita.CitaObservacion',
                'Usuario',
                'Usuario.Rol',
                'Usuario.DatosPersonale',
                'Usuario.Ubicacion',
                'Especialidad',
                'Cita',
                'Cita.Medico',
                'Cita.Medico.Usuario',
                'Cita.Medico.Especialidad',
                'Cita.Medico.Usuario.Rol',
                'Cita.Medico.Usuario.Ubicacion',
                'Cita.Medico.Usuario.DatosPersonale',
                'Cita.Paciente',
                'Cita.Paciente.Usuario',
                'Cita.Paciente.Usuario.Rol',
                'Cita.Paciente.Usuario.DatosPersonale',
                'Cita.Paciente.Usuario.Ubicacion',
                'Cita.EstadoCita',
                'Horario',
                'Horario.Medico',
                'Horario.Medico.Especialidad',
                'Horario.Medico.Usuario',
                'Horario.Medico.Usuario.Rol',
                'Horario.Medico.Usuario.DatosPersonale',
                'Horario.Medico.Usuario.Ubicacion',
                'Titulo',
                'Titulo.Medico',
                'Titulo.Medico.Especialidad',
                'Titulo.Medico.Usuario',
                'Titulo.Medico.Usuario.Rol',
                'Titulo.Medico.Usuario.DatosPersonale',
                'Titulo.Medico.Usuario.Ubicacion',
                'Pago',
                'Pago.Cita',
                'Pago.Cita.Medico',
                'Pago.Cita.Medico.Usuario',
                'Pago.Cita.Medico.Usuario.Rol',
                'Pago.Cita.Medico.Usuario.DatosPersonale',
                'Pago.Cita.Medico.Usuario.Ubicacion',
                'Pago.Cita.Medico.Especialidad',
                'Pago.Cita.Paciente',
                'Pago.Cita.Paciente.Usuario',
                'Pago.Cita.Paciente.Usuario.Rol',
                'Pago.Cita.Paciente.Usuario.DatosPersonale',
                'Pago.Cita.Paciente.Usuario.Ubicacion',
                'Pago.Cita.EstadoCita',
                'Pago.Medico',
                'Pago.Medico.Especialidad',
                'Pago.Medico.Usuario',
                'Pago.Medico.Usuario.Rol',
                'Pago.Medico.Usuario.DatosPersonale',
                'Pago.Medico.Usuario.Ubicacion',
                'Pago.Paciente',
                'Pago.Paciente.Usuario',
                'Pago.Paciente.Usuario.Rol',
                'Pago.Paciente.Usuario.DatosPersonale',
                'Pago.Paciente.Usuario.Ubicacion',
                'Cita.Receta',
                'Cita.Resenia.Comentario'
            )->first();

            return response()->json(['Informacion' => $medico]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
