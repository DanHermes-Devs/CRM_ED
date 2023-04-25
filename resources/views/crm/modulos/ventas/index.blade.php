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
                <h4 class="mb-sm-0">VENTAS</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">VENTAS</li>
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
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('ventas.formImportVentas') }}" class="btn btn-warning d-flex align-items-center justify-content-center gap-1 fs-5">
                            <i class="ri-file-excel-2-line"></i>
                            Importar CSV
                        </a>
                    </div>
                    <form method="GET">
                        {{-- Si el usuario es agente de ventas nueva no se deben mostrar los campos de fecha inicio, fecha fin, mes_bdd y anio_bdd--}}
                        <div class="d-grid mb-3 grid-search">
                            @if (!auth()->user()->hasRole('Agente Ventas Nuevas'))
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

                        <div class="collapse show" id="filterCollapse">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="lead">Lead:</label>
                                        <input type="text" name="lead" id="lead" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="numero_serie">Número de serie:</label>
                                        <input type="text" name="numero_serie" id="numero_serie" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="numero_poliza">Número de póliza:</label>
                                        <input type="text" name="numero_poliza" id="numero_poliza" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" name="telefono" id="telefono" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="tipo_venta">Tipo de venta:</label>
                                        <select name="tipo_venta" id="tipo_venta" class="form-select">
                                            <option value="">-- Selecciona --</option>
                                            <option value="VENTA NUEVA">Venta nueva</option>
                                            <option value="RENOVACION">Renovaciones</option>
                                            <option value="POSIBLE DUPLICIDAD">Posible Duplicidad</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="nombre_cliente">Nombre de cliente:</label>
                                        <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4">
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
                                <div class="col-12 col-md-4">
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
                            </div>
                            @if (!auth()->user()->hasRole('Agente Ventas Nuevas'))
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="mb-3">
                                            <label for="mes_bdd">Mes:</label>
                                            <select name="mes_bdd" id="mes_bdd" class="form-select" disabled>
                                                <option value="">-- Selecciona --</option>
                                                <option value="ENERO">Enero</option>
                                                <option value="FEBRERO">Febrero</option>
                                                <option value="MARZO">Marzo</option>
                                                <option value="ABRIL">Abril</option>
                                                <option value="MAYO">Mayo</option>
                                                <option value="JUNIO">Junio</option>
                                                <option value="JULIO">Julio</option>
                                                <option value="AGOSTO">Agosto</option>
                                                <option value="SEPTIEMBRE">Septiembre</option>
                                                <option value="OCTUBRE">Octubre</option>
                                                <option value="NOVIEMBRE">Noviembre</option>
                                                <option value="DICIEMBRE">Diciembre</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="mb-3">
                                            <label for="anio_bdd">Año:</label>
                                            <input type="number" name="anio_bdd" id="anio_bdd" min="1900" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                
                    <hr>
                
                    <table class="table table-middle table-nowrap mb-0" id="tabla_ventas">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Tipo Venta</th>
                                <th>Fecha Preventa</th>
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
        $('#tipo_venta').on('change', function() {
            var selectedValue = $(this).val();

            if (selectedValue === 'RENOVACION') {
                $('#mes_bdd').prop('disabled', false);
                $('#anio_bdd').prop('disabled', false);
            } else {
                $('#mes_bdd').prop('disabled', true);
                $('#anio_bdd').prop('disabled', true);
            }
        });
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
            var numero_serie = $('#numero_serie').val();
            var numero_poliza = $('#numero_poliza').val();
            var telefono = $('#telefono').val();
            var tipo_venta = $('#tipo_venta').val();
            var nombre_cliente = $('#nombre_cliente').val();
            var supervisor = $('#supervisor').val();
            var agente = $('#agente').val();
            var mes_bdd = $('#mes_bdd').val();
            var anio_bdd = $('#anio_bdd').val();

            $('#tabla_ventas').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bAutoWidth: false,
                ajax: {
                    url: "{{ route('ventas.index') }}",
                    type: "GET",
                    data: {
                        rol: rol,
                        fecha_inicio: fecha_inicio,
                        fecha_fin: fecha_fin,
                        lead: lead,
                        numero_serie: numero_serie,
                        numero_poliza: numero_poliza,
                        telefono: telefono,
                        tipo_venta: tipo_venta,
                        nombre_cliente: nombre_cliente,
                        supervisor: supervisor,
                        agente: agente,
                        mes_bdd: mes_bdd,
                        anio_bdd: anio_bdd,
                    },
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'Nombre', name: 'Nombre'},
                    {data: 'tVenta', name: 'tVenta'},
                    {data: 'Fpreventa', name: 'Fpreventa'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                language: idiomaDataTable
            });

            // Validamos que solamente los campos fecha_inicio y fecha_fin no estén vacíos
            // if(fecha_inicio != '' && fecha_fin != '') {
            //     // Mandamos los datos a la ruta ventas.index para posteriormente cargar el datatable con la informacion que devuelva este mismo
                
            // }else{
            //     // Mostramos el mensaje de error con un sweetalert
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Oops...',
            //         text: 'No puedes filtrar por fechas vacias',
            //     });
            // }
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
            var tipo_venta = $('#tipo_venta').val();
            var nombre_cliente = $('#nombre_cliente').val();
            var mes_bdd = $('#mes_bdd').val();
            var anio_bdd = $('#anio_bdd').val();

            // Validamos que solamente los campos fecha_inicio y fecha_fin no estén vacíos
            window.location.href = "{{ route('ventas.exportVentas') }}?rol="+rol+"&fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin+"&lead="+lead+"&numero_serie="+numero_serie+"&numero_poliza="+numero_poliza+"&telefono="+telefono+"&tipo_venta="+tipo_venta+"&nombre_cliente="+nombre_cliente+"&mes_bdd="+mes_bdd+"&anio_bdd="+anio_bdd;
        });
    });
</script>
@endsection