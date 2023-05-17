<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aseguradoras = Insurance::all();

        if(request()->ajax()){
            return DataTables()
                ->of($aseguradoras)
                ->addColumn('action', 'crm.aseguradoras.actions')
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }

        return view('crm.aseguradoras.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('crm.aseguradoras.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Agregamos las validaciones necesarias
        $request->validate([
            'nombre_aseguradora' => 'required|unique:insurances,nombre_aseguradora',
            'estatus' => 'required'
        ],
        [
            'nombre_aseguradora.required' => 'El campo nombre de la aseguradora es obligatorio',
            'nombre_aseguradora.unique' => 'El nombre de la aseguradora ya existe',
            'estatus.required' => 'El campo estatus es obligatorio'
        ]);

        $insurance = new Insurance;
        // Convertimos el nombre_aseguradora en mayus siempre
        $insurance->nombre_aseguradora = strtoupper($request->nombre_aseguradora);
        $insurance->status = $request->estatus;
        $insurance->save();

        return redirect()->route('aseguradoras.index')->with('success', 'Aseguradora creada correctamente');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function edit(Insurance $insurance, $id)
    {
        $insurance = Insurance::find($id);
        
        return view('crm.aseguradoras.edit', compact('id', 'insurance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Insurance $insurance)
    {
        // Agregamos las validaciones necesarias
        $request->validate([
            'nombre_aseguradora' => 'required',
            'estatus' => 'required'
        ],
        [
            'nombre_aseguradora.required' => 'El campo nombre de la aseguradora es obligatorio',
            'estatus.required' => 'El campo estatus es obligatorio'
        ]);

        $insurance = Insurance::find($request->id);
        // Convertimos el nombre_aseguradora en mayus siempre
        $insurance->nombre_aseguradora = strtoupper($request->nombre_aseguradora);
        $insurance->status = $request->estatus;
        $insurance->save();

        return redirect()->route('aseguradoras.index')->with('success', 'Aseguradora actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Eliminamos el registro
        $insurance = Insurance::find($id);
        $insurance->delete();
        
        // Mandamos un mensaje de exito a la vista index
        return response()->json([
            'code' => 200,
            'success' => 'Registro eliminado correctamente'
        ]);
    }
}
