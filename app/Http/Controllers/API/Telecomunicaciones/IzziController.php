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
        // Logica para guardar un registro en la tabla telecomunications

        //Identifico si el usuario pertenece a ZEUS
        $usuario = User::where('usuario', $request->loginOcm)->first();

        //Valido si existe el registro
        $cuentaRegistrada = Telecomunication::where('contact_id', $request->contact_id)->first();

        //Si existe
        if($cuentaRegistrada){
            //Defino campos a actualizar

            //Datos personales
            $cuentaRegistrada->nombre = $request->nombre;
            $cuentaRegistrada->apellidoPaterno = $request->apellidoPaterno;
            $cuentaRegistrada->apellidoMaterno = $request->apellidoMaterno;
            $cuentaRegistrada->telefonoFijo = $request->telefonoFijo;
            $cuentaRegistrada->celular = $request->celular;
            $cuentaRegistrada->correo = $request->correo;
            $cuentaRegistrada->rfc = $request->rfc;
            $cuentaRegistrada->fechaNacimiento = $request->fechaNacimiento;
            $cuentaRegistrada->cuenta = $request->cuenta;
            $cuentaRegistrada->claveElector = $request->claveElector;
            $cuentaRegistrada->claveDistribuidor = $request->claveDistribuidor;
            //Datos domicilio
            $cuentaRegistrada->calle = $request->calle;
            $cuentaRegistrada->numInterior = $request->numInterior;
            $cuentaRegistrada->numExterior = $request->numExterior;
            $cuentaRegistrada->colonia = $request->colonia;
            $cuentaRegistrada->cp = $request->cp;
            $cuentaRegistrada->municipio = $request->municipio;
            $cuentaRegistrada->estado = $request->estado;
            $cuentaRegistrada->entreCalle1 = $request->entreCalle1;
            $cuentaRegistrada->entreCalle2 = $request->entreCalle2;
            //Datos paquete
            $cuentaRegistrada->tipoSegmento = $request->tipoSegmento;
            $cuentaRegistrada->tipoLinea = $request->tipoLinea;
            $cuentaRegistrada->tipoPaquete = $request->tipoPaquete;
            $cuentaRegistrada->paquete = $request->paquete;
            $cuentaRegistrada->plazoForzoso = $request->plazoForzoso;
            $cuentaRegistrada->precio = $request->precio;
            $cuentaRegistrada->numeroPortar = $request->numeroPortar;
            //Datos adicionales
            $cuentaRegistrada->chkPremium = $request->chkPremium;
            $cuentaRegistrada->chkHbo = $request->chkHbo;
            $cuentaRegistrada->chkGolden = $request->chkGolden;
            $cuentaRegistrada->chkFox = $request->chkFox;
            $cuentaRegistrada->chkInternacional = $request->chkInternacional;
            $cuentaRegistrada->chkIzziHd = $request->chkIzziHd;
            $cuentaRegistrada->chkNoggin = $request->chkNoggin;
            $cuentaRegistrada->chkHotPack = $request->chkHotPack;
            $cuentaRegistrada->chkAfizzionados = $request->chkAfizzionados;
            $cuentaRegistrada->chkParamount = $request->chkParamount;
            $cuentaRegistrada->chkDog = $request->chkDog;
            $cuentaRegistrada->chkBlim = $request->chkBlim;
            $cuentaRegistrada->chkAcorn = $request->chkAcorn;
            $cuentaRegistrada->chkStarz = $request->chkStarz;
            $cuentaRegistrada->chkDisney = $request->chkDisney;
            $cuentaRegistrada->chkNetEst = $request->chkNetEst;
            $cuentaRegistrada->chkNetPre = $request->chkNetPre;
            $cuentaRegistrada->extensionesTv = $request->extensionesTv;
            $cuentaRegistrada->extensionesTel = $request->extensionesTel;
            $cuentaRegistrada->lineaAdicional = $request->lineaAdicional;
            $cuentaRegistrada->extGraba = $request->extGraba;
            //Datos Móvil
            $cuentaRegistrada->tipoSegmentoMovil = $request->tipoSegmentoMovil;
            $cuentaRegistrada->paqueteMovil = $request->paqueteMovil;
            $cuentaRegistrada->portabilidad = $request->portabilidad;
            $cuentaRegistrada->imei = $request->imei;
            $cuentaRegistrada->lineaMovilAdicional = $request->lineaMovilAdicional;
            $cuentaRegistrada->pedido = $request->pedido;
            $cuentaRegistrada->costo = $request->costo;
            $cuentaRegistrada->numeroPortarMovil = $request->numeroPortarMovil;
            //Datos Segmento
            $cuentaRegistrada->giro = $request->giro;
            $cuentaRegistrada->fechaNacRepLegal = $request->fechaNacRepLegal;
            $cuentaRegistrada->representante = $request->representante;
            $cuentaRegistrada->rfcRepresentante = $request->rfcRepresentante;
            $cuentaRegistrada->tipoTarjeta = $request->tipoTarjeta;
            //Datos de tarjeta
            $cuentaRegistrada->numTarjeta = $request->numTarjeta;
            $cuentaRegistrada->vencimiento = $request->vencimiento;
            $cuentaRegistrada->cvv = $request->cvv;
            //Datos Whatsapp
            $cuentaRegistrada->btnWsp = $request->btnWsp;
            $cuentaRegistrada->observaciones = $request->observaciones;
            $cuentaRegistrada->fUltimaGestion = Carbon::now();
            $cuentaRegistrada->ultimaCampana = $request->campana;
            //'referencia' => $request->referencia,
            // 'adicionales' => $adicionales
            //'observaciones' => $observaciones,
            //'documentos' => $documentos,
            //'generar' => $generar,


            //Valido si la codificación es VENTA MODIFICADA o RE-VENTA
            if($request->codification == 'VENTA MODIFICADA'){
                $cuentaRegistrada->estadoIzzi = 'RECHAZO CORREGIDO';
            } else if ($request->codification == 'RE-VENTA'){
                $cuentaRegistrada->estadoIzzi = 'RE-VENTA';
                $cuentaRegistrada->campana = $request->campana;
                $cuentaRegistrada->loginOcm = $request->loginOcm;
                $cuentaRegistrada->loginIntranet = $request->loginIntranet;
                $cuentaRegistrada->mombreAgente = $request->mombreAgente;
                $cuentaRegistrada->fechaReventa = Carbon::now();
            }

            if ($request->codification == 'VENTA IZZI' || $request->codification == 'VENTA MOVIL' || $request->codification == 'VENTA COMPLEMENTO' ) {
                $cuentaRegistrada->uGestion = $request->codification;
            }

            $cuentaRegistrada->save();

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

                $this->addHistory($telecom,'INGRESO DE VENTA');

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
        $historico->save();

        return response()->json([
            "code" => 200,
            "message" => "Registro guardado correctamente",
            "data" => $historico
        ]);
    }
}
