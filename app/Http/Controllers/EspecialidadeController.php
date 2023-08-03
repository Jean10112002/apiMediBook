<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use Illuminate\Http\Request;

class EspecialidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $especialidades=Especialidade::all();
       return response()->json([
        "especialidades"=>$especialidades
       ]);
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
    public function show(Especialidade $especialidade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Especialidade $especialidade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Especialidade $especialidade)
    {
        //
    }
}
