<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Receipt;
use App\Models\ReceiptLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\ventas\VentasController;

class CobranzaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-cobranza|crear-cobranza|editar-cobranza|borrar-cobranza',['only' => ['index','show']]);
        $this->middleware('permission:crear-cobranza', ['only' => ['create','store']]);
        $this->middleware('permission:editar-cobranza', ['only' => ['edit','update']]);
        $this->middleware('permission:borrar-cobranza', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $agentes_ventas = User::whereHas('roles', function ($q) {
            $q->where('name', 'Agente de Cobranza');
            //   ->orWhere('name', 'Supervisor');
        })->get();

        $tipoRecibos = $request->get('tipo_recibos', 'TODOS');
        $estado_pago = $request->get('estado_pago');
        $start_date = $request->get('fecha_pago_1');
        $end_date = $request->get('fecha_pago_2');
        $recibos = $this->filtrarRecibos($tipoRecibos, $estado_pago, $start_date, $end_date);

        // Relacionamos los recibos con los usuarios para sacar el nombre del agente
        $recibos->load('venta');
        $recibos->Load('agente_cob');

        if(request()->ajax()){
            return DataTables()
                ->of($recibos)
                ->addColumn('agente_cob_id', function($data){
                    $button = '';
                    if ($data->agente_cob_id === null) {
                        return '<span class="badge rounded-pill badge-soft-danger badge-border">Sin asignar</span>';
                    }else{
                        $usuario = User::find($data->agente_cob_id);
                        return '<span class="badge rounded-pill badge-soft-success badge-border"> Asignado a '.$usuario->usuario.'</span>';
                    }
                })
                ->addColumn('action', function($data){
                    $button = ''; // Inicializa la variable $button aquí
                
                    // Si el recibo no tiene agente de cobranza, se le puede asignar, asi mismo ponemos un boton para cancelar el recibo
                    if ($data->agente_cob_id === null) {
                        if ($data->agente_cob_id === Auth::user()->id || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Agente de Cobranza') || Auth::user()->hasRole('Coordinador')) {
                            $button = '<div class="d-flex flex-column flex-md-row gap-2 w-100"><button class="btn btn-primary asignar-recibo" data-id="' . $data->id . '">Asignar recibo</button>';
                            $button .= '<button class="btn btn-dark editar-recibo" data-id="' . $data->id . '">Editar Recibo</button>';
                        }
                        $button .= '<a href="'.route('cobranza.cancelar.show', $data->id).'" class="btn btn-danger">Cancelar Recibo</a></div>';
                        return $button;
                    }else{
                        // solo puede cancelar el recibo el agente de ventas que lo tiene asignado o el administrador del sistema o el agente de cobranza
                        if ($data->agente_cob_id === Auth::user()->id || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Agente de Cobranza') || Auth::user()->hasRole('Coordinador')) {
                            $button = '<div class="d-flex flex-column flex-md-row gap-3 w-100"><button class="btn btn-info reasignar-recibo" data-id="' . $data->id . '">Reasignar recibo</button>';
                            $button .= '<button class="btn btn-dark editar-recibo" data-id="' . $data->id . '">Editar Recibo</button>';
                            $button .= '<a href="'.route('cobranza.cancelar.show', $data->id).'" class="btn btn-danger">Cancelar Recibo</a></div>';
                            return $button;
                        }
                    }
                })
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }

        return view('crm.modulos.cobranza.index', compact('recibos', 'tipoRecibos', 'agentes_ventas'));
    }

    private function filtrarRecibos($tipoRecibos, $estado_pago, $start_date, $end_date)
    {
        $query = Receipt::with('agente_cob');

        // Aplica el filtro de estado_pago solo si se proporciona
        if (!empty($estado_pago)) {
            if (strtoupper($estado_pago) !== 'TODOS') {
                $query->where('estado_pago', $estado_pago);
            }
        } else {
            // Si estado_pago es vacío, considera todos los posibles estados
            $query->whereIn('estado_pago', ['LIQUIDADO', 'PENDIENTE', 'PAGADO']);
        }

        // Aplica el filtro de fecha_pago solo si se proporciona
        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween('fecha_pago_real', [$start_date, $end_date]);
        }

        $query->orderBy('fecha_pago_real', 'asc');

        if ($tipoRecibos === 'MIS_RECIBOS') {
            $agente_cob_id = Auth::user()->id;
            $query->where('agente_cob_id', $agente_cob_id);
        }

        return $query->get();
    }

    public function asignarRecibo(Request $request, $recibo_id)
    {
        $vendedor_id = $request->input('vendedor_id');
        $recibo = Receipt::find($recibo_id);
        $recibo->agente_cob_id = $vendedor_id;
        $recibo->save();

        $usuario = $vendedor_id;
        $user = User::find($usuario);

        $receiptLog = new ReceiptLog([
            'receipt_id' => $recibo->id,
            'user_id' => auth()->user()->id,
            'action' => 'Asignado',
            'notes' => 'Recibo asignado al usuario ' . $user->usuario
        ]);
        $receiptLog->save();
    
        return response()->json([
            'code' => 200,
            'message' => 'Recibo asignado al vendedor.'
        ]);
    }

    public function showCancelarRecibo($recibo_id)
    {
        $recibo = Receipt::find($recibo_id);
        return view('crm.modulos.cobranza.cancelar', compact('recibo'));
    }

    public function cancelarRecibo(Request $request, $recibo_id)
    {
        $recibo = Receipt::find($recibo_id);
        $recibo->estado_pago = 'CANCELADO';
        $recibo->save();

        $receiptLog = new ReceiptLog([
            'receipt_id' => $recibo->id,
            'user_id' => auth()->user()->id,
            'action' => 'Cancelado',
            'notes' => 'Recibo cancelado por el usuario ' . auth()->user()->usuario
        ]);
        $receiptLog->save();

        // Llama a la función actualizarEstadoRecibosYPago de VentaController
        $ventaController = new VentasController();
        $ventaController->actualizarEstadoRecibosYPago($recibo->venta_id, $recibo->num_pago);

        // Mandamos un mensaje flash de confirmacion de la cancelacion del recibo
        $request->session()->flash('success', 'Recibo cancelado correctamente');

        return redirect()->route('cobranza.index');
    }

    public function edit($id)
    {
        // Buscamos el recibo por su id
        $recibo = Receipt::find($id);

        // Mandamos la respuesta en formato json
        return response()->json([
            'code' => 200,
            'recibo' => $recibo
        ]);
    }

    public function update(Request $request, $id)
    {
        // Buscamos el recibo por su id
        $recibo = Receipt::findOrFail($id);

        // Validamos los datos del recibo
        $request->validate([
            'prima_cobrada' => 'required|numeric',
            'estado_pago' => 'required'
        ]);

        // Actualizamos los datos del recibo
        $recibo->prima_neta_cobrada = $request->prima_cobrada;
        $recibo->estado_pago = $request->estado_pago;
        $recibo->observations = $request->observations;

        // Guardamos los cambios
        $recibo->save();

        // Mandamos una respuesta en formato json con codigo 200 y un mensaje de confirmacion
        return response()->json([
            'code' => 200,
            'message' => 'Recibo actualizado correctamente'
        ]);
    }
}
