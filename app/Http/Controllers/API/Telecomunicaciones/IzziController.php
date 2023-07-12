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

    // function __construct()
    // {
    //     $this->middleware('permission:ver-ventas|crear-ventas|editar-ventas|borrar-ventas|ver-campos', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:editar-ventas', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:borrar-ventas', ['only' => ['destroy']]);

    //     $this->middleware('auth')->except('store');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = Telecomunication::query();

        // Búsqueda por fecha de inicio y fin
        if ($request->filled(['fecha_inicio', 'fecha_fin'])) {
            $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
            $fechaFin = Carbon::parse($request->fecha_fin)->endOfDay();
            $query->whereBetween('Fpreventa', [$fechaInicio, $fechaFin]);
        }

        $resultados = $query->get();

        // Recuperamos todos los usuarios con rol supervisor y lo mandamos a la vista
        $supervisores = User::role('Supervisor')->get();

        // Recuperamos todos los usuario con rol Agente de Ventas y lo mandmos a la vista
        $agentes = User::role('Agente de Ventas')->get();

        //////////////////////////////ENVIARLOS A DB////////////////////////////
        ///////////////////////////////////////////////////////////////////////

        $campanas = ['ONL_IZZI_IN','FB_IZZI_IN','RL_IZZI_IN','ONL_MOVIL_IN','FB_MOVIL_IN','RL_MOVIL_IN','ONL_IZZIUPS_IN','ONL_IZZIINS_IN'];
        $tipos_venta = ['IZZI','WIZZ','NEGOCIOS','MOVIL','MOVIL OUT','OUTBOUND'];
        $tipos_linea = ['ALTA','PORTABILIDAD','UPSELL','IZZI MOVIL ALTA','IZZIMOVIL PORTABILIDAD','IZZI MOVIL UPSELL','IZZ MOVIL'];
        $estados_siebel = ['ABIERTA','CANCELADO','COMPLETA','PENDIENTE'];
        $subestados_siebel = ['CLIENTE CANCELA','CLIENTE NO CONTACTADO','CLIENTE NO DESEA SERVICIO','DATOS INCORRECTOS','DOMICILIO CON ADEUDO',
                              'FALTAN DATOS','FUERA DE COBERTURA','IMPOSIBILIDAD TECNICA','ORDEN MAL GENERADA','SIN COBERTURA','SOLICITUD DEL CLIENTE',
                              'VENTA DUPLICADA'];
        $estados_tramitacionM = ['INGRESADA','TRAMITADA','ACTIVADA','CANCELADA','PENDIENTE CAPTURA ','IMEI INVALIDO','PEDIDO INVALIDO','RECHAZO CORREGIDO ',
                                 'CANCELAR PEDIDO','VENTA DUPLICADA','CONCILIACION ','CONCILIACION SOLICITADA'];
        $estados_tramitacionF = ['CANCELADA','CANCELADA EN INSTALACION','CONCILIACION','CONCILIACION SOLICITADA','INGRESADA','INSTALADA','PENDIENTE VALIDACION','VENTA MODIFICADA',
                                 'RE-VENTA','CASO DE NEGOCIO','TRAMITADA/CONFIRMADA','2 CUENTAS ACTIVAS','ADEUDO','CLIENTE CANCELA','CLIENTE NO LOCALIZADO','CONTRATO INDEPENDIENTE',
                                 'CORREO','DOMICILIO','IDENTIFICACION OFICIAL','INGRESA OTRO CANAL','NOMBRE DEL CLIENTE','NUMEROS DE CONTACTO','OFERTA COMERCIAL','SIN COBERTURA',
                                 'SIN PAGO ANTICIPADO','VENTA CARRUSEL','DUPLICADA','FORZADA'];
        $estados_velocity = ['ABIERTA','CANCELADA','COMPLETADA','ENVIADA'];
        $subestados_velocity = ['CANCELADA','CANCELADA POR CLIENTE','ENTREGA FALLIDA','ESPERA ACTIVACIÒN','ESPERA SURTIDO','SERVICIO ACTIVO','SIM INACTIVO'];

        return view('crm.telecomunicaciones.izzi.index',compact('resultados', 'supervisores', 'agentes','campanas','tipos_linea','tipos_venta','estados_siebel','subestados_siebel','estados_tramitacionM','estados_tramitacionF','estados_velocity','subestados_velocity'));

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
            $cuentaRegistrada->tipoIdentificacion = $request->tipoIdentificacion;
            $cuentaRegistrada->claveIdentificacion = $request->claveIdentificacion;
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
            $cuentaRegistrada->referencia = $request->referencia;
            $cuentaRegistrada->dirCompleta = $request->dirCompleta;
            $cuentaRegistrada->lat = $request->lat;
            $cuentaRegistrada->lng = $request->lng;
            //Datos paquete
            $cuentaRegistrada->tipoSegmento = $request->tipoSegmento;
            $cuentaRegistrada->tipoLinea = $request->tipoLinea;
            $cuentaRegistrada->tipoPaquete = $request->tipoPaquete;
            $cuentaRegistrada->paquete = $request->paquete;
            $cuentaRegistrada->plazoForzoso = $request->plazoForzoso;
            $cuentaRegistrada->precio = $request->precio;
            $cuentaRegistrada->numeroPortar = $request->numeroPortar;
            //Datos adicionales
            $cuentaRegistrada->chkHdPlus = $request->chkHdPlus;
            $cuentaRegistrada->chkHbo = $request->chkHbo;
            $cuentaRegistrada->chkInternacional = $request->chkInternacional;
            $cuentaRegistrada->chkHotPack = $request->chkHotPack;
            $cuentaRegistrada->chkComboPlus = $request->chkComboPlus;
            $cuentaRegistrada->chkAfizzionados = $request->chkAfizzionados;
            $cuentaRegistrada->chkParamount = $request->chkParamount;
            $cuentaRegistrada->chkUniPlus = $request->chkUniPlus;
            $cuentaRegistrada->ckhStarPlus = $request->ckhStarPlus;
            $cuentaRegistrada->chkDisney = $request->chkDisney;
            $cuentaRegistrada->chkVixPlus = $request->chkVixPlus;
            $cuentaRegistrada->chkGolden = $request->chkGolden;
            $cuentaRegistrada->chkNetBase = $request->chkNetBase;
            $cuentaRegistrada->ckhNetEst = $request->ckhNetEst;
            $cuentaRegistrada->chkNetPrem = $request->chkNetPrem;
            $cuentaRegistrada->extensionesTv = $request->extensionesTv;
            $cuentaRegistrada->extensionesTel = $request->extensionesTel;
            $cuentaRegistrada->lineaAdicional = $request->lineaAdicional;
            //Datos Móvil
            $cuentaRegistrada->tipoSegmentoMovil = $request->tipoSegmentoMovil;
            $cuentaRegistrada->paqueteMovil = $request->paqueteMovil;
            $cuentaRegistrada->portabilidad = $request->portabilidad;
            $cuentaRegistrada->imei = $request->imei;
            $cuentaRegistrada->contratoAdicionalMovil = $request->contratoAdicionalMovil;
            $cuentaRegistrada->pedido = $request->pedido;
            $cuentaRegistrada->costo = $request->costo;
            $cuentaRegistrada->numeroPortarMovil = $request->numeroPortarMovil;
            //Datos Segmento
            $cuentaRegistrada->fechaNacRepLegal = $request->fechaNacRepLegal;
            $cuentaRegistrada->representante = $request->representante;
            $cuentaRegistrada->rfcRepresentante = $request->rfcRepresentante;
            //Datos de tarjeta
            $cuentaRegistrada->formaPago = $request->formaPago;
            $cuentaRegistrada->tipoTarjeta = $request->tipoTarjeta;
            $cuentaRegistrada->numTarjeta = $request->numTarjeta;
            $cuentaRegistrada->vencimiento = $request->vencimiento;
            $cuentaRegistrada->cvv = $request->cvv;
            //Datos Whatsapp
            $cuentaRegistrada->btnWsp = $request->btnWsp;
            $cuentaRegistrada->observaciones = $request->observaciones;
            $cuentaRegistrada->fUltimaGestion = Carbon::now();
            $cuentaRegistrada->ultimaCampana = $request->campana;
            $cuentaRegistrada->observaciones = $request->observaciones;
            $cuentaRegistrada->documentos = $request->documentos;
            $cuentaRegistrada->datosGenerados = $request->datosGenerados;


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
                $telecom->tipoIdentificacion = $request->tipoIdentificacion;
                $telecom->claveIdentificacion = $request->claveIdentificacion;
                //Datos domicilio
                $telecom->calle = $request->calle;
                $telecom->numInterior = $request->numInterior;
                $telecom->numExterior = $request->numExterior;
                $telecom->colonia = $request->colonia;
                $telecom->cp = $request->cp;
                $telecom->municipio = $request->municipio;
                $telecom->estado = $request->estado;
                $telecom->entreCalle1 = $request->entreCalle1;
                $telecom->entreCalle2 = $request->entreCalle2;
                $telecom->referencia = $request->referencia;
                $telecom->dirCompleta = $request->dirCompleta;
                $telecom->lat = $request->lat;
                $telecom->lng = $request->lng;
                //Datos paquete
                $telecom->tipoSegmento = $request->tipoSegmento;
                $telecom->tipoLinea = $request->tipoLinea;
                $telecom->tipoPaquete = $request->tipoPaquete;
                $telecom->paquete = $request->paquete;
                $telecom->plazoForzoso = $request->plazoForzoso;
                $telecom->precio = $request->precio;
                $telecom->numeroPortar = $request->numeroPortar;
                //Datos adicionales
                $telecom->chkHdPlus = $request->chkHdPlus;
                $telecom->chkHbo = $request->chkHbo;
                $telecom->chkInternacional = $request->chkInternacional;
                $telecom->chkHotPack = $request->chkHotPack;
                $telecom->chkComboPlus = $request->chkComboPlus;
                $telecom->chkAfizzionados = $request->chkAfizzionados;
                $telecom->chkParamount = $request->chkParamount;
                $telecom->chkUniPlus = $request->chkUniPlus;
                $telecom->ckhStarPlus = $request->ckhStarPlus;
                $telecom->chkDisney = $request->chkDisney;
                $telecom->chkVixPlus = $request->chkVixPlus;
                $telecom->chkGolden = $request->chkGolden;
                $telecom->chkNetBase = $request->chkNetBase;
                $telecom->ckhNetEst = $request->ckhNetEst;
                $telecom->chkNetPrem = $request->chkNetPrem;
                $telecom->extensionesTv = $request->extensionesTv;
                $telecom->extensionesTel = $request->extensionesTel;
                $telecom->lineaAdicional = $request->lineaAdicional;
                //Datos Móvil
                $telecom->tipoSegmentoMovil = $request->tipoSegmentoMovil;
                $telecom->paqueteMovil = $request->paqueteMovil;
                $telecom->portabilidad = $request->portabilidad;
                $telecom->imei = $request->imei;
                $telecom->contratoAdicionalMovil = $request->contratoAdicionalMovil;
                $telecom->pedido = $request->pedido;
                $telecom->costo = $request->costo;
                $telecom->numeroPortarMovil = $request->numeroPortarMovil;
                //Datos segmento
                $telecom->fechaNacRepLegal = $request->fechaNacRepLegal;
                $telecom->representante = $request->representante;
                $telecom->rfcRepresentante = $request->rfcRepresentante;
                //Datos de tarjeta
                $telecom->formaPago = $request->formaPago;
                $telecom->tipoTarjeta = $request->tipoTarjeta;
                $telecom->numTarjeta = $request->numTarjeta;
                $telecom->vencimiento = $request->vencimiento;
                $telecom->cvv = $request->cvv;
                //Datos Whatsapp
                $telecom->btnWsp = $request->btnWsp;
                //Datos Otros
                $telecom->observaciones = $request->observaciones;
                $telecom->fUltimaGestion = Carbon::now();
                $telecom->ultimaCampana = $request->campana;
                $telecom->documentos = $request->documentos;
                $telecom->datosGenerados = $request->datosGenerados;
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
