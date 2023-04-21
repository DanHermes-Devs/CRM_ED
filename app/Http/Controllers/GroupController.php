<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-grupos|crear-grupos|editar-grupos|borrar-grupos',['only' => ['index','show']]);
        $this->middleware('permission:crear-grupos', ['only' => ['create','store']]);
        $this->middleware('permission:editar-grupos', ['only' => ['edit','update']]);
        $this->middleware('permission:borrar-grupos', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grupos = Group::all();
        
        if(request()->ajax()){
            return DataTables()
                ->of($grupos)
                ->addColumn('action', 'crm.grupos.actions')
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }

        return view('crm.grupos.index', compact('grupos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $proyectos = Project::all();
        $usuarios = User::all();
        // Traemos todos los usuarios con role de supervisor
        $supervisores = User::role('Supervisor')->get();

        // Traemos todos los usuarios con role Agente de Cobranza, Agente de Ventas, Agente Renovaciones
        $agentes = User::role(['Agente de Cobranza', 'Agente de Ventas', 'Agente Renovaciones'])->get();

        return view('crm.grupos.create', compact('proyectos', 'usuarios', 'supervisores', 'agentes'));
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
            'grupo' => 'required',
            'descripcion' => 'required',
            'estatus' => 'required',
            'proyecto' => 'required',
            'usuarios' => 'required',
            'supervisor' => 'required',
        ], [
            'grupo.required' => 'El campo grupo es obligatorio',
            'descripcion.required' => 'El campo descripción es obligatorio',
            'estatus.required' => 'El campo estatus es obligatorio',
            'proyecto.required' => 'El campo proyecto es obligatorio',
            'usuarios.required' => 'El campo usuarios es obligatorio',
            'supervisor.required' => 'El campo supervisor es obligatorio',
        ]);

        $grupo = new Group();

        $grupo->grupo = $request->grupo;
        $grupo->descripcion = $request->descripcion;
        $grupo->estatus = $request->estatus;
        $grupo->id_project = $request->proyecto;
        $grupo->id_user = json_encode($request->usuarios);
        $grupo->id_supervisor = $request->supervisor;
        $grupo->save();

        return redirect()->route('grupos.index')
            ->with('success', 'Grupo creado con éxito.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $grupo = Group::find($id);
        $proyectos = Project::all();
        $usuarios = User::all();

        $json_users = json_decode($grupo->id_user);

        return view('crm.grupos.edit', compact('grupo', 'proyectos', 'usuarios', 'json_users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'grupo' => 'required',
            'descripcion' => 'required',
            'estatus' => 'required',
        ], [
            'grupo.required' => 'El campo grupo es obligatorio',
            'descripcion.required' => 'El campo descripción es obligatorio',
            'estatus.required' => 'El campo estatus es obligatorio',
        ]);

        $grupo = new Group();

        $grupo->grupo = $request->grupo;
        $grupo->descripcion = $request->descripcion;
        $grupo->estatus = $request->estatus;
        $grupo->id_project = $request->proyecto;
        $grupo->id_user = json_encode($request->usuarios);
        $grupo->save();

        return redirect()->route('grupos.index')
            ->with('success', 'Grupo actualizado con éxito.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $grupo = Group::find($id);
        $grupo->delete();

        // Mandamos un mensaje de exito a la vista index
        return response()->json([
            'code' => 200,
            'success' => 'Grupo eliminado con éxito.'
        ]);
    }
}
