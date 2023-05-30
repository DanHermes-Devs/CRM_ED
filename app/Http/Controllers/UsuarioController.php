<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Usuario;
use App\Models\Campaign;
use App\Models\Incident;
use Illuminate\Support\Arr;
use App\Imports\UsersImport;
use App\Models\PersonalFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Facades\Image;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Validator;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class UsuarioController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-usuarios|crear-usuarios|editar-usuarios|borrar-usuarios', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear-usuarios', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-usuarios', ['only' => ['edit', 'update']]);
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

        if (request()->ajax()) {
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
        $roles = Role::pluck('name', 'name')->all();
        
        // Mostramos las campañas
        $campanas = Campaign::all();

        return view('crm.modulo_usuarios.create', compact('roles', 'campanas'));
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
            'password' => 'required',
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
            'id_campana' => $request->id_campana,
            'sexo' => $request->sexo,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'rfc' => $request->rfc,
            'curp' => $request->curp,
            'estado_civil' => $request->estado_civil,
            'no_imss' => $request->no_imss,
            'cr_infonavit' => $request->cr_infonavit,
            'cr_fonacot' => $request->cr_fonacot,
            'tipo_sangre' => $request->tipo_sangre,
            'ba_nomina' => $request->ba_nomina,
            'cta_clabe' => $request->cta_clabe,
            'alergias' => $request->alergias,
            'padecimientos' => $request->padecimientos,
            'tel_casa' => $request->tel_casa,
            'tel_celular' => $request->tel_celular,
            'persona_emergencia' => $request->persona_emergencia,
            'tel_emergencia' => $request->tel_emergencia,
            'esquema_laboral' => $request->esquema_laboral,
            'proyecto_asignado' => $request->proyecto_asignado,
            'turno' => $request->turno,
            'hora_entrada' => $request->hora_entrada,
            'hora_salida' => $request->hora_salida,
            'fecha_ingreso' => $request->fecha_ingreso,
            'fecha_baja' => $request->fecha_baja,
            'motivo_baja' => $request->motivo_baja,
            'observaciones' => $request->observaciones,
        ])->assignRole($request->input('roles'));

        Log::channel('registerUsers')->info('Nuevo usuario registrado: ' . $request->name);

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
        $roles = Role::pluck('name', 'name')->all();
        $usuarioRole = $usuario->roles->pluck('name', 'name')->all();

        // Mostramos las campañas
        $campanas = Campaign::all();

        // Mostramos el proyecto que tiene asignado
        $user = PersonalFile::where('id_usuario', $id)->pluck('id_proyecto');

        return view('crm.modulo_usuarios.edit', compact('usuario', 'roles', 'usuarioRole', 'campanas'));
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

        if (!empty($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $usuario = User::findOrFail($id);
        $usuario->update($input);

        DB::table('model_has_roles')->where('model_id', $id)->delete();

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

    public function formImport()
    {
        // Mndamos a la vista los roles que tenemos
        $roles = Role::all();
        return view('crm.modulo_usuarios.importUsers', compact('roles'));
    }

    public function importUsers(Request $request)
    {
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

    // Metodo para la creacion de los expedientes
    public function createExpediente($id)
    {
        // Mostramos al usuario
        $usuario = User::findOrFail($id);

        // Mostramos el expediente
        $expediente = PersonalFile::where('id_usuario', $id)->first();

        // Mostramos los usuarios que tienen perfil supervisor
        $supervisores = User::role('Supervisor')->get();

        // Mostramos todos los proyectos
        $proyectos = Project::all();

        $roles = Role::pluck('name', 'name')->all();

        return view('crm.modulo_usuarios.crear-expediente', compact('usuario', 'proyectos', 'supervisores', 'roles', 'expediente'));
    }

    // Metodo para almacenar los expedientes
    public function crearExpediente(Request $request)
    {
        // Validamos si ya existe un expediente con el usuario
        $expediente = PersonalFile::where('id_usuario', $request->id_usuario)->first();

        // Obtenemos el usuario
        $usuario = User::findOrFail($request->id_usuario);

        // Obtenemos el proyecto
        $proyecto = Project::findOrFail($request->id_proyecto);

        // Obtenemos el supervisor
        $supervisor = User::findOrFail($request->id_supervisor);

        // Obtenemos el rol
        $rol = Role::where('name', $request->perfil)->pluck('name')->first();

        // Creamos el expediente
        if ($expediente) {
            $expediente->id_proyecto = $proyecto->id;
            $expediente->id_supervisor = $supervisor->id;
            $expediente->perfil = $rol;
        } else {
            // Creamos el expediente
            $expediente = new PersonalFile();
            $expediente->id_usuario = $usuario->id;
            $expediente->id_proyecto = $proyecto->id;
            $expediente->id_supervisor = $supervisor->id;
            $expediente->perfil = $rol;
        }

        // Array de campos con sus respectivas extensiones y nombres
        $campos = [
            'ruta_ine' => ['jpg', 'jpeg', 'png', 'pdf'],
            'ruta_acta_nacimiento' => ['jpg', 'jpeg', 'png', 'pdf'],
            'ruta_curp' => ['jpg', 'jpeg', 'png', 'pdf'],
            'ruta_constancia_fiscal' => ['jpg', 'jpeg', 'png', 'pdf'],
            'ruta_nss' => ['jpg', 'jpeg', 'png', 'pdf'],
            'ruta_comp_estudios' => ['jpg', 'jpeg', 'png', 'pdf'],
            'ruta_comp_domicilio' => ['jpg', 'jpeg', 'png', 'pdf'],
            'ruta_edo_bancario' => ['jpg', 'jpeg', 'png', 'pdf'],
            'ruta_aviso_ret_infonavit' => ['jpg', 'jpeg', 'png', 'pdf'],
            'ruta_aviso_ret_fonacot' => ['jpg', 'jpeg', 'png', 'pdf'],
        ];

        foreach ($campos as $campo => $extensiones) {
            if ($request->hasFile($campo)) {
                $archivo = $request->file($campo);
                $extension = $archivo->getClientOriginalExtension();
                $fecha_actual = date('Y-m-d');
                $carpeta = substr($campo, 5); // Quitamos 'ruta_' del nombre del campo para obtener el nombre de la carpeta

                if (in_array($extension, $extensiones)) {
                    $nombre_archivo = $usuario->usuario . '_' . strtoupper($carpeta) . '_' . $fecha_actual . '.' . $extension;

                    // Verificar el tamaño máximo
                    $tamanio_maximo = 2 * 1024 * 1024; // 2MB

                    if ($archivo->getSize() > $tamanio_maximo) {
                        return back()->with('error', 'El tamaño máximo permitido para los archivos es de 2MB.');
                    }

                    // Guardar el archivo en el almacenamiento público
                    $archivo->storeAs('public/uploads/' . $carpeta . '/' . $usuario->usuario, $nombre_archivo);

                    // Obtener la ruta completa del archivo
                    $ruta_archivo = public_path('storage/uploads/' . $carpeta . '/' . $usuario->usuario . '/' . $nombre_archivo);

                    // Comprimir la imagen (solo para imágenes)
                    if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                        $optimizerChain = OptimizerChainFactory::create();
                        $optimizerChain->optimize($ruta_archivo);
                    }

                    // Asignar el nombre del archivo al campo correspondiente del expediente
                    $expediente->{$campo} = $nombre_archivo;
                }
            }
        }

        $expediente->save();

        // Si el expediente se crea con éxito se muestra un mensaje de éxito
        if ($expediente) {
            return redirect()->route('create.expedient', $usuario->id)->with('success', 'Expediente actualizado con éxito.');
        } else {
            return back()->with('error', 'Ocurrió un error al crear el expediente.');
        }
    }


    // Método para las incidencias
    public function incidents($id)
    {
        $usuario = User::findOrFail($id);
        return view('crm.modulo_usuarios.incidents', compact('usuario'));
    }

    // Método para guardar las incidencias
    public function crearIncidencia(Request $request)
    {
        $incidencia = new Incident;
        $incidencia->id_usuario = $request->id_usuario;
        $incidencia->tipo_incidencia = $request->tipo_incidencia;
        $incidencia->fecha_desde = $request->fecha_desde;
        $incidencia->fecha_hasta = $request->fecha_hasta;
        $incidencia->observaciones = $request->observaciones;
        $incidencia->save();

        return back()->with('success', 'Incidencia guardada con éxito.');
    }
}
