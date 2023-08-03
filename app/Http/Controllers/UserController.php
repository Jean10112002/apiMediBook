<?php

namespace App\Http\Controllers;

use App\Models\DatosPersonale;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $rulesUpdateAll = array(
        'nombre' => 'required|string',
        'apellido' => 'required|string',
        'telefono' => 'required|regex:/^[0-9]+$/|digits_between:10,10',
        'canton' => 'required|string',
        'provincia' => 'required|string'
    );

    private $messagesUpdateAll = array(
        'nombre.required' => 'El nombre es requerido.',
        'nombre.string' => 'El nombre debe ser texto.',

        'apellido.required' => 'El apellido es requerido.',
        'apellido.string' => 'El apellido debe ser texto.',
        'telefono.required' => 'El teléfono es requerido.',
        'telefono.regex' => 'El teléfono debe contener solo números.',
        'telefono.digits_between' => 'El teléfono debe tener exactamente 10 dígitos.',

        'canton.required' => 'El cantón es requerido.',
        'canton.string' => 'El cantón debe ser texto.',

        'provincia.required' => 'La provincia es requerida.',
        'provincia.string' => 'La provincia debe ser texto.'
    );
    private  $rulesLogin = array(
        'email' => 'required|email',
        'password' => 'required|min:5|max:10'
    );
    private $messagesLogin = array(
        'email.required' => 'Email es requerido.',
        'email.email' => 'Debe ser un email correcto.',
        'password.required' => 'Debe ingresar una password',
        'password.min' => 'La contraseña debe tener al menos 5 caracteres.',
        'password.max' => 'La contraseña no puede tener más de 10 caracteres.'
    );
    private $rulesUpdatePassword = array(
        'password' => 'required|min:5|max:50',
        'newPassword' => 'required|min:5|max:50',

    );

    private $messagesUpdatePassword = array(
        'password.required' => 'La contraseña es requerida.',
        'password.min' => 'La contraseña debe tener al menos 5 caracteres.',
        'password.max' => 'La contraseña no puede tener más de 50 caracteres.',
        'newPassword.required' => 'La contraseña es requerida.',
        'newPassword.min' => 'La contraseña debe tener al menos 5 caracteres.',
        'newPassword.max' => 'La contraseña no puede tener más de 50 caracteres.',
    );
    private $rulesUpdatePasswordAdmin = array(
        'newPassword' => 'required|min:5|max:50',

    );

    private $messagesUpdatePasswordAdmin = array(

        'newPassword.required' => 'La contraseña es requerida.',
        'newPassword.min' => 'La contraseña debe tener al menos 5 caracteres.',
        'newPassword.max' => 'La contraseña no puede tener más de 50 caracteres.',
    );


    public function index()
    {
        try {
            $pacientes=Paciente::with('Usuario','Usuario.Rol','Usuario.Ubicacion','Usuario.DatosPersonale')->get();
            $medicos=Medico::with('Usuario','Usuario.Rol','Usuario.Ubicacion','Usuario.DatosPersonale')->get();

            return response()->json([
                "pacientes"=>$pacientes,
                "medicos"=>$medicos
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
    public function show($id)
    {
        try {
            $usuarios = User::with('Rol', 'Ubicacion', 'DatosPersonale')->where('id', '=', $id)->first();
            if(!($usuarios)){
                return response()->json([
                    "message"=>"no existe ese usuario"
                ],500);
            }
            return response()->json([
                "usuario" => $usuarios
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), $this->rulesUpdateAll, $this->messagesUpdateAll);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }
        $contador = 0;
        try {
            $user = User::find($id);
            $datosPersonales = DatosPersonale::find($user->datos_personales_id);
            if (!($datosPersonales->telefono == $request->telefono)) {
                $datosPersonales->update([
                    "telefono" => $request->telefono
                ]);
                $contador++;
            }
            $ubicacion = Ubicacion::find($user->ubicacion_id);
            if (!($ubicacion->canton == $request->canton)) {
                $ubicacion->update([
                    "canton" => $request->canton
                ]);
                $contador++;

            }
            if (!($ubicacion->provincia == $request->provincia)) {
                $ubicacion->update([
                    "provincia" => $request->provincia
                ]);
                 $contador++;

            }
            if (!($user->nombre == $request->nombre)) {
                $user->update([
                    "nombre" => $request->nombre,
                ]);
                 $contador++;

            }
            if (!($user->apellido == $request->apellido)) {
                $user->update([
                    "apellido" => $request->apellido
                ]);
                 $contador++;

            }
            if($contador>0){
                return response()->json([
                    "message" => "actualizado correctamente"
                ]);
            }else{
                return response()->json([
                    "message" => "Todos los campos siguen siendo los mismos"
                ],500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            User::find($id)->delete();
            return response()->json([
                "message" => "Usuario eliminado correctamente"
            ]);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rulesLogin, $this->messagesLogin);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }

        $user = User::where("email", "=", $request->email)->first();
        if (isset($user->id)) {
            if (Hash::check($request->password, $user->password)) {
                //creamos el token
                $token = $user->createToken("auth_token")->plainTextToken;
                //si está todo ok
                return response()->json([

                    "messages" => "¡Usuario logueado exitosamente!",
                    "access_token" => $token
                ], 200);
            } else {
                return response()->json([
                    "status" => 0,
                    "error" => "credenciales incorrectas",
                ], 500);
            }
        } else {
            return response()->json([
                "status" => 0,
                "error" => "Usuario no registrado",
            ], 404);
        }
    }

    public function userProfile()
    {
        $id = Auth::guard('sanctum')->user()->id;
        $usuario=User::with('Rol','DatosPersonale','Ubicacion')->whereId($id)->get();

        return response()->json([
            "msg" => "Acerca del perfil de usuario",
            "user" => $usuario,
            /* "usuario"=>$user, */
        ]);
    }

    public function logout()
    {
        /*  auth()->user()->tokens()->delete(); */
        Auth::guard('sanctum')->user()->tokens()->delete();
        return response()->json([
            "status" => 1,
            "messages" => "Cierre de Sesión",
        ]);
    }
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rulesUpdatePassword, $this->messagesUpdatePassword);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }

        try {
            $user = User::find(Auth::guard('sanctum')->user()->id);
            if (!$user) {
                return response()->json(['message'=>'Usuario no encontrado'],500);
            }
            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['message'=>'Contraseñas incorrecta'],500);
            }
            if ($request->password == $request->newPassword) {
                return response()->json(['message'=> 'Contraseñas son las mismas a cambiar'],500);
            }
            $user->password = Hash::make($request->input('newPassword'));
            $user->save();
            $user->tokens()->delete();
            return response()->json(['message'=> 'Contraseña actualizada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updatePasswordAdmin(Request $request,$id)
    {
        $validator = Validator::make($request->all(), $this->rulesUpdatePasswordAdmin, $this->messagesUpdatePasswordAdmin);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["messages" => $messages], 500);
        }

        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message'=> 'Usuario no encontrado'],500);
            }
            if (Hash::check($request->newPassword, $user->password)) {
                return response()->json(['message'=> 'Contraseñas son las mismas a cambiar'],500);
            }
            $user->password = Hash::make($request->input('newPassword'));
            $user->save();
            $user->tokens()->delete();
            return response()->json(['message'=> 'Contraseña actualizada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
