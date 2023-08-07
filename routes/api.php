<?php

use App\Http\Controllers\AntecedentesMedicoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\CitaObservacionController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\DudaController;
use App\Http\Controllers\EspecialidadeController;
use App\Http\Controllers\ExamenesMedicoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\ReplyDudaController;
use App\Http\Controllers\ReseniaController;
use App\Http\Controllers\TituloController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacunaController;
use App\Models\Horario;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ["auth:sanctum"]], function (){
    Route::post('/register-medico', [MedicoController::class, 'register'])->middleware('onlyAdmin');//solo admin registra medico
    Route::apiResource('medicos',MedicoController::class)->only('index','show','update','destroy');
    Route::apiResource('user',UserController::class)->only('index','update','destroy','show'); //update solo admin,destroy igual e index
    Route::apiResource('horarios',HorarioController::class)->only('store','destroy','update','show');
    Route::apiResource('titulos',TituloController::class)->only('store','update','destroy');
    Route::apiResource('especialidades',EspecialidadeController::class)->only('index','store','update','destroy')->middleware('onlyAdmin');

    //ver mi informacion como paciente y medico todo lo relacionado a mi
    Route::get('paciente-information',[PacienteController::class,'informacionTotal']);
    Route::get('medico-information',[MedicoController::class,'informacionTotal']);

     //historial medico de un paciente en especifico
     Route::get('historial-medico/{id}',[PacienteController::class,'historialmedico']);

    //crud total de citas
    Route::apiResource('citas',CitaController::class)->only('index','show','store','update','destroy');
    //crud de recetas
    Route::apiResource('recetas',RecetaController::class)->only('index','show','store','update','destroy');
    //crud de calificacion
    Route::apiResource('resenia',ReseniaController::class)->only('store','index','show','update','destroy');
    //crud de pagos
    Route::apiResource('pagos',PagoController::class)->only('store','show','index');
    //crud de comentarios
    Route::apiResource('comentarios',ComentarioController::class)->only('index');
    //crud de mensajes
    Route::apiResource('mensajes',MensajeController::class)->only('index','show','store');
    //crud de dudas
    Route::apiResource('dudas',DudaController::class)->only('index','show','store','update');
    //crud de replydudas
    Route::apiResource('replydudas',ReplyDudaController::class)->only('index','show','store','update');
    //crud de observaciones
    Route::apiResource('observaciones',CitaObservacionController::class)->only('index','show','store','update');
    //crud examenes_medicos
    Route::apiResource('examenes-medicos',ExamenesMedicoController::class)->only('index','show','store','update','destroy');
    //crud medicamentos
    Route::apiResource('medicamentos',MedicamentoController::class)->only('index','show','store','update','destroy');
    //crud vacunas
    Route::apiResource('vacunas',VacunaController::class)->only('index','show','store','update','destroy');
    //crud antecedentes_medicos
    Route::apiResource('antecedentes-medicos',AntecedentesMedicoController::class)->only('index','show','store','update','destroy');



    //

    Route::controller(UserController::class)->group(function () {
        Route::get('user-profile', 'userProfile');
        Route::post('logout',  'logout');
        Route::post('updatePassword', [UserController::class, 'updatePassword']);
        Route::put('updatePasswordAdmin/{id}', [UserController::class, 'updatePasswordAdmin'])->middleware('onlyAdmin'); //solo admin
    });

});
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [PacienteController::class, 'register']);
