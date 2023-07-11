@extends('crm.layouts.app')
<style>
    .grid-search {
        grid-template-columns: repeat( auto-fit, minmax(200px, 1fr) );
        gap: 1.5rem;
        align-items: end;
    }


    @media (max-width: 768px) {
        .grid-search {
            grid-template-columns: repeat( 1, minmax(250px, 1fr) );
            gap: 1.5rem;
            align-items: end;
        }
    }

    #tabla_ventas {
        display: none;
    }

    div#tabla_ventas_wrapper {
        width: 100%;
    }
</style>
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">TELECOMUNICACIONES</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">TELECOMUNICACIONES</li>
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
                    @if (Auth::user()->hasAnyRole(['Administrador', 'Coordinador']))
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('ventas.formImportVentas') }}" class="btn btn-warning d-flex align-items-center justify-content-center gap-1 fs-5">
                                <i class="ri-file-excel-2-line"></i>
                                Importar CSV
                            </a>
                        </div>
                    @endif

                    <form method="GET">
                        {{-- Si el usuario es agente de ventas nueva no se deben mostrar los campos de fecha inicio, fecha fin, mes_bdd y anio_bdd--}}
                        <div class="d-grid mb-3 grid-search">
                            {{-----------------------------
                                COLOCAR ROL DE IZZI
                                 ---------------------------------}}
                            @if (!auth()->user()->hasAnyRole(['Agente de Ventas']))
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



                            @can('exportar-ventas')
                                {{-- Validamos si el usuario autenticado tiene el rol supervisor o coordinador --}}
                                <input type="hidden" name="exportar" value="1">
                                <!-- Aquí debes agregar inputs ocultos para mantener los criterios de búsqueda al exportar -->
                                <button type="submit" id="exportVentas" class="btn btn-success d-flex align-items-center justify-content-center gap-1 fs-5">
                                    <i class="ri-file-excel-2-line"></i>
                                    Exportar
                                </button>
                            @endcan

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
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="campana">Campaña:</label>
                                        <input type="text" name="campana" id="campana" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="lead">Lead:</label>
                                        <input type="text" name="lead" id="lead" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="cuenta">Cuenta:</label>
                                        <input type="text" name="cuenta" id="cuenta" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="pedido_movil">Pedido Móvil:</label>
                                        <input type="text" name="pedido_movil" id="pedido_movil" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="tipos_venta">Tipo de venta:</label>
                                        <select name="tipos_venta" id="tipos_venta" class="form-select">
                                            <option value="">-- Selecciona --</option>
                                            @foreach ($tipos_venta as $tipo_venta)
                                                <option value="{{ $tipo_venta }}">{{ $tipo_venta}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="tipos_linea">Tipo de línea:</label>
                                        <select name="tipos_linea" id="tipos_linea" class="form-select">
                                            <option value="">-- Selecciona --</option>
                                            @foreach ($tipos_linea as $tipo_linea)
                                                <option value="{{ $tipo_linea }}">{{ $tipo_linea}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="estados_tramitacionF">Estado tramitación fija:</label>
                                        <select name="estados_tramitacionF" id="estados_tramitacionF" class="form-select">
                                            <option value="">-- Selecciona --</option>
                                            @foreach ($estados_tramitacionF as $estado_tramitacionF)
                                                <option value="{{ $estado_tramitacionF }}">{{ $estado_tramitacionF}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="estados_tramitacionM">Estado tramitación móvil:</label>
                                        <select name="estados_tramitacionM" id="estados_tramitacionM" class="form-select">
                                            <option value="">-- Selecciona --</option>
                                            @foreach ($estados_tramitacionM as $estado_tramitacionM)
                                                <option value="{{ $estado_tramitacionM }}">{{ $estado_tramitacionM}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{---------------------------------------
                                    CAMBIAR A ROLES DE TELECOMUNICACIONES
                                    ------------------------------------------}}
                                @if (!auth()->user()->hasAnyRole(['Agente de Ventas', 'Agente Renovaciones']))

                                    <div class="col-12 col-md-3">
                                        <div class="mb-3">
                                            <label for="supervisor">Supervisor:</label>
                                            {{-- Mostramos un select con los usuarios que tienen rol superviso --}}
                                            <select name="supervisor" id="supervisor" class="form-select">
                                                <option value="">-- Selecciona --</option>
                                                @foreach ($supervisores as $supervisor)
                                                    <option value="{{ $supervisor->name }}">{{ $supervisor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="mb-3">
                                            <label for="agente">Agente:</label>
                                            {{-- Mostramos un select con los usuarios que tienen rol agente --}}
                                            <select name="agente" id="agente" class="form-select">
                                                <option value="">-- Selecciona --</option>
                                                @foreach ($agentes as $agente)
                                                    <option value="{{ $agente->id }}">{{ $agente->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                @endif
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="estados_siebel">Estado siebel:</label>
                                        <select name="estados_siebel" id="estados_siebel" class="form-select">
                                            <option value="">-- Selecciona --</option>
                                            @foreach ($estados_siebel as $estado_siebel)
                                                <option value="{{ $estado_siebel }}">{{ $estado_siebel}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="estados_velocity">Estado velocity:</label>
                                        <select name="estados_velocity" id="estados_velocity" class="form-select">
                                            <option value="">-- Selecciona --</option>
                                            @foreach ($estados_velocity as $estado_velocity)
                                                <option value="{{ $estado_velocity }}">{{ $estado_velocity}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="nombre_cliente">Nombre del cliente:</label>
                                        <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="telefono">Telefono:</label>
                                        <input type="text" name="telefono" id="telefono" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="celular">Celular:</label>
                                        <input type="text" name="celular" id="celular" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="correo">Correo:</label>
                                        <input type="text" name="correo" id="correo" class="form-control">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>

                    <hr>

                    <table class="table table-middle table-nowrap mb-0" id="tabla_ventas">
                        <thead>
                            <tr>
                                <th>ContactID</th>
                                <th>Fecha Venta</th>
                                <th>Fecha Reventa</th>
                                <th>Estado Izzi</th>
                                <th>Estado Siebel</th>
                                <th>Estado Móvil</th>
                                <th>Orden</th>
                                <th>Estado Orden</th>
                                <th>Cliente</th>
                                {{-- Validar perfil que podrá ver estos datos --}}
                                <th>Teléfono</th>
                                <th>Celular</th>
                                <th>Correo</th>
                                {{----------------------------------------------}}
                                <th>Tipo Linea</th>
                                <th>Campaña</th>
                                <th>Agente</th>
                                <th>Supervisor</th>
                                <th>Tramitador</th>
                                <th>Confirmador</th>
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

        // $('#tipo_venta').on('change', function() {
        //     var selectedValue = $(this).val();

        //     if (selectedValue === 'RENOVACION') {
        //         $('#mes_bdd').prop('disabled', false);
        //         $('#anio_bdd').prop('disabled', false);
        //     } else {
        //         $('#mes_bdd').prop('disabled', true);
        //         $('#anio_bdd').prop('disabled', true);
        //     }
        // });
        // Buscamos mediante los filtros
        $('body').on('click', '#buscarDatos', function(e){
            e.preventDefault();
            $('#tabla_ventas').DataTable().destroy();
            $('#tabla_ventas').show();

            // Ponemos el valor de los inputs en variables
            var rol = $('input[name="rol"]').val();
            var fecha_inicio = $('#fecha_inicio').val();
            var fecha_fin = $('#fecha_fin').val();

            var lead = $('#lead').val();
            var campana = $('#campana').val();
            var cuenta = $('#cuenta').val();
            var pedido_movil = $('#pedido_movil').val();

            var tipos_venta = $('#tipos_venta').val();
            var tipos_linea = $('#tipos_linea').val();
            var estados_tramitacionF = $('#estados_tramitacionF').val();
            var estados_tramitacionM = $('#estados_tramitacionM').val();

            var supervisor = $('#supervisor').val();
            var agente = $('#agente').val();
            var estados_siebel = $('#estados_siebel').val();
            var estados_velocity = $('#estados_velocity').val();

            var nombre_cliente = $('#nombre_cliente').val();
            var telefono = $('#telefono').val();
            var celular = $('#celular').val();
            var correo = $('#correo').val();

            $('#tabla_ventas').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bAutoWidth: false,
                ajax: {
                    url: "{{ route('izzi.index') }}",
                    type: "GET",
                    data: {
                        rol: rol,
                        fecha_inicio: fecha_inicio,
                        fecha_fin: fecha_fin,
                        lead: lead,
                        campana: campana,
                        cuenta: cuenta,
                        pedido_movil: pedido_movil,
                        tipos_venta: tipos_venta,
                        tipos_linea: tipos_linea,
                        estados_tramitacionF: estados_tramitacionF,
                        estados_tramitacionM: estados_tramitacionM,
                        supervisor: supervisor,
                        agente: agente,
                        estados_siebel: estados_siebel,
                        estados_velocity: estados_velocity,
                        nombre_cliente: nombre_cliente,
                        telefono: telefono,
                        celular: celular,
                        correo: correo
                    },
                },
                columns: [
                    {data: 'contact_id', name: 'contact_id'},
                    {data: 'fechaReventa', name: 'fechaReventa'},
                    {data: 'estadoIzzi', name: 'estadoIzzi'},
                    {data: 'cuenta', name: 'cuenta'},
                    ////////// Agregar en migración///////////
                    // {data: 'estadoSiebel', name: 'estadoSiebel'},
                    // {data: 'estadoMovil', name: 'estadoMovil'},
                    // {data: 'orden', name: 'orden'},
                    // {data: 'estadoOrden', name: 'estadoOrden'},
                    {data: 'nombre', name: 'nombre'},
                    {data: 'apellidoPaterno', name: 'apellidoPaterno'},
                    {data: 'apellidoMaterno', name: 'apellidoMaterno'},
                    {data: 'telefonoFijo', name: 'telefonoFijo'},
                    {data: 'celular', name: 'celular'},
                    {data: 'correo', name: 'correo'},
                    {data: 'tipoLinea', name: 'tipoLinea'},
                    {data: 'campana', name: 'campana'},
                    {data: 'loginIntranet', name: 'loginIntranet'},
                    {data: 'campana', name: 'campana'},
                    {data: 'loginOcm', name: 'loginOcm'},
                    {data: 'supervisor', name: 'supervisor'},
                    ////////// Agregar en migración///////////
                    // {data: 'tramitador', name: 'tramitador'},
                    // {data: 'confirmador', name: 'confirmador'},
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
                                return `$${data}`;
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
                            var fecha = `${data}`;
                            var fechaFormateada = new Date(fecha);
                            var anio = fechaFormateada.getFullYear();
                            var mes = ("0" + (fechaFormateada.getMonth() + 1)).slice(-2);
                            var dia = ("0" + fechaFormateada.getDate()).slice(-2);
                            var FPreventaFormat = anio + "-" + mes + "-" + dia;
                            // Hacemos un foreach a row.roles para obtener el nombre de cada rol
                            if(data == 'null' || data == 'NULL' || data == '' || data == null){
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin dato</span>`;
                            }else {
                                return `${FPreventaFormat}`;
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
                    },
                    {
                        target: 12,
                        render: function(data, type, row) {
                            var fecha = `${data}`;
                            if (!fecha || fecha === 'null' || fecha === 'NULL' || fecha === '') {
                                return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Sin dato</span>`;
                            } else {
                                var fechaObj = new Date(fecha);
                                if (isNaN(fechaObj.getTime())) {
                                    return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">Fecha inválida</span>`;
                                } else {
                                    var fechaFormateada = fechaObj.toISOString().split("T")[0];
                                    return `${fechaFormateada}`;
                                }
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
            var campana = $('#campana').val();
            var cuenta = $('#cuenta').val();
            var pedido_movil = $('#pedido_movil').val();

            var tipos_venta = $('#tipos_venta').val();
            var tipos_linea = $('#tipos_linea').val();
            var estados_tramitacionF = $('#estados_tramitacionF').val();
            var estados_tramitacionM = $('#estados_tramitacionM').val();

            var supervisor = $('#supervisor').val();
            var agente = $('#agente').val();
            var estados_siebel = $('#estados_siebel').val();
            var estados_velocity = $('#estados_velocity').val();

            var nombre_cliente = $('#nombre_cliente').val();
            var telefono = $('#telefono').val();
            var celular = $('#celular').val();
            var correo = $('#correo').val();

            // Validamos que solamente los campos fecha_inicio y fecha_fin no estén vacíos
            window.location.href = "{{ route('ventas.exportVentas') }}?rol="+rol+"&fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin+"&lead="+lead+"&campana="+campana+"&cuenta="+cuenta+"&pedido_movil="+pedido_movil+"&tipos_venta="+tipos_venta+"&tipos_linea="+tipos_linea+"&estados_tramitacionF="+estados_tramitacionF+"&estados_tramitacionM="+estados_tramitacionM+"&supervisor="+supervisor+"&agente="+agente+"&estados_siebel="+estados_siebel+"&estados_velocity="+estados_velocity+"&nombre_cliente="+nombre_cliente+"&telefono="+telefono+"&celular="+celular+"&correo="+correo;
        });
    });
</script>
@endsection
