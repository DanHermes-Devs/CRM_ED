@extends('crm.layouts.app')
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ $venta->NombreDeCliente }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item">VENTAS</li>
                        <li class="breadcrumb-item active text-uppercase">{{ $venta->NombreDeCliente }}</li>
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
                        <h2>Datos del cliente: <b>{{ $venta->NombreDeCliente }}</b></h2>
                        <div class="ms-auto">
                            <a href="{{ route('ventas.index') }}" class="btn btn-primary d-flex align-items-center gap-1">
                                <i class="ri-arrow-left-line"></i>
                                Regresar
                            </a>
                        </div>
                    </div>
                    {{-- Creamos una tabla con la informacion en vertical --}}
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <th>Agente</th>
                            <td>{{ $venta->AgenteCob }}</td>
                        </tr>
                        <tr>
                            <th>Cliente</th>
                            <td>{{ $venta->Nombre }} {{ $venta->ApePaterno }} {{ $venta->ApeMaterno }}</td>
                        </tr>
                        <tr>
                            <th>Fecha</th>
                            <td>{{ $venta->Fpreventa }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de Nacimiento</th>
                            <td>{{ $venta->fNacimiento }}</td>
                        </tr>
                        <tr>
                            <th>Género</th>
                            <td>{{ $venta->Genero }}</td>
                        </tr>
                        <tr>
                            <th>RFC</th>
                            <td>{{ $venta->RFC }}</td>
                        </tr>
                        <tr>
                            <th>CURP</th>
                            <td>{{ $venta->CURP }}</td>
                        </tr>
                        <tr>
                            <th>Correo</th>
                            <td>{{ $venta->Correo }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono fijo</th>
                            {{-- Si existe un telefono, lo mostramos, de lo contrario mostrar mensaje de no se tiene regsitro de un numero fijo --}}
                            <td>{{ $venta->TelefonoFijo ? $venta->TelefonoFijo : 'No se tiene registro de un número fijo' }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono celular</th>
                            {{-- Si existe un telefono, lo mostramos, de lo contrario mostrar mensaje de no se tiene regsitro de un numero fijo --}}
                            <td>{{ $venta->TelCelular ? $venta->TelCelular : 'No se tiene registro de un número celular' }}</td>
                        </tr>
                        <tr>
                            <th>Calle</th>
                            <td>{{ $venta->Calle }}</td>
                        </tr>
                        <tr>
                            <th>Número exterior</th>
                            <td>{{ $venta->NumExt }}</td>
                        </tr>
                        <tr>
                            <th>Número interior</th>
                            <td>{{ $venta->NumInt }}</td>
                        </tr>
                        <tr>
                            <th>Colonia</th>
                            <td>{{ $venta->Colonia }}</td>
                        </tr>
                        <tr>
                            <th>Código postal</th>
                            <td>{{ $venta->CP }}</td>
                        </tr>
                        <tr>
                            <th>Estado</th>
                            <td>{{ $venta->Estado }}</td>
                        </tr>
                        <tr>
                            <th>Municipio</th>
                            <td>{{ $venta->AlMun }}</td>
                        </tr>
                        <tr>
                            <th>Marca</th>
                            <td>{{ $venta->Marca }}</td>
                        </tr>
                        <tr>
                            <th>Submarca</th>
                            <td>{{ $venta->SubMarca }}</td>
                        </tr>
                        <tr>
                            <th>Modelo</th>
                            <td>{{ $venta->Modelo }}</td>
                        </tr>
                        <tr>
                            <th>No. Serie</th>
                            <td>{{ $venta->nSerie }}</td>
                        </tr>
                        <tr>
                            <th>No. Motor</th>
                            <td>{{ $venta->nMotor }}</td>
                        </tr>
                        <tr>
                            <th>No. Placas</th>
                            <td>{{ $venta->nPlacas }}</td>
                        </tr>
                        <tr>
                            <th>Segmento</th>
                            <td>{{ $venta->Segmento }}</td>
                        </tr>
                        <tr>
                            <th>Legalizado</th>
                            <td>{{ $venta->Legalizado }}</td>
                        </tr>
                        <tr>
                            <th>Paquete</th>
                            <td>{{ $venta->Paquete }}</td>
                        </tr>
                        <tr>
                            <th>No. Poliza</th>
                            <td>{{ $venta->nPoliza }}</td>
                        </tr>
                        <tr>
                            <th>Aseguradora</th>
                            <td>{{ $venta->Aseguradora }}</td>
                        </tr>
                        <tr>
                            <th>No. Cotización</th>
                            <td>{{ $venta->nCotizacion }}</td>
                        </tr>
                        <tr>
                            <th>Forma de Pago</th>
                            <td>{{ $venta->fPago }}</td>
                        </tr>
                        <tr>
                            <th>Frecuencia de Pago</th>
                            <td>{{ $venta->FrePago }}</td>
                        </tr>
                        <tr>
                            <th>Tipo de Tarjeta</th>
                            <td>{{ $venta->tTarjeta }}</td>
                        </tr>
                        <tr>
                            <th>Prima Neta Anualizada</th>
                            <td>{{ $venta->PncTotal }}</td>
                        </tr>
                        <tr>
                            <th>Prima Neta Cobrada</th>
                            <td>{{ $venta->PrimaNetaCobrada }}</td>
                        </tr>
                        <tr>
                            <th>Tipo de Pago</th>
                            <td>{{ $venta->TipoPago }}</td>
                        </tr>
                        <tr>
                            <th>Estado de Pago</th>
                            <td>{{ $venta->EstadoDePago }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection