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

    #tabla_cobranza {
        display: none;
    }

    div#tabla_cobranza_wrapper {
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
                    <h4 class="mb-sm-0">COBRANZA</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">COBRANZA</li>
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
                        <h1>Recibos pendientes</h1>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>¡Éxito!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <form class="d-flex align-items-center flex-column flex-md-row gap-2">
                            <div class="mb-3 w-100">
                                <label for="tipo_recibos">Tipo de recibos:</label>
                                <select name="tipo_recibos" id="tipo_recibos" class="form-select">
                                    <option value="TODOS" {{ $tipoRecibos === 'TODOS' ? 'selected' : '' }}>Todos los
                                        recibos</option>
                                    <option value="MIS_RECIBOS" {{ $tipoRecibos === 'MIS_RECIBOS' ? 'selected' : '' }}>Mis
                                        recibos</option>
                                </select>
                            </div>
                            <div class="mb-3 w-100">
                                <label for="fecha_pago_1">Rango de fecha:</label>
                                <input type="text" name="fecha_pago_1" class="form-control" id="fecha_pago_1">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="estado_pago">Estado de Pago:</label>
                                <select name="estado_pago" id="estado_pago" class="form-select">
                                    <option value="TODOS">-- Selecciona una opción --</option>
                                    <option value="PAGADO">PAGADO</option>
                                    <option value="PENDIENTE">PENDIENTE</option>
                                    <option value="LIQUIDADO">LIQUIDADO</option>
                                </select>
                            </div>
                            <button type="submit" id="buscarDatos" class="btn btn-primary mt-2 w-100">Filtrar</button>
                        </form>

                        <table class="table table-middle table-nowrap mb-0" id="tabla_cobranza">
                            <thead>
                                <tr>
                                    <th>Lead</th>
                                    <th>Nombre Cliente</th>
                                    <th>Póliza</th>
                                    <th>Nueva Póliza</th>
                                    <th>Teléfono Celular</th>
                                    <th>Teléfono Fijo</th>
                                    <th>#Recibo</th>
                                    <th>Fecha Próximo Pago</th>
                                    <th>Estado de Pago</th>
                                    <th>Agente cobrador</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para asignar recibo -->
    <div class="modal fade" id="asignarReciboModal" tabindex="-1" role="dialog" aria-labelledby="asignarReciboModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="asignarReciboModalLabel">Asignar Recibo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="asignarReciboForm">
                        <input type="hidden" id="recibo_id" name="recibo_id">
                        <div class="form-group mb-3">
                            <label for="agente_venta_id">Selecciona un Agente de Venta</label>
                            <select class="form-select" id="agente_venta_id" name="agente_venta_id">
                                <option selected>-- Selecciona un agente de venta --</option>
                                @foreach ($agentes_ventas as $agente_venta)
                                    <option value="{{ $agente_venta->id }}">{{ $agente_venta->usuario }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Asignar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para reasignar recibo -->
    <div class="modal fade" id="reasignarReciboModal" tabindex="-1" role="dialog"
        aria-labelledby="reasignarReciboModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reasignarReciboModalLabel">Asignar Recibo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reasignarReciboForm">
                        <input type="hidden" id="reasignar_recibo_id" name="recibo_id">
                        <div class="form-group mb-3">
                            <label for="reasignar_agente_venta_id">Selecciona un Agente de Venta</label>
                            <select class="form-select" id="reasignar_agente_venta_id" name="agente_venta_id">
                                <option selected>-- Selecciona un agente de venta --</option>
                                @foreach ($agentes_ventas as $agente_venta)
                                    <option value="{{ $agente_venta->id }}">{{ $agente_venta->usuario }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Asignar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para reasignar recibo -->
    <div class="modal fade" id="editarReciboModal" tabindex="-1" role="dialog"
        aria-labelledby="editarReciboModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarReciboModalLabel">Editar Recibo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editarReciboForm">
                        <input type="hidden" id="editar_recibo_id" name="recibo_id">
                        <div class="form-group mb-3">
                            <label for="estado_pago" class="form-label">Estado de Pago:</label>
                            <select class="form-select" id="estado_pago_edit" name="estado_pago">
                                <option selected>-- Selecciona un estado de pago --</option>
                                <option value="PAGADO">PAGADO</option>
                                <option value="PENDIENTE">PENDIENTE</option>
                                <option value="LIQUIDADO">LIQUIDADO</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="prima_cobrada" class="form-label">Prima Cobrada:</label>
                            <input type="text" pattern="^\d*(\.\d{0,2})?$" class="form-control" id="prima_cobrada"
                                name="prima_cobrada">
                        </div>
                        <div class="form-group mb-3">
                            <label for="observations" class="form-label">Observaciones:</label>
                            <textarea class="form-control" id="observations" name="observations" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Editar</button>
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
                maxSpan: {
                    days: 31
                },
                locale: {
                    format: 'DD/MM/YYYY',
                    applyLabel: 'Aplicar',
                    cancelLabel: 'Cancelar',
                },
            });

            $('input[name="fecha_pago_1"]').on('apply.daterangepicker', function(ev, picker) {
                // Almacena las fechas seleccionadas en las variables
                start_date = picker.startDate.format('YYYY-MM-DD');
                end_date = picker.endDate.format('YYYY-MM-DD');
            });

            var usuarioAutenticadoId = "{{ auth()->user()->id }}";
            $('body').on('click', '#buscarDatos', function(e) {
                e.preventDefault();
                $('#tabla_cobranza').DataTable().destroy();
                $('#tabla_cobranza').show();

                let tipoRecibos = $('#tipo_recibos').val();
                let fecha_pago_1 = $('#fecha_pago_1').val();
                let fecha_pago_2 = $('#fecha_pago_2').val();
                let estado_pago = $('#estado_pago').val();

                $('#tabla_cobranza').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    bAutoWidth: false,
                    ajax: {
                        url: "{{ route('cobranza.index') }}",
                        type: 'GET',
                        data: {
                            tipo_recibos: tipoRecibos,
                            fecha_pago_1: start_date,
                            fecha_pago_2: end_date,
                            estado_pago: estado_pago
                        }
                    },
                    columns: [{
                            data: 'venta.contactId',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                        {
                            data: 'venta.Nombre',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                        {
                            data: 'venta.nPoliza',
                            render: function(data, type, row) {
                                // SI no tiene una poliza anterior retornamos un badge con la leyenda "Sin póliza anterior"
                                if (data === null) {
                                    return `<span class="badge rounded-pill badge-soft-danger badge-border">Sin póliza anterior</span>`;
                                } else {
                                    return `${data}`;
                                }
                            }
                        },
                        {
                            data: 'venta.nueva_poliza',
                            render: function(data, type, row) {
                                // Si no tiene una nueva poliza, retornamos un badge con la leyenda "Sin nueva póliza"
                                if (data === null) {
                                    return `<span class="badge rounded-pill badge-soft-danger badge-border">Sin nueva póliza</span>`;
                                } else {
                                    return `${data}`;
                                }
                            }
                        },
                        {
                            data: 'venta.TelCelular',
                            render: function(data, type, row) {
                                // Si no tiene un teléfono celular, retornamos un badge con la leyenda "Sin teléfono Celular"
                                if (data === null) {
                                    return `<span class="badge rounded-pill badge-soft-danger badge-border">Sin teléfono Celular</span>`;
                                } else {
                                    return `${data}`;
                                }
                            }
                        },
                        {
                            data: 'venta.TelFijo',
                            render: function(data, type, row) {
                                // Si no tiene un teléfono fijo, retornamos un badge con la leyenda "Sin teléfono Fijo"
                                if (data === null) {
                                    return `<span class="badge rounded-pill badge-soft-danger badge-border">Sin teléfono Fijo</span>`;
                                } else {
                                    return `${data}`;
                                }
                            }
                        },
                        {
                            data: 'num_pago',
                            name: 'num_pago'
                        },
                        {
                            data: 'fecha_proximo_pago',
                            name: 'fecha_proximo_pago'
                        },
                        {
                            data: 'estado_pago',
                            name: 'estado_pago'
                        },
                        {
                            data: 'agente_cob_id',
                            name: 'agente_cob_id'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    language: idiomaDataTable
                });
            });

            $(document).on('click', '.asignar-recibo', function(e) {
                e.preventDefault();
                var reciboId = $(this).data('id');
                $('#recibo_id').val(reciboId);
                $('#asignarReciboModal').modal('show');
            });

            $(document).on('click', '.editar-recibo', function(e) {
                e.preventDefault();
                var reciboId = $(this).data('id');
                $('#editar_recibo_id').val(reciboId);
                $('#editarReciboModal').modal('show');

                // Consultamos a la base de datos
                var route = '{{ route('cobranza.edit', ':id') }}';
                var url = route.replace(':id', reciboId);

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        if (response.code == 200) {
                            // Pintamos los datos en los inputs
                            $('#prima_cobrada').val(response.recibo.prima_neta_cobrada);
                            $('#estado_pago_edit').val(response.recibo.estado_pago);
                            $('#observations').val(response.recibo.observations);
                        }
                    }
                });
            });


            $('#editarReciboForm').on('submit', function(e) {
                e.preventDefault();

                var reciboId = $('#editar_recibo_id').val();
                var prima_cobrada = $('#prima_cobrada').val();
                var estado_pago = $('#estado_pago_edit').val();
                var observations = $('#observations').val();

                var route = '{{ route('cobranza.update', ':id') }}';
                var url = route.replace(':id', reciboId);

                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: {
                        recibo_id: reciboId,
                        prima_cobrada: prima_cobrada,
                        estado_pago: estado_pago,
                        observations: observations
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Si el response.code es igual a 200 mandamos un sweet alert de éxito
                        if (response.code == 200) {
                            Swal.fire(
                                'Éxito',
                                response.message,
                                'success'
                            )
                        }
                        $('#editarReciboModal').modal('hide');
                        // Limpiamos el formulario
                        $('#editarReciboForm')[0].reset();
                        // Recargamos la tabla
                        $('#tabla_cobranza').DataTable().ajax.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status ==
                            422) { // Cuando la respuesta de Laravel es 422, significa que hay un error de validación
                            let errors = jqXHR.responseJSON.errors;
                            let errorMessage = '';

                            // Iteramos a través de los errores y los agregamos a nuestro mensaje de error
                            Object.values(errors).forEach((errorFields) => {
                                errorFields.forEach((error) => {
                                    errorMessage += error + '\n';
                                });
                            });

                            Swal.fire(
                                'Error',
                                errorMessage,
                                'error'
                            )
                        } else {
                            // Si no es un error de validación, es posible que sea otro tipo de error
                            Swal.fire(
                                'Error',
                                'Hubo un problema al intentar actualizar. Por favor, intenta de nuevo más tarde.',
                                'error'
                            )
                        }
                    }
                });
            });

            $('#asignarReciboForm').on('submit', function(e) {
                e.preventDefault();
                var reciboId = $('#recibo_id').val();
                var agente_venta_id = $('#agente_venta_id').val();
                console.log(agente_venta_id)

                var route = '{{ route('cobranza.asignar', ':id') }}';
                var url = route.replace(':id', reciboId);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        recibo_id: reciboId,
                        vendedor_id: agente_venta_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#asignarReciboModal').modal('hide');
                        // Limpiamos el formulario
                        $('#asignarReciboForm').trigger('reset');
                        $('#tabla_cobranza').DataTable().ajax.reload();
                        // Sweetalert2
                        if (response.code == 200) {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: 'El recibo ha sido asignado correctamente.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });
                        } else {
                            Swal.fire({
                                title: '¡Error!',
                                text: 'Ha ocurrido un error al asignar el recibo.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Sweetalert2
                        Swal.fire({
                            title: '¡Error!',
                            text: 'Ha ocurrido un error al asignar el recibo.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });

            $(document).on('click', '.reasignar-recibo', function(e) {
                e.preventDefault();
                var reciboId = $(this).data('id');
                $('#reasignar_recibo_id').val(reciboId);
                $('#reasignarReciboModal').modal('show');
            });

            $('#reasignarReciboForm').on('submit', function(e) {
                e.preventDefault();
                var reciboId = $('#reasignar_recibo_id').val();
                var agente_venta_id = $('#reasignar_agente_venta_id').val();
                console.log(agente_venta_id)

                var route = '{{ route('cobranza.asignar', ':id') }}';
                var url = route.replace(':id', reciboId);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        recibo_id: reciboId,
                        vendedor_id: agente_venta_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#reasignarReciboModal').modal('hide');
                        // Limpiamos el formulario
                        $('#reasignarReciboForm').trigger('reset');
                        $('#tabla_cobranza').DataTable().ajax.reload();
                        // Sweetalert2
                        if (response.code == 200) {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: 'El recibo ha sido asignado correctamente.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });
                        } else {
                            Swal.fire({
                                title: '¡Error!',
                                text: 'Ha ocurrido un error al asignar el recibo.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Sweetalert2
                        Swal.fire({
                            title: '¡Error!',
                            text: 'Ha ocurrido un error al asignar el recibo.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
        });
    </script>
@endsection
