<?php

namespace App\Http\Controllers\API\renovaciones;

use Carbon\Carbon;
use App\Models\Venta;
use App\Models\User;
use App\Models\Receipt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RenovacionesController extends Controller
{
    public function store(Request $request)
    {
        if ($request->Codificacion == 'RENOVACION') {
            $renovacion = Venta::where('contactId', $request->contactId)
                                ->where('UGestion', 'RENOVACION')
                                ->latest('created_at')->first();

            if ($renovacion) {
                return response()->json([
                    'code' => 400,
                    'message' => 'Ya existe una renovación con el mismo Lead',
                ]);
            } else {
                // BUSCAMOS SI EXISTE UNA RENOVACION POR NSERIE Y TVENTA 'RENOVACION'
                $ventaRenovacion = Venta::where('nSerie', $request->nSerie)
                    ->where('tVenta', 'RENOVACION')
                    ->first();
                
                if($ventaRenovacion)
                {
                    // SI EXISTE UNA RENOVACION CON EL MISMO NSERIE Y TVENTA 'RENOVACION'
                    if ($ventaRenovacion->UGestion != 'PROMESA DE PAGO') {
                        $venta = new Venta;
                        $venta->contactId = $request->contactId;
                        $venta->Fpreventa = Carbon::now();
                        $venta->fill($request->all());
                        $venta->UGestion = 'RENOVADA' . $ventaRenovacion->MesBdd . $ventaRenovacion->AnioBdd;
                        $venta->FinVigencia = $request->FinVigencia;
                        $venta->FfVigencia = Carbon::parse($request->FinVigencia)->addYear();
                        $venta->fecha_ultima_gestion = Carbon::now();
                        $venta->aseguradora_vendida = $request->Aseguradora;
                        $venta->tVenta = 'RENOVACION';

                        $venta->save();

                        return response()->json([
                            'code' => 200,
                            'message' => 'Renovacion guardada correctamente venta_1',
                            'data' => $venta
                        ]);
                    } else {
                        // SI NO EXISTE UNA RENOVACION CON EL MISMO NSERIE Y TVENTA 'RENOVACION' INSERTAMOS UNA RENOVACION NUEVA
                        $ventaRenovacion->contactId = $request->contactId;
                        $ventaRenovacion->Fpreventa = Carbon::now();
                        $ventaRenovacion->fill($request->all());
                        $ventaRenovacion->UGestion = $request->UGestion;
                        $ventaRenovacion->FinVigencia = $request->FinVigencia;
                        $ventaRenovacion->FfVigencia = Carbon::parse($request->FinVigencia)->addYear();
                        $ventaRenovacion->fecha_ultima_gestion = Carbon::now();
                        $ventaRenovacion->aseguradora_vendida = $request->Aseguradora;
                        $ventaRenovacion->tVenta = 'RENOVACION';

                        $ventaRenovacion->save();

                        $frecuenciaPago = $request->input('FrePago');
                        $this->crearRecibosPago($ventaRenovacion, $frecuenciaPago);

                        return response()->json([
                            'code' => 200,
                            'message' => 'Renovacion guardada correctamente venta_2',
                            'data' => $venta
                        ]);
                    }
                }else{
                    $venta = new Venta;
                    $venta->contactId = $request->contactId;
                    $venta->Fpreventa = Carbon::now();
                    $venta->fill($request->all());
                    $venta->UGestion = $request->UGestion;
                    $venta->FinVigencia = $request->FinVigencia;
                    $venta->FfVigencia = Carbon::parse($request->FinVigencia)->addYear();
                    $venta->fecha_ultima_gestion = Carbon::now();
                    $venta->aseguradora_vendida = $request->Aseguradora;
                    $venta->tVenta = 'RENOVACION';

                    $venta->save();

                    $frecuenciaPago = $request->input('FrePago');
                        $this->crearRecibosPago($venta, $frecuenciaPago);

                    return response()->json([
                        'code' => 200,
                        'message' => 'Renovacion guardada correctamente venta_3',
                        'data' => $venta
                    ]);
                }
            }
        } else {
            // BUSCAMOS SI EXISTE UN CONTACTID
            $contactid = Venta::where('contactId', $request->contactId)->latest('created_at')->first();

            // SI EXISTE UN CONTACTID
            if ($contactid) {
                if ($contactid->UGestion == 'RENOVACION' || $contactid->UGestion == 'PROMESA DE PAGO') {
                    return response()->json([
                        'code' => 400,
                        'message' => 'Éste registro ya es una promesa de pago o renovacion, no se actualizó el registro',
                    ]);
                } else if ($request->UGestion == 'PROMESA DE PAGO') {
                    // ACTUALIZAMOS LOS DATOS
                    $contactid->contactId = $request->contactId;
                    $contactid->Fpreventa = Carbon::now();
                    $contactid->UGestion = $request->UGestion;
                    $contactid->fill($request->all());
                    if ($request->Aseguradora) {
                        if (!$contactid->Aseguradora) {
                            // si la aseguradora no existe en contactid, la asigna
                            $contactid->Aseguradora = $request->Aseguradora;
                        }

                        // si la aseguradora ya existe en request, asigna el valor a aseguradora_vendida
                        $contactid->aseguradora_vendida = $request->Aseguradora;
                    }

                    $contactid->FinVigencia = $request->FinVigencia;
                    $contactid->FfVigencia = Carbon::parse($request->FinVigencia)->addYear();
                    $contactid->fecha_ultima_gestion = Carbon::now();
                    $contactid->aseguradora_vendida = $request->Aseguradora;

                    // SI EL LOGIN INTRANET ES DIFERENTE AL LOGIN OCM ACTUALIZAMOS EL LOGIN INTRANET
                    if ($request->LoginOcm !== $contactid->LoginIntranet) {
                        $contactid->LoginIntranet = $request->LoginOcm;
                    }

                    $contactid->save();

                    $frecuenciaPago = $request->input('FrePago');
                    $this->crearRecibosPago($contactid, $frecuenciaPago);

                    return response()->json([
                        'code' => 200,
                        'message' => 'Promesa de pago guardada correctamente contactid',
                        'data' => $contactid
                    ]);
                } else {
                    $contactid->UGestion = $request->UGestion;
                    $contactid->fecha_ultima_gestion = Carbon::now();

                    $contactid->save();

                    return response()->json([
                        'code' => 200,
                        'message' => 'Registro guardado correctamente contactid_1',
                        'data' => $contactid
                    ]);
                }
            } else {
                // VALIDAMOS SI LA UGESTION ES PROMESA DE PAGO
                if ($request->UGestion == 'PROMESA DE PAGO') {
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
                        'message' => 'Promesa de pago guardada correctamente venta_3',
                        'data' => $venta
                    ]);
                } else {
                    $venta = new Venta;
                    $venta->contactId = $request->contactId;
                    $venta->UGestion = $request->UGestion;
                    $venta->fill($request->all());
                    if ($request->Aseguradora) {
                        if (!$venta->Aseguradora) {
                            // si la aseguradora no existe en contactid, la asigna
                            $venta->Aseguradora = $request->Aseguradora;
                        }

                        // si la aseguradora ya existe en request, asigna el valor a aseguradora_vendida
                        $venta->aseguradora_vendida = $request->Aseguradora;
                    }
                    $venta->fecha_ultima_gestion = Carbon::now();
                    $venta->aseguradora_vendida = $request->Aseguradora;
                    $venta->tVenta = 'RENOVACION';

                    $venta->save();

                    $frecuenciaPago = $request->input('FrePago');
                    $this->crearRecibosPago($venta, $frecuenciaPago);

                    return response()->json([
                        'code' => 200,
                        'message' => 'Registro guardado correctamente contactid_2',
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

                $numRecibos = $frecuenciaPagos[$frecuenciaPago];
                $finVigencia = Carbon::parse($venta->FinVigencia);

                $primerReciboAnualAsignado = false;

                for ($i = 1; $i <= $numRecibos; $i++) {
                    $mesesPorRecibo = 12 / $numRecibos; // Cantidad de meses por recibo
                    $fechaProximoPago = $finVigencia->copy()->addMonthsNoOverflow($mesesPorRecibo * ($i - 1));

                    $fechaProximoPago = $i == 1 ? $finVigencia : $fechaProximoPago;

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
