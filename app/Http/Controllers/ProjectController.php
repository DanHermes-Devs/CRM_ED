<?php

namespace App\Http\Controllers;

use App\Models\Pais;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-proyectos|crear-proyectos|editar-proyectos|borrar-proyectos',['only' => ['index','show']]);
        $this->middleware('permission:crear-proyectos', ['only' => ['create','store']]);
        $this->middleware('permission:editar-proyectos', ['only' => ['edit','update']]);
        $this->middleware('permission:borrar-proyectos', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proyectos = Project::all();
        
        if(request()->ajax()){
            return DataTables()
                ->of($proyectos)
                ->addColumn('action', 'crm.proyectos.actions')
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }

        return view('crm.proyectos.index', compact('proyectos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $paises = Pais::all();
        return view('crm.proyectos.create', compact('paises'));
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
            'proyecto' => 'required',
            'descripcion' => 'required',
            'estatus' => 'required',
            'id_pais' => 'required',
        ], [
            'proyecto.required' => 'El campo proyecto es obligatorio',
            'descripcion.required' => 'El campo descripción es obligatorio',
            'estatus.required' => 'El campo estatus es obligatorio',
            'id_pais.required' => 'El campo país es obligatorio',
        ]);

        $proyecto = new Project;
        $proyecto->proyecto = $request->proyecto;
        $proyecto->descripcion = $request->descripcion;
        $proyecto->estatus = $request->estatus;
        $proyecto->id_pais = $request->id_pais;
        $proyecto->save();

        return redirect()->route('proyectos.index')
            ->with('success', 'Proyecto creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $proyecto = Project::find($id);
        $paises = Pais::all();
        return view('crm.proyectos.edit', compact('proyecto', 'paises'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'proyecto' => 'required',
            'descripcion' => 'required',
            'estatus' => 'required',
            'id_pais' => 'required',
        ], [
            'proyecto.required' => 'El campo proyecto es obligatorio',
            'descripcion.required' => 'El campo descripción es obligatorio',
            'estatus.required' => 'El campo estatus es obligatorio',
            'id_pais.required' => 'El campo país es obligatorio',
        ]);

        $proyecto = Project::find($id);
        $proyecto->proyecto = $request->proyecto;
        $proyecto->descripcion = $request->descripcion;
        $proyecto->estatus = $request->estatus;
        $proyecto->id_pais = $request->id_pais;
        $proyecto->save();

        return redirect()->route('proyectos.index')
            ->with('success', 'Proyecto actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $proyecto = Project::find($id);
        $proyecto->delete();

        return response()->json([
            'code' => 200,
            'success' => 'Proyecto eliminado con éxito'
        ]);
    }
}
