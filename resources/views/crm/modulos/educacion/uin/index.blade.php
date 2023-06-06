@extends('crm.layouts.app')
<style>
    .grid-search {grid-template-columns: repeat( auto-fit, minmax(200px, 1fr) );gap: 1.5rem;align-items: end;}
    @media (max-width: 768px) { .grid-search { grid-template-columns: repeat( 1, minmax(250px, 1fr) );gap: 1.5rem;align-items: end;} }
    #tabla_education { display: none;}
    div#tabla_education_wrapper { width: 100%;}
</style>
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">VENTAS UIN</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">VENTAS UIN</li>
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

                    <form method="GET">
                        {{-- Si el usuario es agente de ventas nueva no se deben mostrar los campos de fecha inicio, fecha fin, mes_bdd y anio_bdd--}}
                        <div class="d-grid mb-3 grid-search">
                            @if (!auth()->user()->hasAnyRole(['Agente de Ventas', 'Agente Renovaciones']))
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha inicio:</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha fin:</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                                </div>
                            @endif

                            <button type="button" id="buscarDatos" class="btn btn-primary d-flex align-items-center justify-content-center gap-1 fs-5">
                                <i class="ri-search-line"></i>
                                Buscar
                            </button>

                            {{-- Capturamos el rol del usuario conectado --}}
                            <input type="hidden" name="rol" value="{{ auth()->user()->roles->first()->name }}">
                            {{-- Capturamos el usuario autenticado --}}
                            <input type="hidden" name="user" id="user" value="{{ auth()->user()->id }}">

                            <a class="btn btn-info d-flex align-items-center justify-content-center gap-1 fs-5" data-bs-toggle="collapse" href="#filterCollapse" role="button" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="ri-filter-3-line"></i>
                                Filtros
                            </a>
                        </div>

                        <div id="alert" class="alert alert-info d-none" role="alert">
                            <div class="d-flex gap-3 align-items-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                El proceso de exportacion puede demorar unos minutos. Por favor, espera.
                            </div>
                        </div>

                        <div class="collapse show" id="filterCollapse">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="contact_id">Lead:</label>
                                        <input type="text" name="contact_id" id="contact_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="client_name">Nombre de cliente:</label>
                                        <input type="text" name="client_name" id="client_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="client_landline">Teléfono Fijo:</label>
                                        <input type="text" name="client_landline" id="client_landline" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="client_celphone">Teléfono Celular:</label>
                                        <input type="text" name="client_celphone" id="client_celphone" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="codification">Estatus:</label>
                                        <select name="codification" id="codification" class="form-select">
                                            <option value="">-- Selecciona --</option>
                                            @if (auth()->user()->hasAnyRole(['Agente de Ventas','Administrador', 'Coordinador']))
                                                <option value="COTIZACION">COTIZACIÓN</option>
                                            @endif
                                            @if (auth()->user()->hasAnyRole(['Agente Renovaciones','Administrador', 'Coordinador']))
                                                <option value="COBRADO">COBRADO</option>
                                            @endif
                                            <option value="ALUMNO">ALUMNO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <table class="table table-middle table-nowrap mb-0" id="tabla_education">
                        <thead>
                            <tr>
                                <th>Lead</th><!-- contact_id -->
                                <th>Codificación</th>
                                <th>Campaña</th>
                                <th>Nombre Cliente</th>
                                <th>Modalidad</th>
                                <th>Programa</th>
                                <th>Especialidad</th>
                                <th>Fecha Preventa</th>
                                <th>Agente</th>
                                <th>Documentos</th>
                                <th>Cuenta UIN</th>
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
        $('body').on('click', '#exportVentas', function(){
            $('#loader').removeClass('d-none');
            $('#alert').removeClass('d-none');

            // Bloqueamos el boton de guardar para evitar que se haga doble click
            $(this).attr('disabled', true);
        });
        // Buscamos mediante los filtros
        $('body').on('click', '#buscarDatos', function(e){
            e.preventDefault();
            $('#tabla_education').DataTable().destroy();
            $('#tabla_education').show();

            // Ponemos el valor de los inputs en variables
            var rol = $('input[name="rol"]').val();
            var fecha_inicio = $('#fecha_inicio').val();
            var fecha_fin = $('#fecha_fin').val();
            var contact_id = $('#contact_id').val();
            var client_name = $('#client_name').val();
            var client_landline = $('#client_landline').val();
            var client_celphone = $('#client_celphone').val();
            var codification = $('#codification').val();
            var user = $('#user').val();
            $('#tabla_education').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bAutoWidth: true,
                ajax: {
                    url: "{{ route('educacion-uin.index') }}",
                    type: "GET",
                    data: {
                        rol: rol,
                        fecha_inicio: fecha_inicio,
                        fecha_fin: fecha_fin,
                        contact_id: contact_id,
                        client_name: client_name,
                        client_landline: client_landline,
                        client_celphone: client_celphone,
                        codification: codification,
                        user: user,
                    },
                },

                columns: [
                    {data: 'contact_id', name: 'contact_id'},
                    {data: 'codification', name: 'codification'},
                    {data: 'campana', name: 'campana'},
                    {data: 'client_name', name: 'client_name'},
                    {data: 'client_modality', name: 'client_modality'},
                    {data: 'client_program', name: 'client_program'},
                    {data: 'client_specialty', name: 'client_specialty'},
                    {data: 'fp_venta', name: 'fp_venta'},
                    {data: 'agent_OCM', name: 'agent_OCM'},
                    {data: 'documents_portal', name: 'documents_portal'},
                    {data: 'account_UIN', name: 'account_UIN'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                columnDefs: [
                    {
                        target: 0,
                        render: function(data, type, row) {
                            // Hacemos un foreach a row.roles para obtener el nombre de cada rol
                            if(data == 'null' || data == 'NULL' || data == '' || data == null){
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin dato</span>`;
                            }else {
                                return `${data}`;
                            }
                        }
                    },
                    {
                        target: 1,
                        render: function(data, type, row) {
                            // Hacemos un foreach a row.roles para obtener el nombre de cada rol
                            if(data == 'null' || data == 'NULL' || data == '' || data == null){
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin dato</span>`;
                            }else {
                                return `${data}`;
                            }
                        }
                    },
                    {
                        target: 2,
                        render: function(data, type, row) {
                            // Hacemos un foreach a row.roles para obtener el nombre de cada rol
                            if(data == 'null' || data == 'NULL' || data == '' || data == null){
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin dato</span>`;
                            }else {
                                return `${data}`;
                            }
                        }
                    },
                    {
                        target: 3,
                        render: function(data, type, row) {
                            // Hacemos un foreach a row.roles para obtener el nombre de cada rol
                            if(data == 'null' || data == 'NULL' || data == '' || data == null){
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin dato</span>`;
                            }else {
                                return `${data}`;
                            }
                        }
                    },
                    {
                        target: 4,
                        render: function(data, type, row) {
                            // Hacemos un foreach a row.roles para obtener el nombre de cada rol
                            if(data == 'null' || data == 'NULL' || data == '' || data == null){
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin dato</span>`;
                            }else {
                                return `${data}`;
                            }
                        }
                    },
                    {
                        target: 5,
                        render: function(data, type, row) {
                            // Hacemos un foreach a row.roles para obtener el nombre de cada rol
                            if(data == 'null' || data == 'NULL' || data == '' || data == null){
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin dato</span>`;
                            }else {
                                return `${data}`;
                            }
                        }
                    },
                    {
                        target: 6,
                        render: function(data, type, row) {
                            // Hacemos un foreach a row.roles para obtener el nombre de cada rol
                            if(data == 'null' || data == 'NULL' || data == '' || data == null){
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin dato</span>`;
                            }else {
                                return `${data}`;
                            }
                        }
                    },
                    {
                        target: 7,
                        render: function(data, type, row) {
                            // Hacemos un foreach a row.roles para obtener el nombre de cada rol
                            if(data == 'null' || data == 'NULL' || data == '' || data == null){
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin dato</span>`;
                            }else {
                                return `${data}`;
                            }
                        }
                    },
                    {
                        target: 8,
                        render: function(data, type, row) {
                            // Hacemos un foreach a row.roles para obtener el nombre de cada rol
                            if(data == 'null' || data == 'NULL' || data == '' || data == null){
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin dato</span>`;
                            }else {
                                return `${data}`;
                            }
                        }
                    },
                    {
                        target: 9,
                        render: function(data, type, row) {
                            // Hacemos un foreach a row.roles para obtener el nombre de cada rol
                            if(data == 'null' || data == 'NULL' || data == '' || data == null){
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin dato</span>`;
                            }else {
                                return `${data}`;
                            }
                        }
                    },
                    {
                        target: 9,
                        render: function(data, type, row) {
                            // Hacemos un foreach a row.roles para obtener el nombre de cada rol
                            if(data == 'null' || data == 'NULL' || data == '' || data == null){
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin dato</span>`;
                            }else {
                                return `${data}`;
                            }
                        }
                    },
                    {
                        target: 10,
                        render: function(data, type, row) {
                            // Hacemos un foreach a row.roles para obtener el nombre de cada rol
                            if(data == 'null' || data == 'NULL' || data == '' || data == null){
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin dato</span>`;
                            }else {
                                return `${data}`;
                            }
                        }
                    }

                ],
                language: idiomaDataTable
            });
        });

        // Boton exportar ventas
        $('body').on('click', '#exportVentas', function(e){
            e.preventDefault();

            // Ponemos el valor de los inputs en variables
            var rol = $('input[name="rol"]').val();
            var fecha_inicio = $('#fecha_inicio').val();
            var fecha_fin = $('#fecha_fin').val();
            var lead = $('#lead').val();
            var numero_serie = $('#numero_serie').val();
            var numero_poliza = $('#numero_poliza').val();
            var telefono = $('#telefono').val();
            var codification = $('#codification').val();
            var nombre_cliente = $('#nombre_cliente').val();
            var mes_bdd = $('#mes_bdd').val();
            var anio_bdd = $('#anio_bdd').val();
        });
    });
</script>
@endsection
