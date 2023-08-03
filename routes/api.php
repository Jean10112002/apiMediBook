<?php

use App\Http\Controllers\EspecialidadeController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\UserController;
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
    Route::post('/register-medico', [MedicoController::class, 'register']);//solo admin registra medico
    Route::apiResource('user',UserController::class)->only('index','update','destroy','show'); //update solo admin,destroy igual e index
    Route::apiResource('especialidades',EspecialidadeController::class)->only('index');
    Route::controller(UserController::class)->group(function () {
        Route::get('user-profile', 'userProfile');
        Route::post('logout',  'logout');
        Route::post('updatePassword', [UserController::class, 'updatePassword']);
        Route::put('updatePasswordAdmin/{id}', [UserController::class, 'updatePasswordAdmin']); //solo admin
    });

});
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [PacienteController::class, 'register']);
