@extends('crm.layouts.app')
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ $coti->client_name }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item">Cotización</li>
                        <li class="breadcrumb-item active text-uppercase">{{ $coti->client_name }}</li>
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
                        <h2>Datos del cliente: <b>{{ $coti->client_name }}</b></h2>
                        <div class="ms-auto">
                            <a href="{{ route('educacion-uin.index') }}" class="btn btn-primary d-flex align-items-center gap-1">
                                <i class="ri-arrow-left-line"></i>
                                Regresar
                            </a>
                        </div>
                    </div>
                    {{-- Creamos una tabla con la informacion en vertical --}}
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <th>Fecha de ingreso a ZEUS</th>
                            <td>{{ $coti->fp_venta ? $coti->fp_venta : 'No se registro la fecha de cotización correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Cliente</th>
                            <td>{{ $coti->client_name ? $coti->client_name : 'No se registro el nombre del cliente correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Codificación</th>
                            <td>{{ $coti->codification ? $coti->codification : 'No se registro la codificación correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Fecha ingresada en OCM</th>
                            <td>{{ $coti->schedule_date ? $coti->schedule_date : 'No se registro la fecha de OCM correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono Fijo</th>
                            <td>{{ $coti->client_landline ? $coti->client_landline : 'No se tiene registro de un número fijo' }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono Celular</th>
                            <td>{{ $coti->client_celphone ? $coti->client_celphone : 'No se tiene registro de un número celular' }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de Nacimiento</th>
                            <td>{{ $coti->client_birth ? $coti->client_birth : 'No se registro la fecha de Nacimiento correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Género</th>
                            <td>{{ $coti->client_sex ? $coti->client_sex : 'No se registro el genero correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Modalidad</th>
                            <td>{{ $coti->client_modality ? $coti->client_modality : 'No se registro la modalidad correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Programa</th>
                            <td>{{ $coti->client_program ? $coti->client_program : 'No se registro el programa correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Especialidad</th>
                            <td>{{ $coti->client_specialty ? $coti->client_specialty : 'No se registro la especialidad correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Calle de vivienda</th>
                            <td>{{ $coti->client_street ? $coti->client_street : 'No se registro la calle correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Número de vivienda</th>
                            <td>{{ $coti->client_number ? $coti->client_number : 'No se registro el numero correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Delegación / Municipio</th>
                            <td>{{ $coti->client_delegation ? $coti->client_delegation : 'No se registro la delegación o municipio correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Estado</th>
                            <td>{{ $coti->client_state ? $coti->client_state : 'No se registro el estado correctamente' }}</td>
                        </tr>
                        <tr>
                            <th>Se cargo el acta de nacimiento</th>
                            <td>{{ $coti->birth_certifcate ? $coti->birth_certifcate : 'NO' }}</td>
                        </tr>
                        <tr>
                            <th>Se cargo El CURP</th>
                            <td>{{ $coti->curp_certificate ? $coti->curp_certificate : 'NO' }}</td>
                        </tr>
                        <tr>
                            <th>Se cargo el INE</th>
                            <td>{{ $coti->ine_certifcate ? $coti->ine_certifcate : 'NO' }}</td>
                        </tr>
                        <tr>
                            <th>Se cargo la solicitud de inscripción</th>
                            <td>{{ $coti->inscripcion_certificate ? $coti->inscripcion_certificate : 'NO' }}</td>
                        </tr>
                        <tr>
                            <th>Se cargo el comprobante de domicilio</th>
                            <td>{{ $coti->domicilio_certifcate ? $coti->domicilio_certifcate : 'NO' }}</td>
                        </tr>
                        <tr>
                            <th>Se el certificado de estudios nivel medio - titulo</th>
                            <td>{{ $coti->estudio_certifcate ? $coti->estudio_certifcate : 'NO' }}</td>
                        </tr>
                        <tr>
                            <th>Se cargo la cotización</th>
                            <td>{{ $coti->cotizacion_certifcate ? $coti->cotizacion_certifcate : 'NO' }}</td>
                        </tr>
                        <tr>
                            <th>Se cargo comprobante de pago</th>
                            <td>{{ $coti->pago_certifcate ? $coti->pago_certifcate : 'NO' }}</td>
                        </tr>
                        <tr>
                            <th>Cuenta Universidad Insurgentes</th>
                            <td>{{ $coti->client_matricula ? $coti->client_matricula :'No se encuentra registrada la cuenta UIN actuamente' }}</td>
                        </tr>
                        @if( $coti->client_modality === 'PRESENCIAL')
                        <tr>
                            <th>PLANEL</th>
                            <td>{{ $coti->client_plantel ? $coti->client_plantel :'No se encuentra registrada la cuenta UIN actuamente' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
