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
                    <h4 class="mb-sm-0">DASHBOARD</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">DASHBOARD</li>
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
                            <div class="form-group">
                                <label for="agente">Campaña:</label>
                                {{-- Mostramos un select con los usuarios que tienen rol agente --}}
                                <select name="agente" id="agente" class="form-select">
                                    <option value="">-- Selecciona --</option>
                                    <option value="">Universidad Insurgentes</option>
                                    <option value="">Qualitas</option>
                                    <option value="">Axa</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha inicio:</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="fecha_fin">Fecha fin:</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="agente">Tipo:</label>
                                {{-- Mostramos un select con los usuarios que tienen rol agente --}}
                                <select name="agente" id="agente" class="form-select">
                                    <option value="">-- Selecciona --</option>
                                    <option value="llamadasFb">Facebook</option>
                                    <option value="llamadasGoogle">Google</option>
                                </select>
                            </div>

<<<<<<< Updated upstream

        @foreach ($data as $row)
            <p>{{ $row['leadsFb'] }}</p>
            <p>{{ $row['leadsGoogle'] }}</p>
        @endforeach


=======
                            <button type="button" id="buscarDatos" class="btn btn-primary d-flex align-items-center justify-content-center gap-1 fs-5">
                                <i class="ri-search-line"></i>
                                Buscar
                            </button>

                            {{-- Capturamos el rol del usuario conectado --}}
                            <input type="hidden" name="rol" value="{{ auth()->user()->roles->first()->name }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
