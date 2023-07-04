<?php

namespace App\Http\Controllers\API\Telecomunicaciones;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Telecomunication;
use App\Http\Controllers\Controller;
use App\Models\Telecomunications_historical;

class IzziController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('crm.telecomunicaciones.izzi.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json([
            "code" => 200,
            "message" => $request->all(),
        ]);
        // Logica para guardar un registro en la tabla telecomunications

        //Identifico si el usuario pertenece a ZEUS
        $usuario = User::where('usuario', $request->agent_OCM)->first();

        //Valido si existe el registro
        $cuentaRegistrada = Telecomunication::where('contact_id', $request->contact_id)->first();

        //Si existe
        if($cuentaRegistrada){

            //Defino campos a actualizar
            $datosVenta = [
                //Datos personales
                'nombre' => $request->nombre,
                'apellidoPaterno' => $request->apellidoPaterno,
                'apellidoMaterno' => $request->apellidoMaterno,
                'telefonoFijo' => $request->telefonoFijo,
                'celular' => $request->celular,
                'correo' => $request->correo,
                'rfc' => $request->rfc,
                'fechaNacimiento' => $request->fechaNacimiento,
                'cuenta' => $request->cuenta,
                'claveElector' => $request->claveElector,
                'claveDistribuidor' => $request->claveDistribuidor,
                //Datos domicilio
                'calle' => $request->calle,
                'numInterior' => $request->numInterior,
                'numExterior' => $request->numExterior,
                'colonia' => $request->colonia,
                'cp' => $request->cp,
                'municipio' => $request->municipio,
                'estado' => $request->estado,
                'entreCalle1' => $request->entreCalle1,
                'entreCalle2' => $request->entreCalle2,
                //Datos paquete
                'tipoSegmento' => $request->tipoSegmento,
                'tipoLinea' => $request->tipoLinea,
                'tipoPaquete' => $request->tipoPaquete,
                'paquete' => $request->paquete,
                'plazoForzoso' => $request->plazoForzoso,
                'precio' => $request->precio,
                'numeroPortar' => $request->numeroPortar,
                //Datos adicionales
                'chkPremium' => $request->chkPremium,
                'chkHbo' => $request->chkHbo,
                'chkGolden' => $request->chkGolden,
                'chkFox' => $request->chkFox,
                'chkInternacional' => $request->chkInternacional,
                'chkIzziHd' => $request->chkIzziHd,
                'chkNoggin' => $request->chkNoggin,
                'chkHotPack' => $request->chkHotPack,
                'chkAfizzionados' => $request->chkAfizzionados,
                'chkParamount' => $request->chkParamount,
                'chkDog' => $request->chkDog,
                'chkBlim' => $request->chkBlim,
                'chkAcorn' => $request->chkAcorn,
                'chkStarz' => $request->chkStarz,
                'chkDisney' => $request->chkDisney,
                'chkNetEst' => $request->chkNetEst,
                'chkNetPre' => $request->chkNetPre,
                'extensionesTv' => $request->extensionesTv,
                'extensionesTel' => $request->extensionesTel,
                'lineaAdicional' => $request->lineaAdicional,
                'extGraba' => $request->extGraba,
                //Datos Móvil
                'tipoSegmentoMovil' => $request->tipoSegmentoMovil,
                'paqueteMovil' => $request->paqueteMovil,
                'portabilidad' => $request->portabilidad,
                'imei' => $request->imei,
                'lineaMovilAdicional' => $request->lineaMovilAdicional,
                'pedido' => $request->pedido,
                'costo' => $request->costo,
                'numeroPortarMovil' => $request->numeroPortarMovil,
                //Datos Segmento
                'giro' => $request->giro,
                'fechaNacRepLegal' => $request->fechaNacRepLegal,
                'representante' => $request->representante,
                'rfcRepresentante' => $request->rfcRepresentante,
                'tipoTarjeta' => $request->tipoTarjeta,
                //Datos de tarjeta
                'numTarjeta' => $request->numTarjeta,
                'vencimiento' => $request->vencimiento,
                'cvv' => $request->cvv,
                //Datos Whatsapp
                'btnWsp' => $request->btnWsp,
                'observaciones' => $request->observaciones,
                'fUltimaGestion' => Carbon::now(),
                'ultimaCampana' => $request->campana,
                //'referencia' => $request->referencia,
                // 'adicionales' => $adicionales
                //'observaciones' => $observaciones,
                //'documentos' => $documentos,
                //'generar' => $generar,
            ];


            //Valido si la codificación es VENTA MODIFICADA o RE-VENTA
            if($request->codification == 'VENTA MODIFICADA'){
                $datosVentaModificada = ['estadoIzzi' => 'RECHAZO CORREGIDO'];
                array_push($datosVenta,$datosVentaModificada);
            } else if ($request->codification == 'RE-VENTA'){
                $datosReventa = [
                                'estadoIzzi' => 'RE-VENTA',
                                'campana' => $request->campana,
                                'loginOcm' => $request->loginOcm,
                                'loginIntranet' => $request->loginIntranet,
                                'mombreAgente' => $request->mombreAgente,
                                'fechaReventa' => Carbon::now()
                                ];
                array_push($datosVenta,$datosReventa);

            }

            if ($request->codification == 'VENTA IZZI' || $request->codification == 'VENTA MOVIL' || $request->codification == 'VENTA COMPLEMENTO' ) {
                $datosFinalVenta = ['uGestion' => $request->codification];
                array_push($datosVenta,$datosFinalVenta);
            }

            Telecomunication::where('contact_id', $request->contact_id)->update($datosVenta);

             return response()->json([
                 "code" => 200,
                 "message" => "Cuenta actualizada con éxito: ".$request->codification,
             ]);

         }else{
             // Se verifica si existe el usuario en ZEUS e inserto la información de la venta y guardo en el histórico
             if($usuario){
                $telecom = new Telecomunication;
                $estadoIzzi = 'INGRESADA';
                $telecom->fPreventa = Carbon::now();
                $telecom->uGestion = $request->uGestion;
                $telecom->contact_id = $request->contact_id;
                $telecom->cuenta = $request->cuenta;
                $telecom->campana = $request->campana;
                $telecom->loginOcm = $request->loginOcm;
                $telecom->loginIntranet = $request->loginOcm;
                $telecom->nombreAgente = $request->nombreAgente;
                $telecom->supervisor = $request->supervisor;
                $telecom->tipoVenta = $request->tipoVenta;
                // DATOS CLIENTE
                $telecom->nombre = $request->nombre;
                $telecom->apellidoPaterno = $request->apellidoPaterno;
                $telecom->apellidoMaterno = $request->apellidoMaterno;
                $telecom->telefonoFijo = $request->telefonoFijo;
                $telecom->celular = $request->celular;
                $telecom->correo = $request->correo;
                $telecom->rfc = $request->rfc;
                $telecom->fechaNacimiento = $request->fechaNacimiento;
                $telecom->cuenta = $request->cuenta;
                $telecom->EstadoIZZI = $estadoIzzi;
                $telecom->claveElector =  $request->claveElector;
                $telecom->claveDistribuidor = $request->claveDistribuidor;
                //Datos domicilio
                $telecom->calle = $request->calle;
                $telecom->numInterior = $request->numInterior;
                $telecom->numExterior = $request->numExterior;
                $telecom->colonia = $request->colonia;
                $telecom->cp = $request->cp;
                $telecom->municipio = $request->municipio;
                $telecom->estado =  $request->estado;
                $telecom->entreCalle1 = $request->entreCalle1;
                $telecom->entreCalle2 = $request->entreCalle2;
                //Datos paquete
                $telecom->tipoSegmento = $request->tipoSegmento;
                $telecom->tipoLinea = $request->tipoLinea;
                $telecom->tipoPaquete = $request->tipoPaquete;
                $telecom->paquete = $request->paquete;
                $telecom->plazoForzoso = $request->plazoForzoso;
                $telecom->precio = $request->precio;
                $telecom->numeroPortar = $request->numeroPortar;
                //Datos adicionales
                $telecom->chkPremium = $request->chkPremium;
                $telecom->chkHbo = $request->chkHbo;
                $telecom->chkGolden = $request->chkGolden;
                $telecom->chkFox = $request->chkFox;
                $telecom->chkInternacional = $request->chkInternacional;
                $telecom->chkIzziHd = $request->chkIzziHd;
                $telecom->chkNoggin = $request->chkNoggin;
                $telecom->chkHotPack = $request->chkHotPack;
                $telecom->chkAfizzionados = $request->chkAfizzionados;
                $telecom->chkParamount = $request->chkParamount;
                $telecom->chkDog = $request->chkDog;
                $telecom->chkBlim = $request->chkBlim;
                $telecom->chkAcorn = $request->chkAcorn;
                $telecom->chkStarz = $request->chkStarz;
                $telecom->chkDisney = $request->chkDisney;
                $telecom->chkNetEst = $request->chkNetEst;
                $telecom->chkNetPre = $request->chkNetPre;
                $telecom->extensionesTv = $request->extensionesTv;
                $telecom->extensionesTel = $request->extensionesTel;
                $telecom->lineaAdicional = $request->lineaAdicional;
                $telecom->extGraba = $request->extGraba;
                //Datos Móvil
                $telecom->tipoSegmentoMovil = $request->tipoSegmentoMovil;
                $telecom->paqueteMovil = $request->paqueteMovil;
                $telecom->portabilidad = $request->portabilidad;
                $telecom->imei = $request->imei;
                $telecom->lineaMovilAdicional = $request->lineaMovilAdicional;
                $telecom->pedido = $request->pedido;
                $telecom->costo = $request->costo;
                $telecom->numeroPortarMovil = $request->numeroPortarMovil;
                //Datos segmento
                $telecom->giro = $request->giro;
                $telecom->fechaNacRepLegal = $request->fechaNacRepLegal;
                $telecom->representante = $request->representante;
                $telecom->rfcRepresentante = $request->rfcRepresentante;
                $telecom->tipoTarjeta = $request->tipoTarjeta;
                //Datos de tarjeta
                $telecom->numTarjeta = $request->numTarjeta;
                $telecom->vencimiento = $request->vencimiento;
                $telecom->cvv = $request->cvv;
                //Datos Whatsapp
                $telecom->btnWsp = $request->btnWsp;
                //Datos Otros
                $telecom->observaciones = $request->observaciones;
                $telecom->fUltimaGestion = Carbon::now();
                $telecom->ultimaCampana = $request->campana;
                $telecom->save();

                $historical = $this->addHistory($telecom,'INGRESO DE VENTA');

                return response()->json([
                    "code" => 200,
                    "message" => "Registro guardado correctamente",
                    "data" => $telecom
                ]);
             }else{
                 return response()->json([
                     "code" => 200,
                     "message" => "No se encontró tu usuario en ZEUS, Por favor, solicita el alta de tu usuario a tu supervisor.",
                 ]);
             }
         }
    }

    // Función para guardar el histórico de la venta
    public function addHistory($datosVenta,$descripcion)
    {
        //Inserto histórico, PONERLO EN FUNCIÓN
        $historico = new Telecomunications_historical;
        $historico->idVenta = $datosVenta->id;
        $historico->contact_id = $datosVenta->contact_id;
        $historico->loginOcm = $datosVenta->loginOcm;
        $historico->loginIntranet = $datosVenta->loginIntranet;
        $historico->estado = $datosVenta->estadoIzzi;
        $historico->descripcion = $descripcion;
        $historico->fill($datosVenta);
        $historico->save();

        return response()->json([
            "code" => 200,
            "message" => "Registro guardado correctamente",
            "data" => $historico
        ]);
    }
}
