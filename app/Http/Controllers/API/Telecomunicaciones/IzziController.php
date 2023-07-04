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
