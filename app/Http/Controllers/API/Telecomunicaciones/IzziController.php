<?php

namespace App\Http\Controllers\API\Telecomunicaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $usuario = User::where('usuario', $request->agent_OCM)->first();

        //Valido si existe el registro
        $cuentaRegistrada = Telecomunicaciones::where('contact_id', $request->contact_id)->first();

        //Si existe
        if($cuentaRegistrada){




            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // Actualiza nombre, apellido paterno, materno, telefono, celuar, mail, rfc, fecha de nacimiento, cliente izzi,
            // ClaveDistribuidor,ClaveElector,Calle,NumExterior,NumExterior,NumInterior,Colonia,CP,DelegacionMunicipio,EstadoDireccion,EntreCalle1,EntreCalle2
            //,Segmento,TipoLinea,Producto
            // Plazo,tipoproducto,complemento,paqueteadicional,Adicional,Adicional2,Adicional3,Tarjeta,VMes,VAno,CVV

            //,NumVentaMovil,NumVentaMovil,NumPorta,FechaInstalacion
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
                'claveElector' =>  $request->claveElector,
                'claveDistribuidor' => $request->claveDistribuidor,
                //Datos domicilio
                'calle' => $request->calle,
                'numInterior' => $request->numInterior,
                'numExterior' => $request->numExterior,
                'colonia' => $request->colonia,
                'cp' => $request->cp,
                'municipio' => $request->municipio,
                'estado' =>  $request->estado,
                'entreCalle1' => $request->entreCalle1,
                'entreCalle2' => $request->entreCalle2,
                //Datos paquete
                'tipoSegmento' => $request->tipoSegmento,
                'tipoLinea' => $request->tipoLinea,
                'tipoPaquete' => $request->tipoPaquete,
                'paquete' => $request->paquete,
                'plazoForzoso' => $request->plazoForzoso,
                'precio' => $request->precio,


                //Datos adicionales
                'chkPremium' => $request->chkPremium,
                'chkHbo' => $request->chkHbo,
                'chkGolden' => $request->chkGolden,
                'chkFox' => $request->chkFox,
                'chkInternacional' => $request->chkInternacional,
                'chkIzziHd' => $request->$chkIzziHd,
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

                //Datos segmento
                'giro'=>$giro,
                'fechaNacRepLegal'=>$fechaNacRepLegal,
                'representante'=>$representante,
                'rfcRepresentante'=>$rfcRepresentante,
                'tipoTarjeta'=>$tipoTarjeta,

                //Datos de tarjeta
                'numTarjeta'=>$numTarjeta,
                'vencimiento'=>$vencimiento,
                'cvv'=>$cvv,

                //'documentos'=>$documentos,


                //'generar'=>$generar,
                //Datos Whatsapp
                'btnWsp'=>$btnWsp,

                'numeroPortarMovil'=>$numeroPortarMovil,
                'referencia'=>$referencia,
                'numeroPortar'=>$numeroPortar,

                // 'adicionales'=>$adicionales
                //'observaciones'=>$observaciones,
            ];


            //Valido si la codificación es VENTA MODIFICADA
            if($request->codification == 'VENTA MODIFICADA'){
                // SQL = SQL & "EstadoIZZI='RECHAZO CORREGIDO',"
                // SQL = SQL & " rutaDocumento = '" & urlDocumentos & "', "
            } else if ($request->codification == 'RE-VENTA'){
            //     SQL = SQL & "EstadoIZZI='RE-VENTA',"
            //     SQL = SQL & "login='" & login & "',agente='" & agente & "',logininconcert='" & logininconcert & "',idgrupo='" & idgrupo & "',grupo='" & grupo & "',coordinador='" & coordinador & "',"
            //     SQL = SQL & "FechaReventa= case when FechaReventa is null then '" & objFunciones.HoraLocal().ToString("yyyy-MM-dd HH:mm:ss") & "' else FechaReventa end,"
            //     SQL = SQL & " rutaDocumento = '" & urlDocumentos & "', "
            } else {

            }





            // SQL = SQL & "Rango='" & Rango & "',Observaciones='" & Observaciones & "'," &
            // "RecibeDatos='" & RecibeDatos & "',VCC='" & VCC.ToUpper & "',fechaultimoestado='" & objFunciones.HoraLocal().ToString("yyyy-MM-dd HH:mm:ss") & "'," &
            // "Campañainconcertultima='" & Campañainconcert & "', " &
            // "PagoAnticipado='" & PagoAnticipado & "',PATipoTarjeta='" & PATipoTarjeta & "',DomiciliacionTJ='" & DomiciliacionTJ & "',Domiciliacion='" & Domiciliacion & "', " &
            // "SegmentoMovil='" & SegmentoMovil & "', PaqueteMovil='" & PaqueteMovil & "', AdicionalMovil='" & AdicionalMovil & "', NumPortaMovil='" & NumPortaMovil & "', IMEI='" & IMEI & "', NumeroPedidoIzziMovil='" & Pedido & "'"

            // If Codificacion = "VENTA IZZI" Or Codificacion = "VENTA MOVIL" Or Codificacion = "VENTA COMPLEMENTO" Then
            //     SQL = SQL & ", CODIFICACION='" & Codificacion & "'"
            // End If



            //  return response()->json([
            //      "code" => 200,
            //      "message" => "La cuenta ya esta registrada con la misma codificación: ".$request->codification,
            //  ]);
         // Si la cuenta esta registrada pero la codificacion es diferente se manda a actualizar la información
         }else{
             // Se verifica si existe el usuario en ZEUS e inserto la información de la venta y guardo en el histórico
             if($usuario){

                /////////////////////////////////////////////////////////////////////////////////////////////////////////////7
                //// Agregar campos de telecomunicaciones y guardar venta

                //  $education = new Education;
                //  $education->contact_id = $request->contact_id;
                //  $education->usuario_ocm = $request->agent_OCM;
                //  $education->usuario_crm = $request->agent_OCM;
                //  $education->nombre_universidad = 'UIN';
                //  $education->fp_venta = Carbon::now();
                //  $education->campana = $request->campana;
                //  $education->agent_OCM = $request->agent_OCM;
                //  $education->agent_intra = $usuario->id;
                //  $education->supervisor = $usuario->id_superior;
                //  $education->codification = $request->codification;
                //  $education->client_name = $request->client_name;
                //  $education->client_landline = $request->client_landline;
                //  $education->client_celphone = $request->client_celphone;
                //  $education->client_modality = $request->client_modality;
                //  $education->client_program = $request->client_program;
                //  $education->client_specialty = $request->client_specialty;
                //  $education->client_street = $request->client_street;
                //  $education->client_number = $request->client_number;
                //  $education->client_delegation = $request->client_delegation;
                //  $education->client_state = $request->client_state;
                //  $education->client_sex = $request->client_sex;
                //  $education->client_birth = $request->client_birth;
                //  $education->schedule_date = $request->schedule_date;
                //  $education->client_plantel = $request->client_plantel;
                //  $education->client_matricula = $request->client_matricula;
                 //$education->fill($request->all());

                // $education->save();


                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //// Insertar registro en histórico
                // Dim SQL As String = "insert into historicoventasIzzi (idventa,lead,usuario,fecha,estado,descripcion) values('" & idventa & "','" & lead & "'" &
                // ",'" & usuario & "','" & fecha & "','" & estado & "','" & descripcion & "') "
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                 return response()->json([
                     "code" => 200,
                     "message" => "Registro guardado correctamente",
                     "data" => $education
                 ]);
             }else{
                 return response()->json([
                     "code" => 200,
                     "message" => "No se encontró tu usuario en ZEUS, Por favor, solicita el alta de tu usuario a tu supervisor.",
                 ]);
             }
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
        //
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
