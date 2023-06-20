<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Group;
use App\Models\Pais;
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
        // Traemos todas las campañas con estatus activo
        $campanas = Campaign::where('status', 1)->get();

        // Mostrar los paises con estatus activo
        $paises = Pais::where('estatus', 1)->get();

        // Regresamos los usuarios con perfil Supervisor
        $supervisores = User::role('Supervisor')->get();

        return view('crm.grupos.create', compact('campanas', 'paises', 'supervisores'));
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
            'nombre_grupo' => 'required',
            'descripcion' => 'required',
            'estatus' => 'required',
            'turno' => 'required',
            'campaign_id' => 'required',
            'pais_id' => 'required',
        ], [
            'nombre_grupo.required' => 'El campo grupo es obligatorio',
            'descripcion.required' => 'El campo descripción es obligatorio',
            'estatus.required' => 'El campo estatus es obligatorio',
            'turno.required' => 'El campo turno es obligatorio',
            'campaign_id.required' => 'El campo campaña es obligatorio',
            'pais_id.required' => 'El campo país es obligatorio',
        ]);

        $grupo = new Group();

        $grupo->nombre_grupo = $request->nombre_grupo;
        $grupo->descripcion = $request->descripcion;
        $grupo->estatus = $request->estatus;
        $grupo->turno = $request->turno;
        $grupo->campaign_id = $request->campaign_id;
        $grupo->pais_id = $request->pais_id;
        $grupo->save();

        $supervisor_id = $request->input('supervisor');
        if($supervisor_id){
            $supervisor = User::find($supervisor_id);

            // Si el usuario existe y es un supervisor, lo asociamos con el grupo.
            if ($supervisor && $supervisor->hasRole('Supervisor')) {
                $supervisor->groups()->syncWithoutDetaching($grupo->id);
            }
        }

        return redirect()->route('grupos.index')
            ->with('success', 'Grupo creado con éxito.');
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
        // Traemos todas las campañas con estatus activo
        $campanas = Campaign::where('status', 1)->get();

        // Mostrar los paises con estatus activo
        $paises = Pais::where('estatus', 1)->get();

        // Regresamos los usuarios con perfil Supervisor
        $supervisores = User::role('Supervisor')->get();

        return view('crm.grupos.edit', compact('grupo', 'campanas', 'paises', 'supervisores'));
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
        // Buscamos el grupo por el id
        $grupo = Group::find($id);
        
        $request->validate([
            'nombre_grupo' => 'required',
            'descripcion' => 'required',
            'estatus' => 'required',
            'turno' => 'required',
            'campaign_id' => 'required',
            'pais_id' => 'required',
        ], [
            'nombre_grupo.required' => 'El campo grupo es obligatorio',
            'descripcion.required' => 'El campo descripción es obligatorio',
            'estatus.required' => 'El campo estatus es obligatorio',
            'turno.required' => 'El campo turno es obligatorio',
            'campaign_id.required' => 'El campo campaña es obligatorio',
            'pais_id.required' => 'El campo país es obligatorio',
        ]);

        $grupo->nombre_grupo = $request->nombre_grupo;
        $grupo->descripcion = $request->descripcion;
        $grupo->estatus = $request->estatus;
        $grupo->turno = $request->turno;
        $grupo->campaign_id = $request->campaign_id;
        $grupo->pais_id = $request->pais_id;
        $grupo->save();

        $supervisor_id = $request->input('supervisor');
        if($supervisor_id){
            $supervisor = User::find($supervisor_id);

            // Si el usuario existe y es un supervisor, lo asociamos con el grupo.
            if ($supervisor && $supervisor->hasRole('Supervisor')) {
                $supervisor->groups()->syncWithoutDetaching($grupo->id);
            }
        }

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
