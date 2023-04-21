<?php

namespace App\Http\Controllers;

use App\Models\Pais;
use Illuminate\Http\Request;

class PaisController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-paises|crear-paises|editar-paises|borrar-paises',['only' => ['index','show']]);
        $this->middleware('permission:crear-paises', ['only' => ['create','store']]);
        $this->middleware('permission:editar-paises', ['only' => ['edit','update']]);
        $this->middleware('permission:borrar-paises', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paises = Pais::all();
        
        if(request()->ajax()){
            return DataTables()
                ->of($paises)
                ->addColumn('action', 'crm.paises.actions')
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }

        return view('crm.paises.index', compact('paises'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('crm.paises.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'pais' => 'required',
            'descripcion' => 'required',
            'estatus' => 'required',
        ], [
            'pais.required' => 'El campo país es obligatorio',
            'descripcion.required' => 'El campo descripción es obligatorio',
            'estatus.required' => 'El campo estatus es obligatorio',
        ]);

        $pais = new Pais;
        $pais->pais = $request->pais;
        $pais->descripcion = $request->descripcion;
        $pais->estatus = $request->estatus;
        $pais->save();

        return redirect()->route('paises.index')
            ->with('success', 'País creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pais  $pais
     * @return \Illuminate\Http\Response
     */
    public function show(Pais $pais)
    {
        $pais = Pais::find($pais->id);
        return view('crm.paises.show', compact('pais'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pais  $pais
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pais = Pais::find($id);
        return view('crm.paises.edit', compact('pais'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pais  $pais
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'pais' => 'required',
            'descripcion' => 'required',
            'estatus' => 'required',
        ], [
            'pais.required' => 'El campo país es obligatorio',
            'descripcion.required' => 'El campo descripción es obligatorio',
            'estatus.required' => 'El campo estatus es obligatorio',
        ]);

        $pais = Pais::find($id);
        $pais->pais = $request->pais;
        $pais->descripcion = $request->descripcion;
        $pais->estatus = $request->estatus;
        $pais->save();

        return redirect()->route('paises.index')
            ->with('success', 'País actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pais  $pais
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pais = Pais::find($id);
        $pais->delete();

        // Mandamos un mensaje de exito a la vista index
        return response()->json([
            'code' => 200,
            'success' => 'País eliminado con éxito.'
        ]);
    }
}