>>>>>>> Stashed changes
        <div class="row">
            <div class="col-xl-12">
                <div class="card crm-widget">
                    <div class="card-body p-0">
                        <div class="row row-cols-xxl-4 row-cols-md-3 row-cols-1 g-0">
                            <div class="col">
                                <div class="py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">LEADS <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-facebook-circle-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0"><span class="counter-value" data-target="<?= (!empty($conteo[0]['leadsFb'])) ? $conteo[0]['leadsFb']: ''; ?>">0</span></h2>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-google-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0"><span class="counter-value" data-target="<?= (!empty($conteo[0]['leadsGoogle'])) ? $conteo[0]['leadsGoogle']: ''; ?>">0</span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            <div class="col">
                                <div class="mt-3 mt-md-0 py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">LLAMADAS <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-facebook-circle-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0"><span class="counter-value" data-target="<?= (!empty($llamadas[0]['TOTAL'])) ? $llamadas[0]['TOTAL']: ''; ?>">0</span></h2>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-google-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3 ml-3">
                                                <h2 class="mb-0"><span class="counter-value" data-target="<?= (!empty($llamadas[1]['TOTAL'])) ? $llamadas[1]['TOTAL']: ''; ?>">0</span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            <div class="col">
                                <div class="mt-3 mt-md-0 py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Ventas Realizadas 
                                    <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-facebook-circle-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0"><span class="counter-value" data-target="{{ optional($ventas[0])['Total'] ? $ventas[0]['Total'] : '' }}">0</span></h2>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-google-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3 ml-3">
                                                <h2 class="mb-0"><span class="counter-value" data-target="{{ optional($ventas[1])['Total'] ? $ventas[1]['Total'] : '' }}">0</span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            <div class="col">
                                <div class="mt-3 mt-md-0 py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Ratio 
                                    <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-facebook-circle-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                            <?php 
                                                $l = $conteo[0]['leadsFb'];
                                                $v = empty($ventas[0]['Total'])? 0 : $ventas[0]['Total'];
                                                $ratioFb = $v > 0 ? round($v / $l, 2) : 0;
                                            ?>
                                                <h2 class="mb-0"><span class="counter-value" data-target="<?= (!empty($ratioFb)) ? $ratioFb: ''; ?>">0</span>% </h2>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-google-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3 ml-3">
                                            <?php 
                                                $l = $conteo[0]['leadsGoogle'];
                                                $v = empty($ventas[1]['Total'])? 0 : $ventas[1]['Total'];
                                                $ratioGo = $v > 0 ? round($v / $l, 2) : 0;
                                            ?>
                                                <h2 class="mb-0"><span class="counter-value" data-target="<?= (!empty($ratioGo)) ? $ratioGo: ''; ?>">0</span>% </h2>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            
                            <!-- <div class="col">
                                <div class="mt-3 mt-lg-0 py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Annual Deals <i
                                            class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-service-line display-6 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0"><span class="counter-value" data-target="2659">0</span></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>//end col -->
                        </div><!-- end row -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->

        

        <div class="row">
            <div class="col-xl-7">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Llamadas por agente</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="text-muted">
                                        <th scope="col" style="width: 20%;">Usuario</th>
                                        <th scope="col" >Nombre del agente</th>
                                        <th scope="col">llamadas atendidas</th>
                                        <th scope="col" style="width: 16%;">No Ventas</th>
                                        <th scope="col" style="width: 16%;">Ventas Facebook</th>
                                        <th scope="col" style="width: 16%;">Ventas Google</th>
                                        <th scope="col" style="width: 12%;">Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @foreach ($lYvAYc as $item)
                                    <tr>
                                        <td>{{ $item['agent'] }}</td>
                                        <td>{{ $item['nombre'] }}</td>
                                        <td>{{ $item['tipoLlamadas'] == 'LlamadasFb' ? 'Facebook' : 'Google' }}</td>
                                        <td>{{ $item['llamadasNoVenta'] }}</td>
                                        <td>{{ $item['llamadasVentaFb'] }}</td>
                                        <td>{{ $item['llamadasVentaGo'] }}</td>
                                        <td>{{ $item['Total'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Últimos resultados de contactación</h4>
                    </div><!-- end card header -->

                    <div class="card-body p-0">
                        <div data-simplebar style="max-height: 219px;">
                            <ul class="list-group list-group-flush border-dashed px-3">
                                @foreach ($tipificacion as $item)
                                <li class="list-group-item ps-0">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1">
                                            <label class="form-check-label mb-0 ps-2" for="task_one">{{ $item['resultadoUC']}}</label>
                                        </div>
                                        <div class="flex-shrink-0 ms-2">
                                            <p class="text-muted fs-12 mb-0">{{ $item['Total']}}</p>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul><!-- end ul -->
                        </div>
                        <!-- <div class="p-3 pt-2">
                            <a href="javascript:void(0);" class="text-muted text-decoration-underline">Show
                                more...</a>
                        </div> -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->


        <div class="row">
            <div class="col-xxl-3 col-md-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Leads</h4>
                        <div class="flex-shrink-0">
                            <div class="dropdown card-header-dropdown">
                                
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div id="sales-forecast-chart" data-colors='["--vz-primary", "--vz-success", "--vz-warning"]'
                            class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Deal Type</h4>
                        <div class="flex-shrink-0">
                            <div class="dropdown card-header-dropdown">
                                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <span class="fw-semibold text-uppercase fs-12">Sort by: </span><span
                                        class="text-muted">Monthly<i class="mdi mdi-chevron-down ms-1"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <a class="dropdown-item" href="#">Weekly</a>
                                    <a class="dropdown-item" href="#">Monthly</a>
                                    <a class="dropdown-item" href="#">Yearly</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div id="deal-type-charts" data-colors='["--vz-warning", "--vz-danger", "--vz-success"]'
                            class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-6">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Balance Overview</h4>
                        <div class="flex-shrink-0">
                            <div class="dropdown card-header-dropdown">
                                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <span class="fw-semibold text-uppercase fs-12">Sort by: </span><span
                                        class="text-muted">Current Year<i class="mdi mdi-chevron-down ms-1"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <a class="dropdown-item" href="#">Last Week</a>
                                    <a class="dropdown-item" href="#">Last Month</a>
                                    <a class="dropdown-item" href="#">Current Year</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0">
                        <ul class="list-inline main-chart text-center mb-0">
                            <li class="list-inline-item chart-border-left me-0 border-0">
                                <h4 class="text-primary">$584k <span
                                        class="text-muted d-inline-block fs-13 align-middle ms-2">Revenue</span>
                                </h4>
                            </li>
                            <li class="list-inline-item chart-border-left me-0">
                                <h4>$497k<span class="text-muted d-inline-block fs-13 align-middle ms-2">Expenses</span>
                                </h4>
                            </li>
                            <li class="list-inline-item chart-border-left me-0">
                                <h4><span data-plugin="counterup">3.6</span>%<span
                                        class="text-muted d-inline-block fs-13 align-middle ms-2">Profit
                                        Ratio</span></h4>
                            </li>
                        </ul>

                        <div id="revenue-expenses-charts" data-colors='["--vz-success", "--vz-danger"]'
                            class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
            </div><!-- end row -->

        <!-- <div class="row">
            <div class="col-xxl-5">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Upcoming Activities</h4>
                        <div class="flex-shrink-0">
                            <div class="dropdown card-header-dropdown">
                                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <span class="text-muted fs-18"><i class="mdi mdi-dots-vertical"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <a class="dropdown-item" href="#">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <ul class="list-group list-group-flush border-dashed">
                            <li class="list-group-item ps-0">
                                <div class="row align-items-center g-3">
                                    <div class="col-auto">
                                        <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3">
                                            <div class="text-center">
                                                <h5 class="mb-0">25</h5>
                                                <div class="text-muted">Tue</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="text-muted mt-0 mb-1 fs-13">12:00am - 03:30pm</h5>
                                        <a href="#" class="text-reset fs-14 mb-0">Meeting for campaign with
                                            sales team</a>
                                    </div>
                                    <div class="col-sm-auto">
                                        <div class="avatar-group">
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Stine Nielsen">
                                                    <img src="assets/images/users/avatar-1.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Jansh Brown">
                                                    <img src="assets/images/users/avatar-2.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Dan Gibson">
                                                    <img src="assets/images/users/avatar-3.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);">
                                                    <div class="avatar-xxs">
                                                        <span class="avatar-title rounded-circle bg-info text-white">
                                                            5
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </li>
                            <li class="list-group-item ps-0">
                                <div class="row align-items-center g-3">
                                    <div class="col-auto">
                                        <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3">
                                            <div class="text-center">
                                                <h5 class="mb-0">20</h5>
                                                <div class="text-muted">Wed</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="text-muted mt-0 mb-1 fs-13">02:00pm - 03:45pm</h5>
                                        <a href="#" class="text-reset fs-14 mb-0">Adding a new event with
                                            attachments</a>
                                    </div>
                                    <div class="col-sm-auto">
                                        <div class="avatar-group">
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Frida Bang">
                                                    <img src="assets/images/users/avatar-4.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Malou Silva">
                                                    <img src="assets/images/users/avatar-5.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Simon Schmidt">
                                                    <img src="assets/images/users/avatar-6.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Tosh Jessen">
                                                    <img src="assets/images/users/avatar-7.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);">
                                                    <div class="avatar-xxs">
                                                        <span class="avatar-title rounded-circle bg-success text-white">
                                                            3
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </li>
                            <li class="list-group-item ps-0">
                                <div class="row align-items-center g-3">
                                    <div class="col-auto">
                                        <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3">
                                            <div class="text-center">
                                                <h5 class="mb-0">17</h5>
                                                <div class="text-muted">Wed</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="text-muted mt-0 mb-1 fs-13">04:30pm - 07:15pm</h5>
                                        <a href="#" class="text-reset fs-14 mb-0">Create new project
                                            Bundling Product</a>
                                    </div>
                                    <div class="col-sm-auto">
                                        <div class="avatar-group">
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Nina Schmidt">
                                                    <img src="assets/images/users/avatar-8.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Stine Nielsen">
                                                    <img src="assets/images/users/avatar-1.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Jansh Brown">
                                                    <img src="assets/images/users/avatar-2.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);">
                                                    <div class="avatar-xxs">
                                                        <span class="avatar-title rounded-circle bg-primary text-white">
                                                            4
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </li>
                            <li class="list-group-item ps-0">
                                <div class="row align-items-center g-3">
                                    <div class="col-auto">
                                        <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3">
                                            <div class="text-center">
                                                <h5 class="mb-0">12</h5>
                                                <div class="text-muted">Tue</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="text-muted mt-0 mb-1 fs-13">10:30am - 01:15pm</h5>
                                        <a href="#" class="text-reset fs-14 mb-0">Weekly closed sales won
                                            checking with sales team</a>
                                    </div>
                                    <div class="col-sm-auto">
                                        <div class="avatar-group">
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Stine Nielsen">
                                                    <img src="assets/images/users/avatar-1.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Jansh Brown">
                                                    <img src="assets/images/users/avatar-5.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Dan Gibson">
                                                    <img src="assets/images/users/avatar-2.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);">
                                                    <div class="avatar-xxs">
                                                        <span class="avatar-title rounded-circle bg-warning text-white">
                                                            9
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </li>
                        </ul>
                        <div class="align-items-center mt-2 row g-3 text-center text-sm-start">
                            <div class="col-sm">
                                <div class="text-muted">Showing<span class="fw-semibold">4</span> of <span
                                        class="fw-semibold">125</span> Results
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <ul
                                    class="pagination pagination-separated pagination-sm justify-content-center justify-content-sm-start mb-0">
                                    <li class="page-item disabled">
                                        <a href="#" class="page-link">←</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">1</a>
                                    </li>
                                    <li class="page-item active">
                                        <a href="#" class="page-link">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">→</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-7">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Closing Deals</h4>
                        <div class="flex-shrink-0">
                            <select class="form-select form-select-sm" aria-label=".form-select-sm example">
                                <option selected="">Closed Deals</option>
                                <option value="1">Active Deals</option>
                                <option value="2">Paused Deals</option>
                                <option value="3">Canceled Deals</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-nowrap align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 30%;">Deal Name</th>
                                        <th scope="col" style="width: 30%;">Sales Rep</th>
                                        <th scope="col" style="width: 20%;">Amount</th>
                                        <th scope="col" style="width: 20%;">Close Date</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>Acme Inc Install</td>
                                        <td><img src="assets/images/users/avatar-1.jpg" alt=""
                                                class="avatar-xs rounded-circle me-2">
                                            <a href="#javascript: void(0);" class="text-body fw-medium">Donald Risher</a>
                                        </td>
                                        <td>$96k</td>
                                        <td>Today</td>
                                    </tr>
                                    <tr>
                                        <td>Save lots Stores</td>
                                        <td><img src="assets/images/users/avatar-2.jpg" alt=""
                                                class="avatar-xs rounded-circle me-2">
                                            <a href="#javascript: void(0);" class="text-body fw-medium">Jansh Brown</a>
                                        </td>
                                        <td>$55.7k</td>
                                        <td>30 Dec 2021</td>
                                    </tr>
                                    <tr>
                                        <td>William PVT</td>
                                        <td><img src="assets/images/users/avatar-7.jpg" alt=""
                                                class="avatar-xs rounded-circle me-2">
                                            <a href="#javascript: void(0);" class="text-body fw-medium">Ayaan Hudda</a>
                                        </td>
                                        <td>$102k</td>
                                        <td>25 Nov 2021</td>
                                    </tr>
                                    <tr>
                                        <td>Raitech Soft</td>
                                        <td><img src="assets/images/users/avatar-4.jpg" alt=""
                                                class="avatar-xs rounded-circle me-2">
                                            <a href="#javascript: void(0);" class="text-body fw-medium">Julia William</a>
                                        </td>
                                        <td>$89.5k</td>
                                        <td>20 Sep 2021</td>
                                    </tr>
                                    <tr>
                                        <td>Absternet LLC</td>
                                        <td><img src="assets/images/users/avatar-4.jpg" alt=""
                                                class="avatar-xs rounded-circle me-2">
                                            <a href="#javascript: void(0);" class="text-body fw-medium">Vitoria
                                                Rodrigues</a>
                                        </td>
                                        <td>$89.5k</td>
                                        <td>20 Sep 2021</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>end row -->

    </div>
    <!-- container-fluid -->
@endsection
