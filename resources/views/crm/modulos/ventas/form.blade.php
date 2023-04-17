@extends('crm.layouts.app')
<style>

</style>
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">FORMULARIO PRUEBA</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">FORMULARIO PRUEBA</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card crm-widget py-4 px-3">
                <div class="card-body">
                    <form action="{{ route('store.venta') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="contactId" class="form-label">Contact ID</label>
                                    <input type="number" class="form-control" id="contactId" name="contactId">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Fpreventa" class="form-label">Fecha de Pre-venta</label>
                                    <input type="date" class="form-control" id="Fpreventa" name="Fpreventa">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="campana" class="form-label">Campaña</label>
                                    <input type="text" class="form-control" id="campana" name="campana">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="LoginOcm" class="form-label">Login OCM</label>
                                    <input type="text" class="form-control" id="LoginOcm" name="LoginOcm">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="LoginIntranet" class="form-label">Login Intranet</label>
                                    <input type="text" class="form-control" id="LoginIntranet" name="LoginIntranet">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="NombreAgente" class="form-label">Nombre del Agente</label>
                                    <input type="text" class="form-control" id="NombreAgente" name="NombreAgente">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Supervisor" class="form-label">Supervisor</label>
                                    <input type="text" class="form-control" id="Supervisor" name="Supervisor">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Codificacion" class="form-label">Codificación</label>
                                    <select class="form-select" id="Codificacion" name="Codificacion">
                                        <option selected>-- Selecciona una opción --</option>
                                        <option value="PREVENTA">PREVENTA</option>
                                        <option value="VENTA MODIFICADA">VENTA MODIFICADA</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="Nombre" name="Nombre">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="ApePaterno" class="form-label">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="ApePaterno" name="ApePaterno">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="ApeMaterno" class="form-label">Apellido Materno</label>
                                    <input type="text" class="form-control" id="ApeMaterno" name="ApeMaterno">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="fNacimiento" class="form-label">Fecha Nacimiento</label>
                                    <input type="date" class="form-control" id="fNacimiento" name="fNacimiento">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Edad" class="form-label">Edad</label>
                                    <input type="number" class="form-control" id="Edad" name="Edad">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Genero" class="form-label">Genero</label>
                                    <select class="form-select" id="Genero" name="Genero">
                                        <option selected>-- Selecciona una opción --</option>
                                        <option value="MASCULINO">MASCULINO</option>
                                        <option value="FEMENINO">FEMENINO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="RFC" class="form-label">RFC</label>
                                    <input type="text" class="form-control" id="RFC" name="RFC">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Homoclave" class="form-label">Homoclave</label>
                                    <input type="text" class="form-control" id="Homoclave" name="Homoclave">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="CURP" class="form-label">CURP</label>
                                    <input type="text" class="form-control" id="CURP" name="CURP">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="TelFijo" class="form-label">Teléfono Fijo</label>
                                    <input type="number" class="form-control" id="TelFijo" name="TelFijo">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="TelCelular" class="form-label">Teléfono Celular</label>
                                    <input type="number" class="form-control" id="TelCelular" name="TelCelular">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="TelEmergencias" class="form-label">Teléfono Emergencias</label>
                                    <input type="number" class="form-control" id="TelEmergencias" name="TelEmergencias">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Correo" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="Correo" name="Correo">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Calle" class="form-label">Calle</label>
                                    <input type="text" class="form-control" id="Calle" name="Calle">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="mb-3">
                                    <label for="NumExt" class="form-label">No. Exterior</label>
                                    <input type="number" class="form-control" id="NumExt" name="NumExt">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="mb-3">
                                    <label for="NumInt" class="form-label">No. Interior</label>
                                    <input type="number" class="form-control" id="NumInt" name="NumInt">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Colonia" class="form-label">Colonia</label>
                                    <input type="text" class="form-control" id="Colonia" name="Colonia">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="AlMun" class="form-label">Municipio</label>
                                    <input type="text" class="form-control" id="AlMun" name="AlMun">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="Estado" class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="Estado" name="Estado">
                                </div>
                            </div>
                            <div class="col-12 col-md-1">
                                <div class="mb-3">
                                    <label for="CP" class="form-label">CP</label>
                                    <input type="number" class="form-control" id="CP" name="CP">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Marca" class="form-label">Marca</label>
                                    <input type="text" class="form-control" id="Marca" name="Marca">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="SubMarca" class="form-label">Sub Marca</label>
                                    <input type="text" class="form-control" id="SubMarca" name="SubMarca">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Modelo" class="form-label">Modelo</label>
                                    <input type="text" class="form-control" id="Modelo" name="Modelo">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="nSerie" class="form-label">No. Serie</label>
                                    <input type="text" class="form-control" id="nSerie" name="nSerie">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="nMotor" class="form-label">No. Motor</label>
                                    <input type="text" class="form-control" id="nMotor" name="nMotor">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="nPlacas" class="form-label">No. Placas</label>
                                    <input type="text" class="form-control" id="nPlacas" name="nPlacas">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Segmento" class="form-label">Segmento</label>
                                    <input type="text" class="form-control" id="Segmento" name="Segmento">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Legalizado" class="form-label">Legalizado</label>
                                    <input type="text" class="form-control" id="Legalizado" name="Legalizado">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="nCotizacion" class="form-label">No. Cotización</label>
                                    <input type="number" class="form-control" id="nCotizacion" name="nCotizacion">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="FinVigencia" class="form-label">Fecha Inicio Vigencia</label>
                                    <input type="date" class="form-control" id="FinVigencia" name="FinVigencia">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="FfVigencia" class="form-label">Fecha Fin de Vigencia</label>
                                    <input type="date" class="form-control" id="FfVigencia" name="FfVigencia">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="tPoliza" class="form-label">Tipo de Poliza</label>
                                    <input type="date" class="form-control" id="tPoliza" name="tPoliza">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Paquete" class="form-label">Paquete</label>
                                    <input type="text" class="form-control" id="Paquete" name="Paquete">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="nPoliza" class="form-label">No. Poliza</label>
                                    <input type="number" class="form-control" id="nPoliza" name="nPoliza">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="Aseguradora" class="form-label">Aseguradora</label>
                                    <input type="text" class="form-control" id="Aseguradora" name="Aseguradora">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="fPago" class="form-label">Forma de Pago</label>
                                    <input type="text" class="form-control" id="fPago" name="fPago">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="FrePago" class="form-label">Frecuencia de Pago</label>
                                    <select class="form-select" aria-label="Default select example" id="FrePago" name="FrePago">
                                        <option selected>Selecciona una opción</option>
                                        <option value="Mensual">MENSUAL</option>
                                        <option value="Trimestral">TRIMESTRAL</option>
                                        <option value="Semestral">SEMESTRAL</option>
                                        <option value="Anual">ANUAL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="tTarjeta" class="form-label">Tipo de Tarjeta</label>
                                    <input type="text" class="form-control" id="tTarjeta" name="tTarjeta">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="nTarjeta" class="form-label">Número de Tarjeta</label>
                                    <input type="number" class="form-control" id="nTarjeta" name="nTarjeta">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="fvencimiento" class="form-label">Fecha de Vencimiento</label>
                                    <input type="date" class="form-control" id="fvencimiento" name="fvencimiento">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="PncTotal" class="form-label">Prima Neta</label>
                                    <input type="number" class="form-control" id="PncTotal" name="PncTotal">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="NombreDeCliente" class="form-label">Nombre del Cliente</label>
                                    <input type="text" class="form-control" id="NombreDeCliente" name="NombreDeCliente">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="tVenta" class="form-label">Tipo de Venta</label>
                                    <input type="text" class="form-control" id="tVenta" name="tVenta">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="mb-3">
                                    <label for="MesBdd" class="form-label">Mes Renovación</label>
                                    <input type="number" class="form-control" id="MesBdd" name="MesBdd">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="mb-3">
                                    <label for="AnioBdd" class="form-label">Año Renovación</label>
                                    <input type="number" class="form-control" id="AnioBdd" name="AnioBdd">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="noPago" class="form-label">No. Recibo de Pago</label>
                                    <input type="number" class="form-control" id="noPago" name="noPago">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="FechaProximoPago" class="form-label">Fecha de Recibos Subsecuentes</label>
                                    <input type="date" class="form-control" id="FechaProximoPago" name="FechaProximoPago">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="FechaPagoReal" class="form-label">Fecha de Pago Real</label>
                                    <input type="date" class="form-control" id="FechaPagoReal" name="FechaPagoReal">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="PrimaNetaCobrada" class="form-label">Prima Neta Cobrada</label>
                                    <input type="number" class="form-control" id="PrimaNetaCobrada" name="PrimaNetaCobrada">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="AgenteCob" class="form-label">Agente Cobro Recibo</label>
                                    <input type="text" class="form-control" id="AgenteCob" name="AgenteCob">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="TipoPago" class="form-label">Tipo de Pago</label>
                                    <select class="form-select" aria-label="Default select example" id="TipoPago" name="TipoPago">
                                        <option selected>-- Seleccione una opción --</option>
                                        <option value="Parcial">PARCIAL</option>
                                        <option value="Liquidado">LIQUIDADO</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="UGestion" class="form-label">Gestion</label>
                                    <input type="text" class="form-control" id="UGestion" name="UGestion">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="EstadoDePago" class="form-label">Estado de Pago</label>
                                    <select class="form-select" aria-label="Default select example" id="EstadoDePago" name="EstadoDePago">
                                        <option selected>-- Seleccione una opción --</option>
                                        <option value="PAGADO">PAGADO</option>
                                        <option value="PENDIENTE">PENDIENTE</option>
                                        <option value="CANCELADO">CANCELADO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection