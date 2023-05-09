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
        margin-right: 1rem!important;
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
                        <form class="d-flex align-items-center gap-2">
                            <div class="mb-3">
                                <label for="tipo_recibos">Tipo de recibos:</label>
                                <select name="tipo_recibos" id="tipo_recibos" class="form-select">
                                    <option value="TODOS" {{ $tipoRecibos === 'TODOS' ? 'selected' : '' }}>Todos los
                                        recibos</option>
                                    <option value="MIS_RECIBOS" {{ $tipoRecibos === 'MIS_RECIBOS' ? 'selected' : '' }}>Mis
                                        recibos</option>
                                </select>
                            </div>
                            <button type="submit" id="buscarDatos" class="btn btn-primary mt-2">Filtrar</button>
                        </form>

                        <table class="table table-middle table-nowrap mb-0" id="tabla_cobranza">
                            <thead>
                                <tr>
                                    <th>Lead</th>
                                    <th>Nombre Cliente</th>
                                    <th>Poliza</th>
                                    <th>TelCelular</th>
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
    <div class="modal fade" id="reasignarReciboModal" tabindex="-1" role="dialog" aria-labelledby="reasignarReciboModalLabel"
        aria-hidden="true">
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

    <script>
        $(document).ready(function() {
            var usuarioAutenticadoId = "{{ auth()->user()->id }}";
            $('body').on('click', '#buscarDatos', function(e) {
                e.preventDefault();
                $('#tabla_cobranza').DataTable().destroy();
                $('#tabla_cobranza').show();

                let tipoRecibos = $('#tipo_recibos').val();

                $('#tabla_cobranza').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    bAutoWidth: false,
                    ajax: {
                        url: "{{ route('cobranza.index') }}",
                        type: 'GET',
                        data: {
                            tipo_recibos: tipoRecibos
                        }
                    },
                    columns: [{
                            data: 'contactId',
                            name: 'contactId'
                        },
                        {
                            data: 'venta.NombreDeCliente',
                            name: 'NombreDeCliente'
                        },
                        {
                            data: 'venta.nPoliza',
                            name: 'nPoliza'
                        },
                        {
                            data: 'venta.TelCelular',
                            name: 'TelCelular'
                        },
                        {
                            data: 'id',
                            name: 'id'
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
                        if(response.code == 200) {
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
                        if(response.code == 200) {
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
