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
                    <h4 class="mb-sm-0">CDM</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">CDM</li>
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
                        <form action="{{ route('filter-dashboard') }}">
                            @method('POST')
                            @csrf
                            <div class="d-grid mb-3 grid-search">
                                <div class="form-group">
                                    <label for="campana">Campaña:</label>
                                    {{-- Mostramos un select con las campañas en duro activas --}}
                                    <select name="campana" id="campana" class="form-select" >
                                        <option value="0" disabled>-- Selecciona --</option>
                                        <option value="UNI"
                                            <?= $values->campana ? ($values->campana == 'UNI' ? 'selected="selected"' : '') : '' ?>>
                                            Universidad Insurgentes</option>
                                        <option value="QUA"
                                            <?= $values->campana ? ($values->campana == 'QUA' ? 'selected="selected"' : '') : '' ?>>
                                            Qualitas</option>
                                        <option value="AXA"
                                            <?= $values->campana ? ($values->campana == 'AXA' ? 'selected="selected"' : '') : '' ?>>
                                            Axa</option>
                                        <option value="PRA"
                                            <?= $values->campana ? ($values->campana == 'PRA' ? 'selected="selected"' : '') : '' ?>>
                                            Practicum</option>
                                        <option value="ADT"
                                            <?= $values->campana ? ($values->campana == 'ADT' ? 'selected="selected"' : '') : '' ?>>
                                            ADT</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha inicio:</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio"
                                        max="{{ today()->format('Y-m-d') }}"
                                        value="<?= $values->fecha_inicio ? ($values->fecha_inicio != '' ? $values->fecha_inicio : '') : '' ?>"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha fin:</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin"
                                        max="{{ today()->format('Y-m-d') }}"
                                        value="<?= $values->fecha_fin ? ($values->fecha_fin != '' ? $values->fecha_fin : '') : '' ?>"
                                        class="form-control">
                                </div>
                                <button type="submit" id="buscarDatos"
                                    class="btn btn-primary d-flex align-items-center justify-content-center gap-1 fs-5">
                                    <i class="ri-search-line"></i>
                                    Buscar
                                </button>

                                <input type="hidden" name="rol" value="{{ auth()->user()->roles->first()->name }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @if ($values->campana != 'PRA')
                <div class="col-xl-12">
                    <div class="card crm-widget">
                        <div class="card-body p-0">
                            <div class="row row-cols-xxl-5 row-cols-md-2 row-cols-1 g-0">
                                <div class="col">
                                    <div class="py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">LEADS <!--<i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>-->
                                        </h5>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="d-flex align-items-center gap-1">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-facebook-circle-line display-6 text-muted"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h2 class="mb-0"><span class="counter-value" data-target="{{ !empty($conteo[0]['leadsFb']) ? $conteo[0]['leadsFb'] : '' }}">0</span>
                                                    </h2>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-google-line display-6 text-muted"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h2 class="mb-0"><span class="counter-value"
                                                            data-target="{{ !empty($conteo[0]['leadsGoogle']) ? $conteo[0]['leadsGoogle'] : '' }}">0</span>
                                                    </h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col">
                                    <div class="mt-3 mt-md-0 py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">LLAMADAS <!--<i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>-->
                                        </h5>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="d-flex align-items-center gap-1">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-facebook-circle-line display-6 text-muted"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h2 class="mb-0"><span class="counter-value"
                                                            data-target="{{ !empty($llamadas[0]['llamadasFb']) ? $llamadas[0]['llamadasFb'] : '' }}">0</span>
                                                    </h2>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-google-line display-6 text-muted"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h2 class="mb-0"><span class="counter-value"
                                                            data-target="{{ !empty($llamadas[0]['llamadasGoogle']) ? $llamadas[0]['llamadasGoogle'] : '' }}">0</span>
                                                    </h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col">
                                    <div class="mt-3 mt-md-0 py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">PREVENTAS</h5>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="d-flex align-items-center gap-1">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-facebook-circle-line display-6 text-muted"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h2 class="mb-0">
                                                        <span class="counter-value"
                                                            data-target="{{ !empty($preventas[0]['Total']) ? $preventas[0]['Total'] : '' }}">0</span>
                                                    </h2>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-google-line display-6 text-muted"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h2 class="mb-0"><span class="counter-value"
                                                            data-target="{{ !empty($preventas[1]['Total']) ? $preventas[1]['Total'] : '' }}">0</span>
                                                    </h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col">
                                    <div class="mt-3 mt-md-0 py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">VENTAS</h5>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="d-flex align-items-center gap-1">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-facebook-circle-line display-6 text-muted"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h2 class="mb-0">
                                                        <span class="counter-value"
                                                            data-target="{{ !empty($ventas[0]['cobradasFB']) ? $ventas[0]['cobradasFB'] : '' }}">0</span>
                                                    </h2>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-google-line display-6 text-muted"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h2 class="mb-0"><span class="counter-value"
                                                            data-target="{{ !empty($ventas[0]['cobradasGoogle']) ? $ventas[0]['cobradasGoogle'] : '' }}">0</span>
                                                    </h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col">
                                    <div class="mt-3 mt-md-0 py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">Ratio
                                            <!--<i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>-->
                                        </h5>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="d-flex align-items-center gap-1">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-facebook-circle-line display-6 text-muted"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    @php

                                                        if ($values->campana == 'UNI' || !$values->campana){
                                                            $ventasFb = empty($ventas[0]['cobradasFB']) ? 0 : $ventas[0]['cobradasFB'];
                                                        } else {
                                                            $ventasFb = empty($preventas[0]['Total']) ? 0 : $preventas[0]['Total'];
                                                        }
                                                        $leadsFb = $conteo[0]['leadsFb'];

                                                        $ratioFb = 0;
                                                        if ($leadsFb > 0) {
                                                            $ratioFb =round($ventasFb / $leadsFb, 2) * 100;
                                                        }
                                                    @endphp
                                                    <h2 class="mb-0"><span class="counter-value" data-target="{{ !empty($ratioFb) ? $ratioFb : '' }}">0</span>%</h2>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-google-line display-6 text-muted"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    @php


                                                        if ($values->campana == 'UNI' || !$values->campana){
                                                            $ventasGl = empty($ventas[0]['cobradasGoogle']) ? 0 : $ventas[0]['cobradasGoogle'];
                                                        } else {
                                                            $ventasGl = empty($preventas[1]['Total']) ? 0 : $preventas[1]['Total'];
                                                        }
                                                        $leadsGl = $conteo[0]['leadsGoogle'];



                                                        $ratioGo = 0;
                                                        if ($leadsGl > 0) {
                                                            $ratioGo =round($ventasGl / $leadsGl, 2) * 100;
                                                        }
                                                    @endphp
                                                    <h2 class="mb-0"><span class="counter-value" data-target="{{ !empty($ratioGo) ? $ratioGo : '' }}">0</span>%
                                                    </h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            @else
        </div><!-- end col -->
        <div class="col-xl-12">
            <div class="card crm-widget">
                <div class="card-body p-0">
                    <div class="row row-cols-xxl-4 row-cols-md-3 row-cols-1 g-0">
                        <div class="col">
                            <div class="py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">LEADS <!--<i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>-->
                                </h5>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-facebook-circle-line display-6 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0"><span class="counter-value"
                                                    data-target="{{ !empty($conteo[0]['leadsFb']) ? $conteo[0]['leadsFb'] : '' }}">0</span>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-md-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">LLAMADAS <!--<i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>-->
                                </h5>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-facebook-circle-line display-6 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0"><span class="counter-value"
                                                    data-target="{{ !empty($llamadas[0]['llamadasFb']) ? $llamadas[0]['llamadasFb'] : '' }}">0</span>
                                            </h2>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-md-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">PREVENTAS
                                    <!--<i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>-->
                                </h5>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-facebook-circle-line display-6 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0">
                                                <span class="counter-value"
                                                    data-target="{{ !empty($ventas[0]['Total']) ? $ventas[0]['Total'] : '' }}">0</span>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-md-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">RATIO
                                </h5>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-facebook-circle-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                @php
                                                    $leadsFb = $conteo[0]['leadsFb'];
                                                    $ventasFb = empty($ventas[0]['Total']) ? 0 : $ventas[0]['Total'];
                                                    $ratioFb = 0;
                                                    if ($leadsFb > 0) {
                                                        $ratioFb =round($ventasFb / $leadsFb, 2) * 100;
                                                    }
                                                @endphp
                                                <h2 class="mb-0"><span class="counter-value"
                                                        data-target="{{ !empty($ratioFb) ? $ratioFb : '' }}">0</span>%
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
        @endif
    </div><!-- end row -->

    <div class="row">
        <div class="col-xl-9">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Llamadas por agente</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-muted">
                                    <th scope="col">Nombre del agente</th>
                                    <th scope="col">Llamadas Totales</th>
                                    <th scope="col" style="width: 16%;">Llamadas Primer Contacto</th>
                                    <th scope="col" style="width: 16%;">Preventas</th>
                                    <th scope="col" style="width: 16%;">Ratio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lYvAYc as $item)
                                    <tr>
                                        <td>{{ $item['nombre'] != '' ? $item['nombre'] : 'No disponible' }}</td>
                                        <td>{{ $item['totalLlamadas'] != '' ? $item['totalLlamadas'] : '0' }}
                                        </td>
                                        <td>{{ $item['primerContacto'] != '' ? $item['primerContacto'] : '0' }}
                                        </td>
                                        <td>{{ $item['ventas'] != '' ? $item['ventas'] : '0' }}</td>
                                        <td>{{ $item['Ratio'] != '' ? number_format($item['Ratio'], 2) : '0' }}
                                            %
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

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
                    <div id="leads" data-colors='["--vz-primary", "--vz-success", "--vz-warning"]'
                        class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div><!-- end row -->


    <div class="row">
        <div class="col-xl-3">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Últimos resultados de contactación</h4>
                </div><!-- end card header -->

                <div class="card-body p-0">
                    <div data-simplebar style="max-height: 285px">
                        <ul class="list-group list-group-flush border-dashed px-3">
                            @foreach ($tipificacion as $item)
                                <li class="list-group-item ps-0">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1">
                                            <label class="form-check-label mb-0 ps-2"
                                                for="task_one">{{ $item['resultadoUC'] }}</label>
                                        </div>
                                        <div class="flex-shrink-0 ms-2">
                                            <p class="text-muted fs-12 mb-0">{{ $item['Total'] }}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul><!-- end ul -->
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xxl-9 col-md-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Estadistica de Tipificaciones</h4>
                </div>
                <div class="card-body pb-0">
                    <div id="tipificaciones"
                        data-colors='[@foreach ($tipificacion as $i => $item) "--vz-primary" {{ $loop->last ? '' : ',' }} @endforeach]'
                        class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div><!-- end row -->

    </div>
    <!-- container-fluid -->
    <script src="{{ asset('./assets/js/plugins.js') }}"></script>
    <script src="{{ asset('./assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        function getChartColorsArray(e) {
            if (null !== document.getElementById(e)) {
                var t = document.getElementById(e).getAttribute("data-colors");
                if (t) return (t = JSON.parse(t)).map(function(e) {
                    var t = e.replace(" ", "");
                    if (-1 === t.indexOf(",")) {
                        var r = getComputedStyle(document.documentElement).getPropertyValue(t);
                        return r || t
                    }
                    e = e.split(",");
                    return 2 != e.length ? t : "rgba(" + getComputedStyle(document.documentElement)
                        .getPropertyValue(e[0]) + "," + e[1] + ")"
                });
                console.warn("data-colors Attribute not found on:", e)
            }
        }
        var areachartSalesColors = getChartColorsArray("leads");
        @if ($values->campana != 'PRA')
            var dataFin = [{{ $conteo[0]['leadsFb'] + $conteo[0]['leadsGoogle'] }}];
        @else
            var dataFin = [{{ $conteo[0]['leadsFb'] }}];
        @endif;
        areachartSalesColors && (options = {
            series: [{
                    name: "total",
                    data: dataFin
                }, {
                    name: "Facebook",
                    data: [{{ $conteo[0]['leadsFb'] }}]
                }
                @if ($values->campana != 'PRA')
                    , {
                        name: "Google",
                        data: [{{ $conteo[0]['leadsGoogle'] }}]
                    }
                @endif
            ],
            chart: {
                type: "bar",
                height: 341,
                toolbar: {
                    show: !1
                }
            },
            plotOptions: {
                bar: {
                    horizontal: !1,
                    columnWidth: "65%"
                }
            },
            stroke: {
                show: !0,
                width: 5,
                colors: ["transparent"]
            },
            xaxis: {
                categories: [""],
                axisTicks: {
                    show: !1,
                    borderType: "solid",
                    color: "#78909C",
                    height: 6,
                    offsetX: 0,
                    offsetY: 0
                },
                title: {
                    text: "Total de leads al {{ now() }}",
                    offsetX: 0,
                    offsetY: -30,
                    style: {
                        color: "#78909C",
                        fontSize: "12px",
                        fontWeight: 400
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(e) {
                        return e
                    }
                },
                tickAmount: 4,
                min: 0
            },
            fill: {
                opacity: 1
            },
            legend: {
                show: !0,
                position: "bottom",
                horizontalAlign: "center",
                fontWeight: 500,
                offsetX: 0,
                offsetY: -14,
                itemMargin: {
                    horizontal: 8,
                    vertical: 0
                },
                markers: {
                    width: 10,
                    height: 10
                }
            },
            colors: areachartSalesColors
        }, (chart = new ApexCharts(document.querySelector("#leads"), options)).render());
        var areachartSalesColors = getChartColorsArray("tipificaciones");
        areachartSalesColors && (options = {
            series: [{
                name: "columna",
                data: [
                    @foreach ($tipificacion as $item)
                        {{ $item['Total'] }}{{ $loop->last ? '' : ',' }}
                    @endforeach
                ]
            }],
            chart: {
                type: "bar",
                height: 341,
                toolbar: {
                    show: !1
                }
            },
            plotOptions: {
                bar: {
                    horizontal: !1,
                    columnWidth: "65%"
                }
            },
            stroke: {
                show: !0,
                width: 3,
                colors: ["transparent"]
            },
            xaxis: {
                categories: [
                    @foreach ($tipificacion as $item)
                        "{{ $item['resultadoUC'] }}"
                        {{ $loop->last ? '' : ',' }}
                    @endforeach
                ],
                axisTicks: {
                    show: !1,
                    borderType: "solid",
                    color: "#78909C",
                    height: 6,
                    offsetX: 0,
                    offsetY: 0
                },
                title: {
                    text: "",
                    offsetX: 0,
                    offsetY: -30,
                    style: {
                        color: "#78909C",
                        fontSize: "12px",
                        fontWeight: 400
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(e) {
                        return e
                    }
                },
                tickAmount: 1,
                min: 0,
                max: {{ $total }}
            },
            fill: {
                opacity: 1
            },
            legend: {
                show: !0,
                position: "bottom",
                horizontalAlign: "center",
                fontWeight: 500,
                offsetX: 0,
                offsetY: -14,
                itemMargin: {
                    horizontal: 8,
                    vertical: 0
                },
                markers: {
                    width: 10,
                    height: 10
                }
            },
            colors: areachartSalesColors
        }, (chart = new ApexCharts(document.querySelector("#tipificaciones"), options)).render());

        //     var options = {
        //     chart: {
        //         type: 'bar',
        //         height: 400
        //     },
        //     series: [
        //         {
        //             name: 'Columnas',
        //             data: [20, 30, 40, 25, 35, 45, 50, 15, 30, 20] // Valores de ejemplo para las columnas
        //         }
        //     ],
        //     xaxis: {
        //         categories: ['Columna 1', 'Columna 2', 'Columna 3', 'Columna 4', 'Columna 5', 'Columna 6', 'Columna 7', 'Columna 8', 'Columna 9', 'Columna 10']
        //     }
        // };

        // var chart = new ApexCharts(document.querySelector("#tipificaciones"), options);
        // chart.render();

        var options, chart, revenueExpensesChartsColors = getChartColorsArray("deals");
        revenueExpensesChartsColors && (options = {
            series: [{
                name: "Revenue",
                data: [20, 25, 30, 35, 40, 55, 70, 110, 150, 180, 210, 250]
            }, {
                name: "Expenses",
                data: [12, 17, 45, 42, 24, 35, 42, 75, 102, 108, 156, 199]
            }],
            chart: {
                height: 290,
                type: "area",
                toolbar: "false"
            },
            dataLabels: {
                enabled: !1
            },
            stroke: {
                curve: "smooth",
                width: 2
            },
            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
            },
            yaxis: {
                labels: {
                    formatter: function(e) {
                        return "$" + e + "k"
                    }
                },
                tickAmount: 5,
                min: 0,
                max: 260
            },
            colors: revenueExpensesChartsColors,
            fill: {
                opacity: .06,
                colors: revenueExpensesChartsColors,
                type: "solid"
            }
        }, (chart = new ApexCharts(document.querySelector("#deals"), options)).render());
        // function reloadPage() { location.reload();}
        // setInterval(reloadPage, 60000);
    </script>
@endsection
