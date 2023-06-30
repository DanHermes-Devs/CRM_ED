<?php

namespace App\Http\Controllers\API\Seguridad;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Adt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Document\Security;
use Spatie\Permission\Models\Role;

class ADTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Adt::query();

        if ($request->filled(['fecha_inicio', 'fecha_fin'])) {
            $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
            $fechaFin = Carbon::parse($request->fecha_fin)->endOfDay();
            $query->whereBetween('fecha_venta', [$fechaInicio, $fechaFin]);
        }
        
        // Filtramos por agente
        $usuario = User::find($request->user);

        $resultados = $query->get();
        // Filtros por perfil de usuario
        $rol = $request->rol;

        if ($rol == 'Agente de Ventas' ) {
            $resultados = $resultados->where('agente_intra', $usuario->usuario);
        } elseif ($rol == 'Supervisor' || $rol == 'Coordinador') {
            // No aplicar filtros adicionales para supervisores y coordinadores
        } else {
            // No aplicar filtros adicionales para administradores
        }

        //RESPUESTA PARA PINTAR LAS QUERYS
        if (request()->ajax()) {
            return DataTables()
                ->of($resultados)
                ->addColumn('action', 'crm.modulos.seguridad.adt.actions')
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }

        return view('crm.modulos.seguridad.adt.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cuentaRegistrada = Adt::where('contact_id', $request->contact_id)->first();

        if($cuentaRegistrada && ($cuentaRegistrada->codification == $request->codification )){
            return response()->json([
                "code" => 200,
                "message" => "La cuenta ya esta registrada con la misma codificación",
            ]);
        }else{
            $adt = new Adt;
            $adt->contact_id = $request->contact_id;
            $adt->fecha_venta = Carbon::now();
            $adt->campana = $request->campana;
            $adt->login_ocm = $request->login_ocm;
            $adt->login_intranet = $request->login_intranet;
            $adt->nombre_agente = $request->nombre_agente;
            $adt->codificacion = $request->codificacion;
            $adt->cuenta_adt = $request->cuenta_adt;
            $adt->cliente_nombre = $request->cliente_nombre;
            $adt->cliente_rfc = $request->cliente_rfc;
            $adt->cliente_telefono = $request->cliente_telefono;
            $adt->cliente_celular = $request->cliente_celular;
            $adt->cliente_correo = $request->cliente_correo;
            $adt->cliente_calle = $request->cliente_calle;
            $adt->cliente_numero = $request->cliente_numero;
            $adt->cliente_cp = $request->cliente_cp;
            $adt->client_colonia = $request->client_colonia;
            $adt->cliente_estado = $request->cliente_estado;
            $adt->cliente_municipio = $request->cliente_municipio;
            $adt->cliente_producto = $request->cliente_producto;
            $adt->cliente_tipo_producto = $request->cliente_tipo_producto;
            $adt->cliente_tipo_equipo = $request->cliente_tipo_equipo;
            $adt->contrato_plazo = $request->contrato_plazo;
            $adt->forma_pago = $request->forma_pago;
            $adt->emergencia_nombre_uno = $request->emergencia_nombre_uno;
            $adt->emergencia_tel_uno = $request->emergencia_tel_uno;
            $adt->emergencia_nombre_dos = $request->emergencia_nombre_dos;
            $adt->emergencia_tel_dos = $request->emergencia_tel_dos;
            $adt->referencia_visual = $request->referencia_visual;
            $adt->estatus_venta = $request->estatus_venta;
            $adt->fecha_instalacion = $request->fecha_instalacion;
            $adt->estatus_instalacion = $request->estatus_instalacion;
            $adt->estatus_post_instalacion = $request->estatus_post_instalacion;
            $adt->usuario_tramitacion = $request->usuario_tramitacion;
            $adt->nombre_tramitador = $request->nombre_tramitador;
            $adt->save();

            return response()->json([
                "code" => 200,
                "message" => "Registro guardado correctamente",
                "data" => $adt
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $adt = ADt::where("id", $id)->first();
        return view('crm.modulos.seguridad.adt.show', compact('adt'));
    }

    public function edit(Request $request, $id)
    {
        $adt = Adt::findOrFail($id);
        $date_now = Carbon::now()->toDateTimeString();
        return view('crm.modulos.seguridad.adt.edit', compact('adt','date_now'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
