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
                            <form action="{{ route('grupos.store') }}" method="POST" novalidate>
                                @csrf
                                <div class="d-flex align-items-end gap-3">
                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="campana" class="form-label">Campaña:</label>
                                        <select name="campana" id="campana" class="form-select">
                                            <option value="0">-- Selecciona una Campaña --</option>
                                            @foreach ($campanas as $campana)
                                                <option value="{{ $campana->id }}"
                                                    {{ old('campana') == $campana->id ? 'selected' : '' }}>
                                                    {{ $campana->nombre_campana }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="supervisor" class="form-label">Supervisor:</label>
                                        <select name="supervisor" id="supervisor" class="form-select">
                                            <option value="0">-- Selecciona una Campaña --</option>
                                            @foreach ($supervisores as $supervisor)
                                                <option value="{{ $supervisor->id }}"
                                                    {{ old('supervisor') == $supervisor->id ? 'selected' : '' }}>
                                                    {{ $supervisor->usuario }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="fecha_pago_1" class="form-label">Rango Fecha:</label>
                                        <input type="text" name="fecha_pago_1" class="form-control" id="fecha_pago_1">
                                    </div>
                                    <button type="submit" id="buscar_asistencias"
                                        class="btn btn-primary waves-effect waves-light mb-3">Filtrar</button>
                                </div>
                            </form>
                        </div>

                        <div class="row">
                            <table class="table table-middle table-nowrap mb-0 display nowrap" id="tabla_asistencias">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Agente</th>
                                        <th>Total de Asistencias</th>
                                        <th>Total de Retardos</th>
                                        <th>Total de Faltas</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>
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

                    <ul class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-home-tab" data-bs-toggle="tab"
                                data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true">Asistencias</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-profile-tab" data-bs-toggle="tab"
                                data-bs-target="#pills-profile" type="button" role="tab"
                                aria-controls="pills-profile" aria-selected="false">Incidencias</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="tab-home-tab" tabindex="0">
                            <table id="tab_asistencias" class="table table-middle table-nowrap mb-0 display nowrap">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Entrada</th>
                                        <th>Salida</th>
                                        <th>Asistencia</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="tab-profile-tab" tabindex="0">
                            <form id="form">
                                <div class="row align-items-end mb-4">
                                    <div class="col-12 col-md-3">
                                        <div class="form-label">Tipo de Incidencia</div>
                                        <select class="form-select" name="tipo_incidencia">
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

                                    <div class="col-12 col-md-3">
                                        <label for="fecha_inicio">Fecha Inicio:</label>
                                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="fecha_fin">Fecha Fin:</label>
                                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                                    </div>
                                    
                                    <div class="col-12 col-md-3">
                                        <input type="submit" value="Guardar Incidencia" id="guardar_incidencia" class="btn btn-primary">
                                    </div>
                                </div>

                                <div class="row">
                                    <table id="tab_incidencias" class="table table-middle table-nowrap mb-0 display nowrap">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Agente</th>
                                                <th>Tipo de Incidencia</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Fin</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="modal fade" id="asistenciaModal" tabindex="-1" aria-labelledby="asistenciaModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="modal_dialog_asistenciaModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="asistenciaModalLabel">Editar Asistencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="form_editar_asistencia">
                        <div class="row mb-3">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="motivo" class="form-label">Motivo</label>
                                <select name="motivo" id="motivo" class="form-select">
                                    <option>-- Selecciona una opción --</option>
                                    <option value="IE">INCAPACIDAD POR ENFERMEDAD</option>
                                    <option value="IM">INCAPACIDAD POR MATERNIDAD</option>
                                    <option value="IR">INCAPACIDAD POR RIESGO DE TRABAJO</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="documentacion" class="form-label">¿Cuenta con la documentación?</label>
                                <select name="documentacion" id="documentacion" class="form-select">
                                    <option>-- Selecciona una opción --</option>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="submit" value="Actualizar asistencia" class="btn btn-primary" disabled>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var start_date = null;
            var end_date = null;

            $('#fecha_pago_1').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: 'Aplicar',
                    cancelLabel: 'Limpiar',
                },
            });

            $('input[name="fecha_pago_1"]').on('apply.daterangepicker', function(ev, picker) {
                // Almacena las fechas seleccionadas en las variables
                start_date = picker.startDate.format('YYYY-MM-DD');
                end_date = picker.endDate.format('YYYY-MM-DD');
            });

            $('body').on('click', '#buscar_asistencias', function(e) {
                e.preventDefault();
                $('#tabla_asistencias').DataTable().destroy();
                $('#tabla_asistencias').show();

                var campana = $('#campana').val();
                var supervisor = $('#supervisor').val();
                var fecha_inicio = start_date;
                var fecha_fin = end_date;

                $('#tabla_asistencias').DataTable({
                    scrollCollapse: true,
                    processing: true,
                    serverSide: true,
                    bAutoWidth: false,
                    ajax: {
                        url: "{{ route('asistencias') }}",
                        type: 'GET',
                        data: {
                            campana: campana,
                            supervisor: supervisor,
                            fecha_inicio: fecha_inicio,
                            fecha_fin: fecha_fin,
                        }
                    },
                    columns: [{
                            data: 'name',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                        {
                            data: 'usuario',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                        {
                            data: 'total_asistencias',
                            render: function(data, type, row) {
                                if (data == 0) {
                                    return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary fs-5">0</span>`;
                                } else {
                                    return `<span class="badge rounded-pill badge-soft-success badge-border text-success fs-5">${data}</span>`;
                                }
                            }
                        },
                        {
                            data: 'total_retardos',
                            render: function(data, type, row) {
                                if (data == 0) {
                                    return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary fs-5">0</span>`;
                                } else {
                                    return `<span class="badge rounded-pill badge-soft-warning badge-border text-warning fs-5">${data}</span>`;
                                }
                            }
                        },
                        {
                            data: 'total_faltas',
                            render: function(data, type, row) {
                                if (data == 0) {
                                    return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary fs-5">0</span>`;
                                } else {
                                    return `<span class="badge rounded-pill badge-soft-danger badge-border text-danger fs-5">${data}</span>`;
                                }
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    columnDefs: [{
                        targets: [2, 3, 4],
                        className: 'text-center align-middle'
                    }],
                    language: idiomaDataTable
                });
            })

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
                        console.log(response.usuario.usuario);
                        $('#name_agent').val(response.usuario.name);
                        $('#user_id').val(response.usuario.id);
                        $('#usuario_name').val(response.usuario.usuario);
                    }
                });

                // Creamos la url para enviarla al controlador
                var url_2 = "{{ route('consultar-asistencia-usuario', ':id') }}";
                url_2 = url_2.replace(':id', user_id);

                $('#tab_asistencias').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    bAutoWidth: true,
                    ajax: {
                        url: url_2,
                        type: 'GET',
                        data: {
                            user_id: user_id,
                        }
                    },
                    columns: [{
                            data: 'fecha_login',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                        {
                            data: 'hora_login',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                        {
                            data: 'hora_logout',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                        {
                            data: 'tipo_asistencia',
                            render: function(data, type, row) {
                                if(data == 'A'){
                                    return `<span class="badge rounded-pill badge-soft-success badge-border text-success fs-5">ASISTENCIA</span>`;
                                }else if(data == 'A+'){
                                    return `<span class="badge rounded-pill badge-soft-success badge-border text-success fs-5">ASISTENCIA (+)</span>`;
                                }else if(data == 'F'){
                                    return `<span class="badge rounded-pill badge-soft-danger badge-border text-danger fs-5">FALTA</span>`;
                                }else if(data == 'R'){
                                    return `<span class="badge rounded-pill badge-soft-warning badge-border text-warning fs-5">RETARDO</span>`;
                                }
                            }
                        },
                        {
                            data: 'action',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                    ],
                    language: idiomaDataTable
                });
                
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
                    ],
                    language: idiomaDataTable
                });

                // $('#tabla_usuarios').DataTable().ajax.reload();
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Incidencia Guardada',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#tab_incidencias').DataTable().ajax.reload();
                        }
                    }
                });
            });

            $('body').on('click', '.modal_show_asistencia', function(e){
                e.preventDefault();
                $('#asistenciaModal').modal('show');
                var asistencia_id = $(this).data('id');

                // Si el select con id documentacion cambia de valor a si, se quita el atributo disabled del boton si es no, no deja guardar
                $('#documentacion').on('change', function(){
                    if($(this).val() == 'SI'){
                        $('input[type="submit"]').removeAttr('disabled');

                        $.ajax({
                            type: "method",
                            url: "url",
                            data: "data",
                            dataType: "dataType",
                            success: function (response) {
                                
                            }
                        });
                    }else{
                        $('input[type="submit"]').attr('disabled', 'disabled');
                    }
                });
            });
        });
    </script>
@endsection