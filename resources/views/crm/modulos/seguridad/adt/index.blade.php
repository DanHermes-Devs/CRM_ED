@extends('crm.layouts.app')
<style>
    .grid-search {grid-template-columns: repeat( auto-fit, minmax(200px, 1fr) );gap: 1.5rem;align-items: end;}
    @media (max-width: 768px) { .grid-search { grid-template-columns: repeat( 1, minmax(250px, 1fr) );gap: 1.5rem;align-items: end;} }
    #tabla_seguridadAdt { display: none;}
    div#tabla_seguridadAdt_wrapper { width: 100%;}
</style>
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">VENTAS ADT</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">VENTAS ADT</li>
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
                        </div>

                        <div id="alert" class="alert alert-info d-none" role="alert">
                            <div class="d-flex gap-3 align-items-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                El proceso de exportacion puede demorar unos minutos. Por favor, espera.
                            </div>
                        </div>
                    </form>

                    <hr>

                    <table class="table table-middle table-nowrap mb-0" id="tabla_seguridadAdt">
                        <thead>
                            <tr>
                                <th>Lead</th><!-- contact_id -->
                                <th>Fecha Venta</th>
                                <th>Fecha Ultimo Estado</th>
                                <th>Nombre Agente</th>
                                <th>Cuenta</th>
                                <th>Cliente</th>
                                <th>RFC</th>
                                <th>Teléfono</th>
                                <th>Celular</th>
                                <th>Correo</th>
                                <th>Estado</th>
                                <th>Municipio</th>
                                <th>Producto</th>
                                <th>Forma de Pago</th>
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
            $('#tabla_seguridadAdt').DataTable().destroy();
            $('#tabla_seguridadAdt').show();

            // Ponemos el valor de los inputs en variables
            var rol = $('input[name="rol"]').val();
            var fecha_inicio = $('#fecha_inicio').val();
            var fecha_fin = $('#fecha_fin').val();
            var contact_id = $('#contact_id').val();
            var cliente_nombre = $('#cliente_nombre').val();
            var cliente_telefono = $('#cliente_telefono').val();
            var cliente_celular = $('#cliente_celular').val();
            var user = $('#user').val();
            $('#tabla_seguridadAdt').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bAutoWidth: true,
                ajax: {
                    url: "{{ route('seguridad-adt.index') }}",
                    type: "GET",
                    data: {
                        rol: rol,
                        fecha_inicio: fecha_inicio,
                        fecha_fin: fecha_fin,
                        contact_id: contact_id,
                        cliente_nombre: cliente_nombre,
                        cliente_telefono: cliente_telefono,
                        cliente_celular: cliente_celular,
                        user: user,
                    },
                },

                columns: [
                    {data: 'contact_id', name: 'contact_id'},
                    {data: 'fecha_venta', name: 'fecha_venta'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'nombre_agente', name: 'nombre_agente'},
                    {data: 'cuenta_adt', name: 'cuenta_adt'},
                    {data: 'cliente_nombre', name: 'cliente_nombre'},
                    {data: 'cliente_rfc', name: 'cliente_rfc'},
                    {data: 'cliente_telefono', name: 'cliente_telefono'},
                    {data: 'cliente_celular', name: 'cliente_celular'},
                    {data: 'cliente_correo', name: 'cliente_correo'},
                    {data: 'cliente_estado', name: 'cliente_estado'},
                    {data: 'cliente_municipio', name: 'cliente_municipio'},
                    {data: 'cliente_producto', name: 'cliente_producto'},
                    {data: 'forma_pago', name: 'forma_pago'},
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
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin fecha</span>`;
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
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin fecha</span>`;
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
                        target: 10,
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
                        target: 11,
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
