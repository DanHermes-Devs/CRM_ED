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

    #tabla_logs {
        display: none;
    }

    div#tabla_logs_wrapper {
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
                    <h4 class="mb-sm-0">LOGS</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">LOGS</li>
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
                        <h1>Logs</h1>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>¡Éxito!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <form class="d-flex align-items-center gap-2">
                            <div class="mb-3">
                                <label for="tipo_recibos">Selecciona el Log a ver:</label>
                                <select name="tipo_recibos" id="tipo_recibos" class="form-select">
                                    <option selected>-- Seleccione el Log --</option>
                                    <option value="LogRecibos">Log Recibos</option>
                                </select>
                            </div>
                            <button type="submit" id="buscarDatos" class="btn btn-primary mt-2">Ver</button>
                        </form>

                        <table class="table table-middle table-nowrap mb-0" id="tabla_logs">
                            <thead>
                                <tr>
                                    <th>ID Recibo</th>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                    <th>Notas</th>
                                    <th>Fecha y Hora</th>
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
            var usuarioAutenticadoId = "{{ auth()->user()->id }}";
            $('body').on('click', '#buscarDatos', function(e) {
                e.preventDefault();
                $('#tabla_logs').DataTable().destroy();
                $('#tabla_logs').show();

                let tipoRecibos = $('#tipo_recibos').val();

                $('#tabla_logs').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    bAutoWidth: false,
                    ajax: {
                        url: "{{ route('logs.index') }}",
                        type: 'GET',
                        data: {
                            tipo_recibos: tipoRecibos
                        }
                    },
                    columns: [{
                            data: 'receipt_id',
                            name: 'receipt_id'
                        },
                        {
                            data: 'user.usuario',
                            name: 'user.usuario'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                        {
                            data: 'notes',
                            name: 'notes'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                    ],
                    columnDefs: [
                        {
                            targets: 4,
                            render: function(data, type, row) {
                                console.log(row);
                                const date = new Date(row.created_at);
                                const formattedDate = `${date.toLocaleDateString()} ${date.toLocaleTimeString()}`;
                                return formattedDate;
                            }
                        }
                    ],
                    language: idiomaDataTable
                });
            });
        });
    </script>
@endsection
