<?php

namespace App\Http\Controllers\API\ventas;

use LDAP\Result;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Venta;
use App\Models\Receipt;
use Illuminate\Http\Request;
use App\Exports\VentasExport;
use App\Imports\VentasImport;
use App\Exports\RegistrosExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\ValidationException;

class VentasController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-ventas|crear-ventas|editar-ventas|borrar-ventas|ver-campos', ['only' => ['index', 'show']]);
        $this->middleware('permission:editar-ventas', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-ventas', ['only' => ['destroy']]);

        $this->middleware('auth')->except('store');
    }

    // Forma de llamar un procedimiento almacenado
    // $results = DB::select("CALL obtener_ventas_totales()");

    // if ($results) {
    //     foreach ($results as $row) {
    //         // Acceder a los datos de cada fila
    //         dump($row->id);
    //     }
    // } else {
    //     // Manejo del error
    //     $error = DB::selectOne('SELECT @@ERROR AS error');
    //     if ($error) {
    //         dump('Error al ejecutar el stored procedure: ' . $error->error);
    //     } else {
    //         dump('Error al ejecutar el stored procedure.');
    //     }
    // }

    public function index(Request $request)
    {
        $query = Venta::query();

        // Búsqueda por fecha de inicio y fin
        if ($request->filled(['fecha_inicio', 'fecha_fin'])) {
            $query->whereBetween('Fpreventa', [$request->fecha_inicio, $request->fecha_fin]);
        }

        // Búsquedas exactas
        $camposExactos = [
            'ContactID' => 'lead',
            'nSerie' => 'numero_serie',
            'nPoliza' => 'numero_poliza',
            'TelFijo' => 'telefono',
            'TelCelular' => 'celular',
            'Nombre' => 'nombre_cliente',
        ];

        foreach ($camposExactos as $campoDb => $campoReq) {
            if ($request->filled($campoReq)) {
                if ($campoReq == 'numero_poliza') {
                    $query->orWhere('nPoliza', $request->$campoReq)
                        ->orWhere('nueva_poliza', $request->$campoReq);
                } else {
                    $query->where($campoDb, $request->$campoReq);
                }
            }
        }

        // Filtramos por agente
        $usuario = User::find($request->user);

        // Búsqueda por tipo de venta
        if ($request->filled('tipo_venta')) {
            $query->where('tVenta', $request->tipo_venta);
        }

        // Búsqueda por mes y año de BDD de renovaciones
        if ($request->filled(['mes_bdd', 'anio_bdd'])) {
            // Implementa la lógica para buscar por mes y año de BDD
            $mes = $request->mes_bdd;
            $anio = $request->anio_bdd;

            $query->where('AnioBdd', $anio)
                ->where('MesBdd', $mes);
        }

        $resultados = $query->get();

        // Filtros por perfil de usuario
        $rol = $request->rol;

        if ($rol == 'Agente Renovaciones') {
            $query->where('tVenta', 'RENOVACION')->where('LoginOcm', $usuario->usuario);
        } elseif ($rol == 'Supervisor' || $rol == 'Coordinador') {
            // No aplicar filtros adicionales para supervisores y coordinadores
        } else {
            // No aplicar filtros adicionales para administradores
        }


        if (request()->ajax()) {
            return DataTables::of($query)
                ->addColumn('action', 'crm.modulos.ventas.actions')
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->toJson();
        }

        // Recuperamos todos los usuarios con rol supervisor y lo mandamos a la vista
        $supervisores = User::role('Supervisor')->get();

        // Recuperamos todos los usuario con rol Agente de Ventas y lo mandmos a la vista
        $agentes = User::role('Agente de Ventas')->get();

        return view('crm.modulos.ventas.index', compact('resultados', 'supervisores', 'agentes'));
    }

    // Metodo para mostrar el formulario de ventas
    public function show($id)
    {
        $venta = Venta::find($id);
        return view('crm.modulos.ventas.show', compact('venta'));
    }

    // Metodo para mostrar el formulario de ventas
    public function store(Request $request)
    {
        if($request->Codificacion === 'VENTA')
        {
            // BUSCAMOS SI EXISTE UNA VENTA CON EL MISMO NUMERO DE CONTACTID
            $venta = Venta::where('contactId', $request->contactId)
                    ->where('UGestion', 'VENTA')
                    ->latest('created_at')->first();

            if($venta)
            {
                return response()->json([
                    'code' => 400,
                    'message' => 'Éste registro ya fue guardado como venta',
                ]);
            }else{
                // BUSCAMOS SI EXISTE UNA VENTA CON EL MISMO NUMERO DE SERIE Y TIPO DE VENTA 'VENTA'
                $ventaExistente = Venta::where('nSerie', $request->nSerie)
                    ->where('tVenta', 'VENTA')
                    ->first();

                // VERIFICAMOS SI EXISTE UNA VENTA CON EL NUMERO DE SERIE Y TIPO DE VENTA 'VENTA'
                if ($ventaExistente) {
                    // OBTENEMOS LA FECHA ACTUAL
                    $hoy = Carbon::now();

                    // CALCULAMOS LA DIFERENCIA EN DIAS ENTRE LA FECHA ACTUAL Y LA FECHA DE PREVENTA
                    $fpreventa = Carbon::parse($ventaExistente->Fpreventa);
                    $diasDiferencia = $fpreventa->diffInDays($hoy, false);

                    if(!$ventaExistente->UGestion === 'PROMESA DE PAGO')
                    {
                        // Aplica las reglas de validación de duplicidad de ventas según la diferencia en días
                        if ($diasDiferencia <= 30) {
                            // SI LA DIFERENCIA DE DIAS ES MENOR O IGUAL A 30, SE CREA UNA VENTA DUPLICADA
                            $ventaDuplicada = new Venta;
                            $ventaDuplicada->contactId = $request->contactId;
                            $ventaDuplicada->fill($request->all());
                            $ventaDuplicada->UGestion = $request->UGestion;
                            $ventaDuplicada->Fpreventa = Carbon::now();
                            $ventaDuplicada->FinVigencia = $request->FinVigencia;
                            $ventaDuplicada->FfVigencia = Carbon::parse($ventaDuplicada->FinVigencia)->addYear();
                            $ventaDuplicada->tVenta = 'VENTA DUPLICADA';
                            $ventaDuplicada->fecha_ultima_gestion = Carbon::now();
                            $ventaDuplicada->aseguradora_vendida = $request->Aseguradora;
                            $ventaDuplicada->save();

                            return response()->json([
                                'code' => 200,
                                'message' => 'Venta guardada correctamente ventaDuplicada',
                                'data' => $ventaDuplicada
                            ]);
                        } elseif ($diasDiferencia > 30 && $diasDiferencia < 330) {
                            // SI LA DIFERENCIA DE DIAS ES MAYOR A 30 Y MENOR A 330, SE CREA UNA VENTA
                            $ventaDiferencia = new Venta;
                            $ventaDiferencia->contactId = $request->contactId;
                            $ventaDiferencia->fill($request->all());
                            $ventaDiferencia->UGestion = $request->UGestion;
                            $ventaDiferencia->Fpreventa = Carbon::now();
                            $ventaDiferencia->FinVigencia = $request->FinVigencia;
                            $ventaDiferencia->FfVigencia = Carbon::parse($ventaDiferencia->FinVigencia)->addYear();
                            $ventaDiferencia->tVenta = 'VENTA';
                            $ventaDiferencia->save();

                            // CREAMOS LOS RECIBOS DE PAGO
                            $frecuenciaPago = $request->input('FrePago');
                            $this->crearRecibosPago($ventaDiferencia, $frecuenciaPago);

                            return response()->json([
                                'code' => 200,
                                'message' => 'Venta guardada correctamente ventaDiferencia',
                                'data' => $ventaDiferencia
                            ]);
                        } elseif ($diasDiferencia > 330) {
                            $ventaNuevaRenovacion = new Venta;
                            $ventaNuevaRenovacion->contactId = $request->contactId;
                            $ventaNuevaRenovacion->fill($request->all());
                            $ventaNuevaRenovacion->UGestion = $request->UGestion;
                            $ventaNuevaRenovacion->Fpreventa = Carbon::now();
                            $ventaNuevaRenovacion->FinVigencia = $request->FinVigencia;
                            $ventaNuevaRenovacion->FfVigencia = Carbon::parse($ventaNuevaRenovacion->FinVigencia)->addYear();
                            $ventaNuevaRenovacion->tVenta = 'RENOVACION';
                            $ventaNuevaRenovacion->save();

                            // CREAMOS LOS RECIBOS DE PAGO
                            $frecuenciaPago = $request->input('FrePago');
                            $this->crearRecibosPago($ventaExistente, $frecuenciaPago);

                            return response()->json([
                                'code' => 200,
                                'message' => 'Venta guardada correctamente ventaNuevaRenovacion',
                                'data' => $ventaNuevaRenovacion
                            ]);
                        }
                    }else{
                        // ACTUALIZAMOS LA PROMESA DE PAGO CON LA NUEVA INFORMACION
                        $ventaExistente->contactId = $request->contactId;
                        $ventaExistente->fill($request->all());
                        $ventaExistente->UGestion = $request->UGestion;
                        $ventaExistente->Fpreventa = Carbon::now();
                        $ventaExistente->FinVigencia = $request->FinVigencia;
                        $ventaExistente->FfVigencia = Carbon::parse($ventaExistente->FinVigencia)->addYear();
                        $ventaExistente->tVenta = 'VENTA';
                        $ventaExistente->fecha_ultima_gestion = Carbon::now();
                        $ventaExistente->save();

                        // CREAMOS LOS RECIBOS DE PAGO
                        $frecuenciaPago = $request->input('FrePago');
                        $this->crearRecibosPago($ventaExistente, $frecuenciaPago);

                        return response()->json([
                            'code' => 200,
                            'message' => 'Venta creada correctamente ventaExistente',
                            'data' => $ventaExistente
                        ]);
                    }
                } else {
                    // Busca si existe una venta coincidente con RFC, TelCelular y NombreDeCliente
                    $ventaCoincidente = Venta::where('RFC', $request->RFC)
                        ->where('TelCelular', $request->TelCelular)
                        ->where('NombreDeCliente', $request->NombreDeCliente)
                        ->first();

                    // Si se encuentra una coincidencia, asigna 'POSIBLE DUPLICIDAD' al campo tVenta
                    if ($ventaCoincidente) {
                        $ventaDuplicadaPosible = new Venta;
                        $ventaDuplicadaPosible->contactId = $request->contactId;
                        $ventaDuplicadaPosible->fill($request->all());
                        $ventaDuplicadaPosible->UGestion = $request->UGestion;
                        $ventaDuplicadaPosible->Fpreventa = Carbon::now();
                        $ventaDuplicadaPosible->FinVigencia = $request->FinVigencia;
                        $ventaDuplicadaPosible->FfVigencia = Carbon::parse($ventaDuplicadaPosible->FinVigencia)->addYear();
                        $ventaDuplicadaPosible->tVenta = 'POSIBLE DUPLICIDAD';
                        $ventaDuplicadaPosible->save();

                        return response()->json([
                            'code' => 200,
                            'message' => 'Venta guardada correctamente ventaDuplicadaPosible',
                            'data' => $ventaDuplicadaPosible
                        ]);
                    } else {
                        $preventaNueva = new Venta;
                        $preventaNueva->contactId = $request->contactId;
                        $preventaNueva->fill($request->all());
                        $preventaNueva->UGestion = $request->UGestion;
                        $preventaNueva->Fpreventa = Carbon::now();
                        $preventaNueva->FinVigencia = $request->FinVigencia;
                        $preventaNueva->FfVigencia = Carbon::parse($preventaNueva->FinVigencia)->addYear();
                        $preventaNueva->tVenta = 'VENTA';
                        $preventaNueva->save();

                        return response()->json([
                            'code' => 200,
                            'message' => 'Venta guardada correctamente preventaNueva',
                            'data' => $preventaNueva
                        ]);

                        // CREAMOS LOS RECIBOS DE PAGO
                        $frecuenciaPago = $request->input('FrePago');
                        $this->crearRecibosPago($ventaExistente, $frecuenciaPago);
                    }
                }
            }
        }else{
            // BUSCAMOS SI EXISTE UNA VENTA CON EL MISMO NUMERO DE CONTACTID
            $venta = Venta::where('contactId', $request->contactId)->latest('created_at')->first();

            // VALIDAMOS SI EXISTE UNA VENTA CON EL MISMO CONTACTID Y APARTE UGESTION ES PROMESA DE PAGO
            if($venta)
            {
                if($venta->UGestion === 'PROMESA DE PAGO' || $venta->UGestion === 'VENTA')
                {
                    return response()->json([
                        'code' => 400,
                        'message' => 'Éste registro ya es una promesa de pago o venta, no se actualizó el registro',
                    ]);
                }else{
                    // ACTUALIZAMOS LA PROMESA DE PAGO CON LA NUEVA INFORMACION
                    $venta->fill($request->all());
                    $venta->UGestion = $request->UGestion;
                    $venta->Fpreventa = Carbon::now();
                    $venta->FinVigencia = $request->FinVigencia;
                    $venta->FfVigencia = Carbon::parse($venta->FinVigencia)->addYear();
                    $venta->tVenta = 'VENTA';
                    $venta->save();

                    return response()->json([
                        'code' => 200,
                        'message' => 'Venta actualizada correctamente venta',
                        'data' => $venta
                    ]);
                }
            }else{
                $promesaPago = new Venta;
                $promesaPago->contactId = $request->contactId;
                $promesaPago->fill($request->all());
                $promesaPago->UGestion = $request->UGestion;
                $promesaPago->Fpreventa = Carbon::now();
                $promesaPago->FinVigencia = $request->FinVigencia;
                $promesaPago->FfVigencia = Carbon::parse($promesaPago->FinVigencia)->addYear();
                $promesaPago->tVenta = 'VENTA';
                $promesaPago->save();

                // CREAMOS LOS RECIBOS DE PAGO
                $frecuenciaPago = $request->FrePago;
                $this->crearRecibosPago($promesaPago, $frecuenciaPago);

                return response()->json([
                    'code' => 200,
                    'message' => 'Venta guardada correctamente promesaPago',
                    'data' => $promesaPago
                ]);
            }
        }
    }

    // Metodo para Crear Recibos de Pago (Módulo Cobranza)
    public function crearRecibosPago($venta, $frecuenciaPago)
    {
        // Verificamos si ya existen recibos de pago para la venta
        $recibos = Receipt::where('venta_id', $venta->id)->count();

        // Si no hay recibos existentes, crea nuevos recibos
        if ($recibos === 0) {
            // Creamos un arreglo con las frecuencias de pago
            $frecuenciaPagos = [
                'ANUAL' => 1,
                'SEMESTRAL' => 2,
                'TRIMESTRAL' => 4,
                'CUATRIMESTRAL' => 3,
                'MENSUAL' => 12
            ];

            // Convertimos frecuenciaPago en mayúsculas
            $frecuenciaPago = strtoupper($frecuenciaPago);

            if (!array_key_exists($frecuenciaPago, $frecuenciaPagos)) {
                $frecuenciaPago = null;
            }

            if ($frecuenciaPago !== null) {
                $numRecibos = $frecuenciaPagos[$frecuenciaPago];
                $usuario = User::where('usuario', $venta->LoginOcm)->first();

                if (!$usuario) {
                    // Mandamos un mensaje de error en el que digamos que el usuario no existe
                    return response()->json([
                        'code' => 500,
                        'message' => 'El usuario no existe'
                    ]);
                }

                $numRecibos = $frecuenciaPagos[$frecuenciaPago];
                $finVigencia = Carbon::parse($venta->FinVigencia);

                $primerReciboAnualAsignado = false;

                for ($i = 1; $i <= $numRecibos; $i++) {
                    $mesesPorRecibo = 12 / $numRecibos; // Cantidad de meses por recibo
                    $fechaProximoPago = $finVigencia->copy()->addMonthsNoOverflow($mesesPorRecibo * $i);

                    $fechaProximoPago = $i == 0 ? $finVigencia : $fechaProximoPago;

                    // Hago que el primer recibo se le asigne el agente que hizo la venta

                    $receipt = new Receipt([
                        'venta_id' => $venta->id,
                        'num_pago' => $i,
                        'fre_pago' => $venta->FrePago,
                        'fecha_proximo_pago' => $i > 1 ? $fechaProximoPago : $finVigencia,
                        'fecha_pago_real' => $venta->Fpreventa,
                        'prima_neta_cobrada' => $venta->PncTotal,
                        'agente_cob_id' => $i == 1 ? $venta->agent->id ?? null : null,
                        'tipo_pago' => $i == $numRecibos ? 'LIQUIDADO' : 'PAGO PARCIAL',
                        'estado_pago' => $i == 1 && $frecuenciaPago != 'ANUAL' && !$primerReciboAnualAsignado ? 'PAGADO' : 'PENDIENTE',
                        'contactId' => $venta->contactId,
                    ]);

                    // Después de asignar el estado de pago, para marcar el primer recibo anual como asignado
                    if ($i == 1 && $frecuenciaPago == 'ANUAL') {
                        $primerReciboAnualAsignado = true;
                    }

                    $receipt->save();
                }
            }
        }
    }

    // Metodo para mostrar el formulario de ventas
    public function form()
    {
        return view('crm.modulos.ventas.form');
    }

    // Metodo para exportar a excel
    public function exportVentas(Request $request)
    {
        $start_date = $request->fecha_inicio;
        $end_date = $request->fecha_fin;

        $query = Venta::query();

        // Búsqueda por fecha de inicio y fin
        if ($request->filled(['fecha_inicio', 'fecha_fin'])) {
            $query->whereBetween('Fpreventa', [$request->fecha_inicio, $request->fecha_fin]);
        }

        // Búsquedas exactas
        $camposExactos = [
            'ContactID' => 'lead',
            'nSerie' => 'numero_serie',
            'nPoliza' => 'numero_poliza',
            'TelCelular' => 'telefono',
            'NombreDeCliente' => 'nombre_cliente',
        ];

        foreach ($camposExactos as $campoDb => $campoReq) {
            if ($request->filled($campoReq)) {
                $query->where($campoDb, $request->$campoReq);
            }
        }

        // Búsqueda por tipo de venta
        if ($request->filled('tipo_venta')) {
            $query->where('tVenta', $request->tipo_venta);
        }

        // Búsqueda por mes y año de BDD de renovaciones
        if ($request->filled(['mes_bdd', 'anio_bdd'])) {
            // Implementa la lógica para buscar por mes y año de BDD
            $mes = $request->mes_bdd;
            $anio = $request->anio_bdd;

            $query->where('AnioBdd', $anio)
                ->where('MesBdd', $mes);
        }

        // Filtros por perfil de usuario
        $rol = $request->rol;

        if ($rol == 'Agente Ventas Nuevas') {
            $query->where('tVenta', 'VENTA')
                ->where('UGestion', 'PREVENTA');
        } elseif ($rol == 'Agente Renovaciones') {
            $query->where('tVenta', 'RENOVACION')
                ->where(function ($q) {
                    $q->where('UGestion', '')->orWhereNull('UGestion');
                });
        } elseif ($rol == 'Supervisor' || $rol == 'Coordinador' || $rol == 'Administrador') {
            // No aplicar filtros adicionales para supervisores y coordinadores
        } else {
            // No aplicar filtros adicionales para administradores
        }

        // Obtén la consulta SQL sin valores
        // $sql = $query->toSql();

        // Obtén los valores de los parámetros
        // $bindings = $query->getBindings();

        // Reemplaza los marcadores de posición con los valores
        // $sqlWithBindings = vsprintf(str_replace('?', '%s', $sql), $bindings);

        // Muestra la consulta SQL completa con valores
        // dd('query: ', $sqlWithBindings);

        return Excel::download(new VentasExport($start_date, $end_date, $query, Auth::user()), 'ventas.xlsx');
    }

    // Vista formulario para importar ventas desde excel
    public function formImportVentas()
    {
        return view('crm.modulos.ventas.form_import');
    }

    // Método para importar ventas desde excel
    public function importVentas(Request $request)
    {
        $rules = [
            'ventas_csv' => 'required|mimes:xlsx,xls,csv'
        ];

        $messages = [
            'required' => 'El campo :attribute es obligatorio.',
            'mimes' => 'El archivo debe ser de tipo: xlsx, xls o csv'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('ventas_csv');

        try {
            Excel::import(new VentasImport, $file);
        } catch (ValidationException $e) {
            $failures = $e->failures();

            // Puedes devolver los errores en un mensaje flash personalizado o como prefieras manejarlos
            $errorMessage = "Se encontraron registros duplicados: ";
            foreach ($failures as $failure) {
                $errorMessage .= "Fila " . $failure->row() . ", ID " . $failure->values()[0] . "; ";
            }

            return redirect()->route('ventas.formImportVentas')->with('error', $errorMessage);
        }

        // Mandamos a la vista el mensaje de que se importaron correctamente las ventas hacia la vista crm.modulos.ventas.form_import
        return redirect()->route('ventas.formImportVentas')->with('success', 'Ventas/Renovaciones importadas correctamente');
    }

    // public function actualizarEstadoRecibosYPago($venta_id, $num_pago)
    // {
    //     // Actualizar los recibos de pago
    //     Receipt::where('venta_id', $venta_id)
    //         ->where('num_pago', '>', $num_pago)
    //         ->update(['estado_pago' => 'CANCELADO']);

    //     // Actualizar el estado de la venta
    //     Venta::where('id', $venta_id)
    //         ->update(['EstadoDePago' => 'CANCELADO PAGO']);
    // }
}
