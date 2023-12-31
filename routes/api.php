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




Route::group(['middleware' => ["auth:sanctum"]], function () {
    Route::post('/register-medico', [MedicoController::class, 'register'])->middleware('onlyAdmin'); //solo admin registra medico
    Route::apiResource('medicos', MedicoController::class)->only('index', 'show', 'update', 'destroy');

    Route::apiResource('user', UserController::class)->only('index', 'update', 'destroy', 'show'); //update solo admin,destroy igual e index
    Route::apiResource('horarios', HorarioController::class)->only('store', 'destroy', 'update', 'show','index');
    Route::apiResource('titulos', TituloController::class)->only('store', 'update', 'destroy', 'show','index');
    Route::apiResource('especialidades', EspecialidadeController::class)->only('index', 'store', 'update', 'destroy', 'show');

    //historial medico de un paciente en especifico
    Route::apiResource('pacientes',PacienteController::class)->only('show');
    Route::get('pacientes-information/{id}',[PacienteController::class,'showPaciente']);
    Route::get('medicos-information/{id}',[MedicoController::class,'showMedico']);
    Route::get('historial-medico/{id}', [PacienteController::class, 'historialmedico']);
    Route::get('paciente-information', [PacienteController::class, 'informacionTotal']);
    //ver mi informacion como paciente y medico todo lo relacionado a mi
    Route::get('medico-information', [MedicoController::class, 'informacionTotal'])->middleware(['onlyAdminMedico']);


    //crud total de citas mi
    Route::apiResource('citas', CitaController::class)->only('index', 'show', 'store', 'update', 'destroy');
    //crud de recetas mi
    Route::apiResource('recetas', RecetaController::class)->only('index', 'show', 'store', 'update', 'destroy');
    //crud de calificacion
    Route::apiResource('resenia', ReseniaController::class)->only('store', 'index', 'show', 'update', 'destroy');
    //crud de pagos mi
    Route::apiResource('pagos', PagoController::class)->only('store', 'show', 'index');
    //crud de comentarios mi
    Route::apiResource('comentarios', ComentarioController::class)->only('index');
    //crud de mensajes mi
    Route::apiResource('mensajes', MensajeController::class)->only('index', 'show', 'store');
    Route::get('mis-mensajes', [MensajeController::class, 'misMensajes']);
    //crud de dudas mi
    Route::apiResource('dudas', DudaController::class)->only('index', 'show', 'store', 'update');
    //crud de replydudas mi
    Route::apiResource('replydudas', ReplyDudaController::class)->only('index', 'show', 'store', 'update');
    //crud de observaciones
    Route::apiResource('observaciones', CitaObservacionController::class)->only('index', 'show', 'store', 'update');
    //crud examenes_medicos mi
    Route::apiResource('examenes-medicos', ExamenesMedicoController::class)->only('index', 'show', 'store', 'update', 'destroy');
    //crud medicamentos mi
    Route::apiResource('medicamentos', MedicamentoController::class)->only('index', 'show', 'store', 'update', 'destroy');
    //crud vacunas mi
    Route::apiResource('vacunas', VacunaController::class)->only('index', 'show', 'store', 'update', 'destroy');
    //crud antecedentes_medicos mi
    Route::apiResource('antecedentes-medicos', AntecedentesMedicoController::class)->only('index', 'show', 'store', 'update', 'destroy');



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
