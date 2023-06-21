@extends('crm.layouts.app')
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ $adt->cliente_nombre }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item">Cotización</li>
                        <li class="breadcrumb-item active text-uppercase">{{ $adt->cliente_nombre }}</li>
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
                    <div class="d-flex mb-3 align-items-center">
                        <h2>Datos del cliente: <b>{{ $adt->cliente_nombre }}</b></h2>
                        <div class="ms-auto">
                            <a href="{{ route('seguridad-adt.index') }}" class="btn btn-primary d-flex align-items-center gap-1">
                                <i class="ri-arrow-left-line"></i>
                                Regresar
                            </a>
                        </div>
                    </div>
                    {{-- Creamos una tabla con la informacion en vertical --}}
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <th>Id Lead</th>
                            <td>{{ $adt->contact_id ? $adt->contact_id : 'No se registro el LEAD correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Codificación</th>
                            <td>{{ $adt->codificacion ? $adt->codificacion : 'No se registro la codificación correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Usuario OCM</th>
                            <td>{{ $adt->login_ocm ? $adt->login_ocm : 'No se registro el usuario OCM correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Nombre del Agente</th>
                            <td>{{ $adt->nombre_agente ? $adt->nombre_agente : 'No se registro el nombre del agente correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>FECHA VENTA</th>
                            <td>{{ $adt->fecha_venta ? $adt->fecha_venta : 'No se registro la fecha de venta correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>CAMPAÑA</th>
                            <td>{{ $adt->campana ? $adt->campana : 'No se registro la campaña correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Cuenta ADT</th>
                            <td>{{ $adt->cuenta_adt ? $adt->cuenta_adt : 'No se registro la cuenta ADT correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Nombre del cliente</th>
                            <td>{{ $adt->cliente_nombre ? $adt->cliente_nombre : 'No se registro el nombre del cliente correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>RFC</th>
                            <td>{{ $adt->cliente_rfc ? $adt->cliente_rfc : 'No se registro el RFC correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono Fijo</th>
                            <td>{{ $adt->cliente_telefono ? $adt->cliente_telefono : 'No se registro el teléfono fijo correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono Celular</th>
                            <td>{{ $adt->cliente_celular ? $adt->cliente_celular : 'No se registro el teléfono celular correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Correo Electrónico</th>
                            <td>{{ $adt->cliente_correo ? $adt->cliente_correo : 'No se registro el correo electrónico correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Calle</th>
                            <td>{{ $adt->cliente_calle ? $adt->cliente_calle : 'No se registro la calle correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Número exterior</th>
                            <td>{{ $adt->cliente_numero ? $adt->cliente_numero : 'No se registro el número exterior correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Código postal</th>
                            <td>{{ $adt->cliente_cp ? $adt->cliente_cp : 'No se registro el código postal correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Estado</th>
                            <td>{{ $adt->cliente_estado ? $adt->cliente_estado : 'No se registro el estado correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Alcaldía / Municipio</th>
                            <td>{{ $adt->cliente_municipio ? $adt->cliente_municipio : 'No se registro la alcaldía / municipio correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Producto</th>
                            <td>{{ $adt->cliente_producto ? $adt->cliente_producto : 'No se registro el producto correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Tipo de producto</th>
                            <td>{{ $adt->cliente_tipo_producto ? $adt->cliente_tipo_producto : 'No se registro el tipo de producto correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Tipo de equipo</th>
                            <td>{{ $adt->cliente_tipo_equipo ? $adt->cliente_tipo_equipo : 'No se registro el tipo de equipo correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Plazo</th>
                            <td>{{ $adt->contrato_plazo ? $adt->contrato_plazo : 'No se registro el plazo correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Forma de pago</th>
                            <td>{{ $adt->forma_pago ? $adt->forma_pago : 'No se registro la forma de pago correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Nombre Emergencia Uno</th>
                            <td>{{ $adt->emergencia_nombre_uno ? $adt->emergencia_nombre_uno : 'No se registro el nombre de emergencia correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono emergencia uno</th>
                            <td>{{ $adt->emergencia_tel_uno ? $adt->emergencia_tel_uno : 'No se registro el teléfono de emergencia correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Nombre Emergencia dos</th>
                            <td>{{ $adt->emergencia_nombre_dos ? $adt->emergencia_nombre_dos : 'No se registro el nombre de emergencia correctamentee' }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono emergencia dos</th>
                            <td>{{ $adt->emergencia_tel_dos ? $adt->emergencia_tel_dos : 'No se registro el teléfono de emergencia correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Estatus de Venta</th>
                            <td>{{ $adt->estatus_venta ? $adt->estatus_venta : 'No se registro el estatus de venta correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de instalación</th>
                            <td>{{ $adt->fecha_instalacion ? $adt->fecha_instalacion : 'No se registro la fecha de instalación correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Estatus de instalación</th>
                            <td>{{ $adt->estatus_instalacion ? $adt->estatus_instalacion : 'No se registro el estatus de instalación correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Estatus de post instalacion</th>
                            <td>{{ $adt->estatus_post_instalacion ? $adt->estatus_post_instalacion : 'No se registro el estatus de  POST instalación correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>usuario tramitador</th>
                            <td>{{ $adt->nombre_tramitador ? $adt->nombre_tramitador : 'No se registro el usuario tramitador correctamente' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
