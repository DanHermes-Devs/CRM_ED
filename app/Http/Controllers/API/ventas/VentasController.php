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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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

        if ($rol == 'Agente de Ventas') {
            $resultados = $resultados->where('tVenta', 'VENTA')
                ->where('UGestion', 'VENTA')
                ->where('LoginOcm', 'Agente2');
        } elseif ($rol == 'Agente Renovaciones') {
            $resultados = $resultados->where('tVenta', 'RENOVACION')->where('LoginOcm', $usuario->usuario);
        } elseif ($rol == 'Supervisor' || $rol == 'Coordinador') {
            // No aplicar filtros adicionales para supervisores y coordinadores
        } else {
            // No aplicar filtros adicionales para administradores
        }

        if (request()->ajax()) {
            return DataTables()
                ->of($resultados)
                ->addColumn('action', 'crm.modulos.ventas.actions')
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }

        // Recuperamos todos los usuarios con rol supervisor y lo mandamos a la vista
        $supervisores = User::role('Supervisor')->get();

        // Recuperamos todos los usuario con rol Agente de Ventas y lo mandmos a la vista
        $agentes = User::role('Agente de Ventas')->get();

        return view('crm.modulos.ventas.index', compact('resultados', 'supervisores', 'agentes'));
    }

    public function show($id)
    {
        $venta = Venta::find($id);
        return view('crm.modulos.ventas.show', compact('venta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // Busca si ya existe una venta con el mismo contactId
        $venta = Venta::where('contactId', $request->contactId)->latest('created_at')->first();

        // Si se encuentra una venta existente con el mismo contactId
        if ($venta) {
            // Si la última gestión es 'VENTA'y 'RENOVACION', no permite modificar el campo UGestion
            if ($venta->UGestion !== 'VENTA' && $venta->UGestion !== 'RENOVACION') {
                if ($venta->UGestion !== 'RENOVACION') {
                    $venta->UGestion = $request->UGestion;
                }

                $venta->Fpreventa = Carbon::now();
    
                // Guarda los valores actuales de MesBdd y AnioBdd antes de actualizar el registro
                $currentMesBdd = $venta->MesBdd;
                $currentAnioBdd = $venta->AnioBdd;

                $venta->fill($request->all());

                // Restaura los valores de MesBdd y AnioBdd que se guardaron previamente
                $venta->MesBdd = $currentMesBdd;
                $venta->AnioBdd = $currentAnioBdd;

            } else {
                if ($request->Codificacion === 'RENOVACION') {
                    // Busca si existe una venta de renovación con el mismo nPoliza y tVenta 'RENOVACION'
                    $ventaRenovacion = Venta::where('nSerie', $request->nSerie)
                        ->where('tVenta', 'RENOVACION')
                        ->first();
    
                    if ($ventaRenovacion) {
                        $venta->UGestion = 'RENOVADA' . $ventaRenovacion->MesBdd . $ventaRenovacion->AnioBdd;
                        $venta->tVenta = 'RENOVACION';
                    } else {
                        $venta = new Venta;
                        $venta->contactId = $request->contactId;
                        $venta->UGestion = 'RENOVADA';
                        $venta->Fpreventa = Carbon::now();
                        $venta->tVenta = 'RENOVACION';    
                    }
                } else {
                    return response()->json([
                        'code' => 400,
                        'message' => 'Éste registro ya fue marcado como ' . $venta->UGestion,
                    ]);
                }
            }
        } else {
            // Si no se encuentra una venta existente, crea una nueva instancia del modelo Venta
            $venta = new Venta;
            $venta->contactId = $request->contactId;
            $venta->UGestion = $request->UGestion;
            $venta->Fpreventa = Carbon::now();

            $venta->fill($request->all());

            if($request->Codificacion == 'VENTA'){
                // Busca si existe una venta con el mismo nSerie y tVenta 'VENTA NUEVA'
                $ventaExistente = Venta::where('nSerie', $request->nSerie)
                    ->where('tVenta', 'VENTA')
                    ->first();

                // Obtiene la fecha actual
                $hoy = Carbon::now();
                if ($ventaExistente) {
                    // Calcula la diferencia en días entre la fecha actual y Fpreventa
                    $fpreventa = Carbon::parse($ventaExistente->Fpreventa);
                    $diasDiferencia = $fpreventa->diffInDays($hoy, false);

                    // Aplica las reglas de validación de duplicidad de ventas según la diferencia en días
                    if ($diasDiferencia <= 30) {
                        $venta->tVenta = 'VENTA DUPLICADA';
                    } elseif ($diasDiferencia > 30 && $diasDiferencia < 330) {
                        $venta->tVenta = 'VENTA';
                    } else {
                        $venta->tVenta = 'RENOVACION';
                    }

                } else {
                    // Busca si existe una venta coincidente con RFC, TelCelular y NombreDeCliente
                    $ventaCoincidente = Venta::where('RFC', $request->RFC)
                        ->where('TelCelular', $request->TelCelular)
                        ->where('NombreDeCliente', $request->NombreDeCliente)
                        ->first();

                    // Si se encuentra una coincidencia, asigna 'POSIBLE DUPLICIDAD' al campo tVenta
                    if ($ventaCoincidente) {
                        $venta->tVenta = 'POSIBLE DUPLICIDAD';
                    } else {
                        // Si no hay una venta existente con el mismo nSerie y tVenta 'VENTA NUEVA', asigna tVenta enviado en la solicitud
                        $venta->tVenta = 'VENTA';
                    }
                }
            }
        }

        // Validación de la solicitud y actualización del recibo de pago (Módulo Cobranza)
        if ($request->estado_pago === 'CANCELADO') {
            $subsequentReceipts = Receipt::where('venta_id', $request->venta_id)
                ->where('num_pago', '>', $request->num_pago)
                ->get();

            foreach ($subsequentReceipts as $subsequentReceipt) {
                $subsequentReceipt->update(['estado_pago' => 'CANCELADO']);
            }

            $venta = $request->venta;
            $venta->update(['estado_venta' => 'PAGO CANCELADO']);
        }

        // Verifica si la codificación es 'PROMESA DE PAGO'
        if($request->Codificacion === 'VENTA' || $request->Codificacion === 'RENOVACION'){
            if ($venta->UGestion === 'PROMESA DE PAGO') {
                // Si el LoginOcm en el request es diferente del LoginIntranet en la venta existente
                if ($request->LoginOcm !== $venta->LoginIntranet) {
                    // Asigna el LoginOcm del request al LoginIntranet de la venta
                    $venta->LoginIntranet = $request->LoginOcm;
                }
            }
        }

        // Guarda la venta en la base de datos
        $venta->save();

        // Si tVenta es NUEVA VENTA y tVenta es Renovacion, me crea los recibos de pago, de lo contrario, no hace nada y si $request->FrePago es diferente de null, me crea los recibos de pago
        if($venta->tVenta === 'VENTA NUEVA' || $venta->tVenta === 'RENOVACION' || $venta->FrePago !== null){
            $frecuenciaPago = $request->input('FrePago');
            $this->crearRecibosPago($venta, $frecuenciaPago);
        }

        // Devuelve la venta creada o actualizada en formato JSON
        return response()->json([
            'code' => 200,
            'message' => 'Venta guardada correctamente',
            'data' => $venta
        ]);
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

            // Convertimos frecuenciaPago en Mayusculas
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

                for ($i = 1; $i <= $numRecibos; $i++) {
                    $finVigencia = Carbon::parse($venta->FinVigencia);

                    // Calcular la fecha del próximo pago en función de la frecuencia de pago
                    switch ($frecuenciaPago) {
                        case 'ANUAL':
                            $fechaProximoPago = $finVigencia->addYear();
                            break;
                        case 'SEMESTRAL':
                            $fechaProximoPago = $finVigencia->addMonths(6);
                            break;
                        case 'TRIMESTRAL':
                            $fechaProximoPago = $finVigencia->addMonths(3);
                            break;
                        case 'CUATRIMESTRAL':
                            $fechaProximoPago = $finVigencia->addMonths(4);
                            break;
                        case 'MENSUAL':
                            $fechaProximoPago = $finVigencia->addMonth();
                            break;
                        default:
                            // Si la frecuencia de pago no está definida correctamente, salir del bucle
                            break 2;
                    }

                    $receipt = new Receipt([
                        'venta_id' => $venta->id,
                        'num_pago' => $i,
                        'fre_pago' => $venta->FrePago,
                        'fecha_proximo_pago' => $i > 1 ? $fechaProximoPago : null,
                        'fecha_pago_real' => $venta->Fpreventa,
                        'prima_neta_cobrada' => $venta->PrimaNetaCobrada,
                        'agente_cob_id' => $i == 1 ? $usuario->id : null,
                        'tipo_pago' => $i == $numRecibos ? 'LIQUIDADO' : 'PAGO PARCIAL',
                        'estado_pago' => 'PENDIENTE',
                        'contactId' => $venta->contactId,
                    ]);

                    $receipt->save();
                }
            }
        }
    }


    public function actualizarEstadoRecibosYPago($venta_id, $num_pago)
    {
        // Actualizar los recibos de pago
        Receipt::where('venta_id', $venta_id)
            ->where('num_pago', '>', $num_pago)
            ->update(['estado_pago' => 'CANCELADO']);

        // Actualizar el estado de la venta
        Venta::where('id', $venta_id)
            ->update(['EstadoDePago' => 'CANCELADO PAGO']);
    }
}
