<?php

namespace App\Http\Controllers\API\renovaciones;

use Carbon\Carbon;
use App\Models\Venta;
use App\Models\Receipt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RenovacionesController extends Controller
{
    public function store(Request $request)
    {
        if($request->Codificacion == 'RENOVACION')
        {
            $renovacion = Venta::where('contactId', $request->contactId)
                                ->where('UGestion', 'RENOVACION')
                                ->latest('created_at')->first();

            if($renovacion)
            {
                return response()->json([
                    'code' => 400,
                    'message' => 'Ya existe una renovación con el mismo Lead',
                ]);
            }else{
                // BUSCAMOS SI EXISTE UNA RENOVACION POR NSERIE Y TVENTA 'RENOVACION'
                $ventaRenovacion = Venta::where('nSerie', $request->nSerie)
                    ->where('tVenta', 'RENOVACION')
                    ->first();

                // SI EXISTE UNA RENOVACION CON EL MISMO NSERIE Y TVENTA 'RENOVACION'
                if($ventaRenovacion)
                {
                    $venta = new Venta;
                    $venta->contactId = $request->contactId;
                    $venta->UGestion = 'RENOVADA' . $ventaRenovacion->MesBdd . $ventaRenovacion->AnioBdd;
                    $venta->Fpreventa = Carbon::now();
                    $venta->fill($request->all());
                    $venta->FinVigencia = $request->FinVigencia;
                    $venta->FfVigencia = Carbon::parse($request->FinVigencia)->addYear();
                    $venta->tVenta = 'RENOVACION';

                    $venta->save();

                    return response()->json([
                        'code' => 200,
                        'message' => 'Renovacion guardada correctamente',
                        'data' => $venta
                    ]);
                }else{
                    // SI NO EXISTE UNA RENOVACION CON EL MISMO NSERIE Y TVENTA 'RENOVACION' INSERTAMOS UNA RENOVACION NUEVA
                    $venta = new Venta;
                    $venta->contactId = $request->contactId;
                    $venta->UGestion = $request->UGestion;
                    $venta->Fpreventa = Carbon::now();
                    $venta->fill($request->all());
                    $venta->FinVigencia = $request->FinVigencia;
                    $venta->FfVigencia = Carbon::parse($request->FinVigencia)->addYear();
                    $venta->tVenta = 'RENOVACION';

                    $venta->save();

                    $frecuenciaPago = $request->input('FrePago');
                    $this->crearRecibosPago($venta, $frecuenciaPago);

                    return response()->json([
                        'code' => 200,
                        'message' => 'Renovacion guardada correctamente',
                        'data' => $venta
                    ]);
                }
            }
        }else{
            // BUSCAMOS SI EXISTE UN CONTACTID
            $contactid = Venta::where('contactId', $request->contactId)->latest('created_at')->first();

            // SI EXISTE UN CONTACTID
            if($contactid)
            {
                if($request->UGestion == 'RENOVACION' || $request->UGestion == 'PROMESA DE PAGO'){
                    return response()->json([
                        'code' => 400,
                        'message' => 'Ya existe una registro con el mismo Lead',
                    ]);
                }else if($request->UGestion == 'PROMESA DE PAGO'){
                    // ACTUALIZAMOS LOS DATOS
                    $contactid->contactId = $request->contactId;
                    $contactid->UGestion = $request->UGestion;
                    $contactid->Fpreventa = Carbon::now();
                    $contactid->fill($request->all());
                    $contactid->FinVigencia = $request->FinVigencia;
                    $contactid->FfVigencia = Carbon::parse($request->FinVigencia)->addYear();

                    // SI EL LOGIN INTRANET ES DIFERENTE AL LOGIN OCM ACTUALIZAMOS EL LOGIN INTRANET
                    if ($request->LoginOcm !== $contactid->LoginIntranet) {
                        $contactid->LoginIntranet = $request->LoginOcm;
                    }

                    $contactid->save();

                    $frecuenciaPago = $request->input('FrePago');
                    $this->crearRecibosPago($contactid, $frecuenciaPago);

                    return response()->json([
                        'code' => 200,
                        'message' => 'Promesa de pago guardada correctamente',
                        'data' => $contactid
                    ]);
                }else{
                    $contactid->contactId = $request->contactId;
                    $contactid->UGestion = $request->UGestion;
                    $contactid->Fpreventa = Carbon::now();
                    $contactid->fill($request->all());
                    $contactid->FinVigencia = $request->FinVigencia;
                    $contactid->FfVigencia = Carbon::parse($request->FinVigencia)->addYear();

                    $contactid->save();

                    return response()->json([
                        'code' => 200,
                        'message' => 'Registro guardado correctamente',
                        'data' => $contactid
                    ]);
                }
            }else{
                // VALIDAMOS SI LA UGESTION ES PROMESA DE PAGO
                if($request->UGestion == 'PROMESA DE PAGO'){
                    // INSERTAMOS LOS DATOS
                    $venta = new Venta;
                    $venta->contactId = $request->contactId;
                    $venta->UGestion = $request->UGestion;
                    $venta->fill($request->all());
                    $venta->FinVigencia = $request->FinVigencia;
                    $venta->FfVigencia = Carbon::parse($request->FinVigencia)->addYear();
                    $venta->tVenta = 'RENOVACION';

                    if ($request->LoginOcm !== $contactid->LoginIntranet) {
                        $contactid->LoginIntranet = $request->LoginOcm;
                    }

                    $venta->save();

                    $frecuenciaPago = $request->input('FrePago');
                    $this->crearRecibosPago($venta, $frecuenciaPago);

                    return response()->json([
                        'code' => 200,
                        'message' => 'Promesa de pago guardada correctamente',
                        'data' => $venta
                    ]);
                }else{
                    // SI LA CODIFICACION NO ES PROMESA DE PAGO MANDAMOS MENSAJE DE ERROR
                    $venta = new Venta;
                    $venta->contactId = $request->contactId;
                    $venta->UGestion = $request->UGestion;
                    $venta->fill($request->all());
                    $venta->FinVigencia = $request->FinVigencia;
                    $venta->FfVigencia = Carbon::parse($request->FinVigencia)->addYear();
                    $venta->tVenta = 'RENOVACION';

                    $venta->save();

                    return response()->json([
                        'code' => 200,
                        'message' => 'Registro guardado correctamente',
                        'data' => $venta
                    ]);
                }
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

                $finVigencia = Carbon::parse($venta->FinVigencia);

                for ($i = 1; $i <= $numRecibos; $i++) {
                    // Calcular la fecha del próximo pago sumando la cantidad adecuada de meses
                    $mesesPorRecibo = ceil(12 / $numRecibos); // Cantidad de meses por recibo
                    $fechaProximoPago = $finVigencia->copy()->addMonthsNoOverflow($mesesPorRecibo * ($i - 1))->endOfMonth();

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

    // Metodo para Actualizar el Estado de los Recibos de Pago (Módulo Cobranza)
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
