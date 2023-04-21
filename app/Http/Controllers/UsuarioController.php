<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Usuario;
use Illuminate\Support\Arr;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-usuarios|crear-usuarios|editar-usuarios|borrar-usuarios',['only' => ['index','show']]);
        $this->middleware('permission:crear-usuarios', ['only' => ['create','store']]);
        $this->middleware('permission:editar-usuarios', ['only' => ['edit','update']]);
        $this->middleware('permission:borrar-usuarios', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::with('roles')->get();

        if(request()->ajax()){
            return DataTables()
                ->of($usuarios)
                ->addColumn('action', 'crm.modulo_usuarios.actions')
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }
        
        return view('crm.modulo_usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('crm.modulo_usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'usuario' => 'required|string',
            'name' => 'required',
            'apellido_paterno' => 'required|string',
            'apellido_materno' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/',
            'estatus' => 'required',
            'roles' => 'required',
        ], [
            'password.regex' => 'Usa al menos 8 caracteres alfanuméricos alternando entre mayúsculas, minúsculas.',
            'usuario.required' => 'Dato requerido: Usuario.',
            'name.required' => 'Dato requerido: Nombre.',
            'apellido_paterno.required' => 'Dato requerido: Apellido Paterno.',
            'apellido_materno.required' => 'Dato requerido: Apellido Materno.',
            'email.required' => 'Dato requerido: Correo Electrónico.',
            'estatus.required'  => 'Dato requerido: Estatus.',
            'roles.required'  => 'Dato requerido: Rol.',
        ]);

        User::create([
            'usuario' => $request->usuario,
            'name' => $request->name,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'estatus' => $request->estatus,
        ])->assignRole($request->input('roles'));

        return redirect()->route('usuarios')
            ->with('success', 'Usuario creado con éxito.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::pluck('name','name')->all();
        $usuarioRole = $usuario->roles->pluck('name','name')->all();

        return view('crm.modulo_usuarios.edit', compact('usuario', 'roles', 'usuarioRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'usuario' => 'required|string',
            'name' => 'required',
            'apellido_paterno' => 'required|string',
            'apellido_materno' => 'required|string',
            'email' => 'required|string|email',
            'estatus' => 'required',
            'roles' => 'required',
        ], [
            'usuario.required' => 'Dato requerido: Usuario.',
            'name.required' => 'Dato requerido: Nombre.',
            'apellido_paterno.required' => 'Dato requerido: Apellido Paterno.',
            'apellido_materno.required' => 'Dato requerido: Apellido Materno.',
            'email.required' => 'Dato requerido: Correo Electrónico.',
            'estatus.required'  => 'Dato requerido: Estatus.',
            'roles.required'  => 'Dato requerido: Rol.',
        ]);

        $input = $request->all();

        if(!empty($input['password'])){
            $input['password'] = bcrypt($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $usuario = User::findOrFail($id);
        $usuario->update($input);

        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $usuario->assignRole($request->input('roles'));

        return redirect()->route('usuarios')
            ->with('success', 'Usuario actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        // Mandamos una respeusta json
        return response()->json([
            'code' => 200,
            'success' => 'Usuario eliminado con éxito.'
        ]);
    }

    public function formImport(){
        // Mndamos a la vista los roles que tenemos
        $roles = Role::all();
        return view('crm.modulo_usuarios.importUsers', compact('roles'));
    }

    public function importUsers(Request $request){
        $rules = [
            'rol' => 'required',
            'users_csv' => 'required|mimes:xlsx,xls,csv',
        ];

        $messages = [
            'rol.required' => 'El campo rol es obligatorio.',
            'required' => 'El campo archivo es obligatorio.',
            'mimes' => 'El archivo debe ser de tipo: xlsx, xls o csv',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Regresamos a la vista con los errores
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('users_csv');
        $rol = $request->rol;

        // Mandamos al excel el archivo y el rol
        Excel::import(new UsersImport($rol), $file);
        
        return back()->with('success', 'Usuarios importados con éxito.');
    }
}
