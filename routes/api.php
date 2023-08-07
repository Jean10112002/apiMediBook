<?php

use App\Http\Controllers\EspecialidadeController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\TituloController;
use App\Http\Controllers\UserController;
use App\Models\Horario;
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

    //crud total de citas
    //crud de especilidad
    //crud de recetas
    //crud de calificacion
    //crud de feedback
    Route::controller(UserController::class)->group(function () {
        Route::get('user-profile', 'userProfile');
        Route::post('logout',  'logout');
        Route::post('updatePassword', [UserController::class, 'updatePassword']);
        Route::put('updatePasswordAdmin/{id}', [UserController::class, 'updatePasswordAdmin'])->middleware('onlyAdmin'); //solo admin
    });

});
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [PacienteController::class, 'register']);
