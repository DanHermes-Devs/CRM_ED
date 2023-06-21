@extends('crm.layouts.app')
<style>
    .grid-search {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        align-items: end;
    }


    @media (max-width: 768px) {
        .grid-search {
            grid-template-columns: repeat(1, minmax(250px, 1fr));
            gap: 1.5rem;
            align-items: end;
        }
    }

    @media (min-width: 992px){
        .modal-lg, .modal-xl {
            max-width: 1200px!important;
        }

        #modal_dialog_asistenciaModal {
            max-width: 700px!important;
        }
    }

    table#tab_asistencias {
        width: 100%!important;
    }

    table#tab_incidencias {
        width: 100%!important;
    }

    #tabla_asistencias {
        display: none;
    }

    div#tabla_asistencias_wrapper {
        width: 100%;
    }

    .mr_3 {
        margin-right: 1rem !important;
    }

    a.link-personalizado {
        text-decoration: underline!important;
    }
</style>
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">ASISTENCIAS</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">ASISTENCIAS</li>
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

                        <div class="d-flex justify-content-between">
                            <h4 class="text-left mb-3">ASISTENCIAS</h4>
                            <a href="{{ route('grupos.index') }}" class="btn btn-info mb-3">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-arrow-left-line"></i>
                                    Regresar
                                </div>
                            </a>
                        </div>

                        {{-- Formulario para agregar nuevo usuario --}}
                        <div class="row">
                            <form action="{{ route('asistencias') }}" method="GET">
                                <div class="row align-items-end">
                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="campana" class="form-label">Campaña:</label>
                                        <select name="campana" id="campana" class="form-select">
                                            <option value="">-- Selecciona una Campaña --</option>
                                            @foreach ($campanas as $campana)
                                                <option value="{{ $campana->id }}" {{ old('campana') == $campana->id ? 'selected' : '' }}>
                                                    {{ $campana->nombre_campana }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="supervisor" class="form-label">Supervisor:</label>
                                        <select name="supervisor" id="supervisor" class="form-select">
                                            <option value="">-- Selecciona un Supervisor --</option>
                                            @foreach ($supervisores as $supervisor)
                                                <option class="text-uppercase" value="{{ $supervisor->id }}"
                                                    {{ old('supervisor') == $supervisor->id ? 'selected' : '' }}>
                                                    {{ $supervisor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="id_campana" id="id_campana">
                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="agente" class="form-label">Agente:</label>
                                        <select name="agente" id="agente" class="form-select">
                                            <option value="">-- Selecciona un Agente --</option>
                                            @foreach ($agentes as $agente)
                                                <option class="text-uppercase" value="{{ $agente->id }}"
                                                    {{ old('agente') == $agente->id ? 'selected' : '' }}>
                                                    {{ $agente->apellido_paterno }} {{ $agente->apellido_materno }} {{ $agente->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="fecha_pago_1" class="form-label">Fecha 1:</label>
                                        <input type="date" name="fecha_pago_1" class="form-control" id="fecha_pago_1" value="{{ old('fecha_pago_1') }}">

                                    </div>
                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="fecha_pago_2" class="form-label">Fecha 2:</label>
                                        <input type="date" name="fecha_pago_2" class="form-control" id="fecha_pago_2" value="{{ old('fecha_pago_2') }}">
                                    </div>
                                    <div class="col-12 col-md-3 mb-3">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light w-100">Filtrar</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-middle table-nowrap mb-0 display nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Agente</th>
                                            <th class="text-center">Total de Asistencias</th>
                                            <th class="text-center">Total de Retardos</th>
                                            <th class="text-center">Total de Faltas</th>
                                            @foreach ($fechas as $fecha)
                                                <th class="text-center">{{ $fecha->format('d-m-Y') }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($usuarios as $usuario)
                                            <tr>
                                                <td>{{ $usuario->apellido_paterno }} {{ $usuario->apellido_materno }} {{ $usuario->name }}</td>
                                                <td>{{ $usuario->usuario }}</td>
                                                <td class="text-center">
                                                    @if ($usuario->total_asistencias == 0)
                                                        <span
                                                            class="badge rounded-pill badge-soft-primary badge-border text-primary fs-5">
                                                            0
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge rounded-pill badge-soft-success badge-border text-success fs-5">
                                                            {{ $usuario->total_asistencias }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($usuario->total_retardos == 0)
                                                        <span
                                                            class="badge rounded-pill badge-soft-primary badge-border text-primary fs-5">
                                                            0
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge rounded-pill badge-soft-warning badge-border text-warning fs-5">
                                                            {{ $usuario->total_retardos }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($usuario->total_faltas == 0)
                                                        <span
                                                            class="badge rounded-pill badge-soft-primary badge-border text-primary fs-5">
                                                            0
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge rounded-pill badge-soft-danger badge-border text-danger fs-5">
                                                            {{ $usuario->total_faltas }}
                                                        </span>
                                                    @endif
                                                </td>
                                                @foreach ($fechas as $fecha)
                                                @php
                                                    $asistencia = $usuario->attendances->first(function ($asistencia) use ($fecha) {
                                                        $fechaAsistencia = \Carbon\Carbon::parse($asistencia->fecha_login);
                                                        return $fechaAsistencia->format('Y-m-d') == $fecha->format('Y-m-d');
                                                    });
                                                @endphp
                                                <td class="text-center">
                                                    @if ($asistencia)
                                                        <a href="#" class="modal_show_incidencias link-personalizado" data-id="{{ $usuario->id }}">
                                                            {{ $asistencia->tipo_asistencia }}
                                                        </a>
                                                    @else
                                                        <a href="#" class="modal_show_incidencias link-personalizado" data-id="{{ $usuario->id }}">
                                                            Sin dato
                                                        </a>
                                                    @endif
                                                </td>
                                            @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {!! $usuarios->appends(request()->except('_token'))->links('pagination::bootstrap-4') !!}
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div>
    <!-- container-fluid -->

    <div class="modal fade" id="incidenciaModal" tabindex="-1" aria-labelledby="incidenciaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="incidenciaModalLabel">Incidencias</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-label">Nombre Agente</div>
                            <input type="text" name="name_agent" id="name_agent" readonly class="form-control">
                            <input type="hidden" name="user_id" id="user_id" readonly class="form-control">
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-label">Usuario Agente</div>
                            <input type="text" name="usuario_name" id="usuario_name" readonly class="form-control">
                        </div>
                    </div>

                    <form id="form">
                        <input type="hidden" name="incidencia_id" id="incidencia_id">
                        <div class="row align-items-end mb-4">
                            <div class="col-12 col-md-3">
                                <div class="form-label">Tipo de Incidencia</div>
                                <select class="form-select" name="tipo_incidencia" id="tipo_incidencia">
                                    <option>-- Selecciona una opción --</option>
                                    <option value="A">ASISTENCIA</option>
                                    <option value="C">CAPACITACIÓN</option>
                                    <option value="V">VACACIONES</option>
                                    <option value="F">FALTA</option>
                                    <option value="FJ">FALTA JUSTIFICADA</option>
                                    <option value="DL">DESCANSO LABORADO</option>
                                    <option value="DFL">DIA FESTIVO LABORADO</option>
                                    <option value="IE">INCAPACIDAD POR ENFERMEDAD</option>
                                    <option value="IM">INCAPACIDAD POR MATERNIDAD</option>
                                    <option value="IR">INCAPACIDAD POR RIESGO DE TRABAJO</option>
                                    <option value="PD">PRIMA DOMINICAL</option>
                                    <option value="D">DESCANSO</option>
                                    <option value="PSG">PERMISO SIN GOCE DE SUELDO</option>
                                    <option value="S">SUSPENSIÓN</option>
                                    <option value="PP">PERMISO POR PATERNIDAD</option>
                                    <option value="PDEF">PERMISO POR DEFUNCIÓN</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="fecha_inicio">Fecha Inicio:</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="fecha_fin">Fecha Fin:</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                            </div>

                            <div class="col-12 col-md-3 d-none" id="entrega_de_documentos">
                                <label for="entrega_documentos" class="entrego_documentos">¿Entrega documentos?</label>
                                <select name="entrega_documentos" id="entrega_documentos" class="form-select">
                                    <option>-- Selecciona una opción --</option>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <input type="submit" value="Guardar Incidencia" id="guardar_incidencia" class="btn btn-primary">
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <table id="tab_incidencias" class="table table-middle table-nowrap mb-0 display nowrap">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Agente</th>
                                    <th>Log</th>
                                    <th>Tipo de Incidencia</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#campana').change(function() {
                var campaignId = $(this).val();
                if (campaignId) {
                    $.ajax({
                        url: '/get-supervisores/' + campaignId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#supervisor').empty();
                            $('#supervisor').append('<option value="">-- Selecciona un Supervisor --</option>');
                            // En el id id_campana guardamos el id de la campaña seleccionada
                            $('#id_campana').val(campaignId);
                            $.each(data, function(key, value) {
                                $('#supervisor').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                            });
                        }
                    });
                }
            });

            $('#supervisor').change(function() {
                var supervisorId = $(this).val();
                var group_id = $('#id_campana').val();
                if (supervisorId) {
                    $.ajax({
                        url: '/get-agentes/' + supervisorId + '/' + group_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#agente').empty();
                            $('#agente').append('<option value="">-- Selecciona un Agente --</option>');
                            $.each(data, function(key, value) {
                                $('#agente').append('<option value="'+ value.id +'">'
                                    + value.apellido_paterno + " "
                                    + value.apellido_materno + " "
                                    + value.name +
                                '</option>');
                            });
                        }
                    });
                }
            });

            // AL DAR CLIC EN EL BOTON EDITAR QUE TIENE UN data-id, SE ABRE UN SWEETALERT CON UN FORMULARIO PARA EDITAR EL REGISTRO
            $('body').on('click', '.edit_btn', function(e){
                e.preventDefault();
                var incidencia_id = $(this).data('id');

                // Creamos la url para enviarla al controlador
                var url = "{{ route('editar-incidencia', ':id') }}";
                url = url.replace(':id', incidencia_id);

                // Creamos el ajax para consultar al controlador
                $.ajax({
                    type: "GET",
                    url: url,
                    data: incidencia_id,
                    dataType: "JSON",
                    success: function(response) {
                        if(response.code == 200){
                            $('#incidencia_id').val(response.incidencia.id);
                            $('#tipo_incidencia').val(response.incidencia.tipo_incidencia);
                            $('#fecha_inicio').val(response.incidencia.fecha_desde);
                            $('#fecha_fin').val(response.incidencia.fecha_hasta);
                            $('#entrega_documentos').val(response.incidencia.entrega_documentos);
                            $('#observaciones').val(response.incidencia.observaciones);
                        }
                    }
                });
            });

            $('body').on('click', '.modal_show_incidencias', function(e) {
                e.preventDefault();
                $('#tab_asistencias').DataTable().destroy();
                $('#tab_incidencias').DataTable().destroy();
                var user_id = $(this).data('id');
                $('#incidenciaModal').modal('show');

                // Creamos la url para enviarla al controlador
                var url = "{{ route('consultar-usuario', ':id') }}";
                url = url.replace(':id', user_id);

                // Creamos el ajax para consultar al controlador
                $.ajax({
                    type: "GET",
                    url: url,
                    data: user_id,
                    dataType: "JSON",
                    success: function(response) {
                        var nombreCompleto = response.usuario.apellido_paterno + ' ' + response.usuario.apellido_materno + ' ' + response.usuario.name;
                        $('#name_agent').val(nombreCompleto);
                        $('#user_id').val(response.usuario.id);
                        $('#usuario_name').val(response.usuario.usuario);
                    }
                });

                // Creamos la url para enviarla al controlador
                var url_2 = "{{ route('consultar-asistencia-usuario', ':id') }}";
                url_2 = url_2.replace(':id', user_id);

                // Creamos la url para enviarla al controlador
                var url_3 = "{{ route('consultar-incidencias-usuario', ':id') }}";
                url_3 = url_3.replace(':id', user_id);

                $('#tab_incidencias').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    bAutoWidth: true,
                    ajax: {
                        url: url_3,
                        type: 'GET',
                        data: {
                            user_id: user_id,
                        }
                    },
                    columns: [{
                            data: 'formatted_date',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                        {
                            data: 'agente',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                        {
                            data: 'user_modification',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                        {
                            data: 'tipo_incidencia',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                        {
                            data: 'fecha_desde',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                        {
                            data: 'fecha_hasta',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                        { data: 'action', name: 'action'},
                    ],
                    language: idiomaDataTable
                });

                // $('#tabla_usuarios').DataTable().ajax.reload();
            });

            // DETECTAMOS CUANDO EL SELECT CON ID TIPO_INCIDENCIA CAMBIA DE VALOR Y SI EL VALOR ES IE, IM E IR LANZAMOS UN SWEETALERT CON EL SIGUIENTE MENSAJE "RECUERDA VALIDAR QUE EL EMPLEADO ENTREGUE LA DOCUMENTACION COMPLETA" Y LE DAMOS LA OPCION AL USUARIO DE ACEPTAR PARA CERRAR EL ALERT
            $('select[name="tipo_incidencia"]').on('change', function(){
                if($(this).val() == 'IE' || $(this).val() == 'IM' || $(this).val() == 'IR'){
                    // BLOQUEAMOS EL BOTON DE GUARDAR INCIDENCIA
                    $('input[type="submit"]').attr('disabled', 'disabled');

                    // MOSTRAMOS EL SELECT CON ID ENTREGA_DOCUMENTOS
                    $('#entrega_de_documentos').removeClass('d-none');

                    Swal.fire({
                        title: 'Recuerda validar que el empleado entregue la documentación completa',
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    });
                }else{
                    // QUITAMOS EL ATRIBUTO DISABLED DEL BOTON DE GUARDAR INCIDENCIA
                    $('input[type="submit"]').removeAttr('disabled');

                    // OCULTAMOS EL SELECT CON ID ENTREGA_DOCUMENTOS
                    $('#entrega_de_documentos').addClass('d-none');
                }
            });

            // DETECTAMOS CUANDO EL SELECT CON ID ENTREGA_DOCUMENTOS CAMBIA DE VALOR Y SI EL VALOR ES SI, QUITAMOS EL ATRIBUTO DISABLED DEL BOTON DE GUARDAR INCIDENCIA
            $('select[name="entrega_documentos"]').on('change', function(){
                if($(this).val() == 'SI'){
                    $('input[type="submit"]').removeAttr('disabled');
                }else{
                    $('input[type="submit"]').attr('disabled', 'disabled');
                }
            });

            $('body').on('click', '#guardar_incidencia', function(e){
                e.preventDefault();

                var user_id = $('#user_id').val();
                var usuario_name = $('#usuario_name').val();
                var tipo_incidencia = $('select[name="tipo_incidencia"]').val();
                var fecha_inicio = $('input[name="fecha_inicio"]').val();
                var fecha_fin = $('input[name="fecha_fin"]').val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('crear-incidencia') }}",
                    data: {
                        user_id: user_id,
                        usuario_name: usuario_name,
                        tipo_incidencia: tipo_incidencia,
                        fecha_inicio: fecha_inicio,
                        fecha_fin: fecha_fin,
                    },
                    dataType: "JSON",
                    success: function (response) {
                        if(response.code == 200){
                            // MOSTRAMOS UN SWEETALERT DE EXITO Y AL DAR EN ACEPTAR RECARGA LA PAGINA ACTUAL
                            Swal.fire({
                                title: 'Incidencia guardada correctamente',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                            $('#tab_incidencias').DataTable().ajax.reload();
                        }
                    }
                });
            });
        });
    </script>
@endsection
