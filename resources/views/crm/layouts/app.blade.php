<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="sm" data-sidebar-image="none" data-layout-style="default" data-layout-mode="light" data-layout-width="fluid" data-layout-position="fixed">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Datatables --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/autofill/2.4.0/css/autoFill.bootstrap4.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowgroup/1.2.0/css/rowGroup.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <!-- Bootstrap Css -->
    <link href="{{ asset('./assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link rel="stylesheet" href="{{ asset('./assets/css/icons.min.css') }}">
    <!-- default icons used in the plugin are from Bootstrap 5.x icon library (which can be enabled by loading CSS below) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <!-- the fileinput plugin styling CSS file -->
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('./assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('./assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="app">
        <!-- Begin page -->
        <div id="layout-wrapper">
            <header id="page-topbar">
                <div class="layout-width">
                    <div class="navbar-header">
                        <div class="d-flex">
                            <button type="button"
                                class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                                id="topnav-hamburger-icon">
                                <span class="hamburger-icon">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </span>
                            </button>
                        </div>

                        <div class="d-flex align-items-center">

                            <div class="dropdown d-md-none topbar-head-dropdown header-item">
                                <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                    id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="bx bx-search fs-22"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                    aria-labelledby="page-header-search-dropdown">
                                    <form class="p-3">
                                        <div class="form-group m-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Search ..."
                                                    aria-label="Recipient's username">
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="mdi mdi-magnify"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="header-item">
                                <div class="alert alert-success alert-border-left mb-0" role="alert">
                                    <strong>{{ Auth::user()->roles->first()->name }}</strong>
                                </div>
                            </div>

                            <div class="ms-1 header-item d-none">
                                <button type="button"
                                    class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                                    <i class='bx bx-moon fs-22'></i>
                                </button>
                            </div>

                            <div class="ms-1 header-item d-none d-sm-flex">
                                <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                                    <i class="bx bx-fullscreen fs-22"></i>
                                </button>
                            </div>

                            <div class="dropdown ms-sm-3 header-item topbar-user">
                                <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <span class="d-flex align-items-center">
                                        <span class="text-start">
                                            <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                                                {{-- Si el usuario esta logueado se muestra su nombre --}}
                                                @if (Auth::check())
                                                    {{ Auth::user()->name }}
                                                @endif
                                            </span>
                                        </span>
                                    </span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <h6 class="dropdown-header">Bienvenido(a)  @if (Auth::check())
                                        {{ Auth::user()->name }}
                                    @endif!</h6>
                                    {{-- <a class="dropdown-item" href="pages-profile.html"><i
                                            class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                            class="align-middle">Perfil</span></a> --}}

                                    @guest
                                    @endguest
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                                        <span class="align-middle" data-key="t-logout">{{ __('Cerrar Sesión') }}</span>
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ========== App Menu ========== -->
            <div class="app-menu navbar-menu">
                <!-- LOGO -->

                <div class="navbar-brand-box">
                    <!-- Dark Logo-->
                    <a href="{{ route('dasboard') }}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ asset('./assets/images/brand/logo_white.svg') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('./assets/images/brand/logo_white.svg') }}" alt="" height="17">
                        </span>
                    </a>
                    <!-- Light Logo-->
                    <a href="{{ route('dasboard') }}" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ asset('./assets/images/brand/logo_white.svg') }}" class="w-100 py-3">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('./assets/images/brand/logo_white.svg') }}" class="w-50 py-4">
                        </span>
                    </a>
                    <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                        <i class="ri-record-circle-line"></i>
                    </button>
                </div>

                <div id="scrollbar">
                    <div class="container-fluid">

                        <div id="two-column-menu">
                        </div>
                        <ul class="navbar-nav" id="navbar-nav">
                            <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                            @if (Auth::user()->hasAnyRole(['Administrador', 'Director', 'Supervisor', 'Coordinador']))
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="{{ route('dasboard') }}">
                                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                                    </a>
                                </li>
                            @endif

                            {{-- Si el usuario es director, no mostramos el contenido h1 hola --}}
                            @if(Auth::user()->hasAnyRole(['Administrador', 'Supervisor', 'BI', 'Coordinador', 'Agente de Ventas', 'Agente de Cobranza', 'Agente Renovaciones']))
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarMultilevel" data-bs-toggle="collapse" role="button" aria-expanded="true"
                                        aria-controls="sidebarMultilevel">
                                        <i class="ri-folders-line"></i> <span data-key="t-dashboards">Módulos</span>
                                    </a>

                                    <div class="menu-dropdown collapse" id="sidebarMultilevel" style="">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="#modulo_seguros" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="true"
                                                    aria-controls="modulo_seguros" data-key="t-level-1.2">
                                                    Módulo Seguros
                                                </a>
                                                <div class="menu-dropdown collapse" id="modulo_seguros" style="">
                                                    <ul class="nav nav-sm flex-column">
                                                        @if (Auth::user()->hasAnyRole(['Agente de Ventas', 'Agente Renovaciones', 'BI', 'Administrador', 'Supervisor', 'Coordinador']))
                                                        <li class="nav-item">
                                                            <a href="{{ route('ventas.index') }}" class="nav-link" data-key="t-analytics">
                                                                Ventas
                                                            </a>
                                                        </li>
                                                        @endif
                                                        @if (Auth::user()->hasAnyRole(['Agente de Cobranza', 'Administrador', 'Supervisor', 'Coordinador']))
                                                            <li class="nav-item">
                                                                <a href="{{ route('cobranza.index') }}" class="nav-link" data-key="t-analytics">
                                                                    Cobranza
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </li>
                                            @if (Auth::user()->hasAnyRole(['Agente de Ventas', 'Agente Renovaciones', 'BI', 'Administrador', 'Supervisor', 'Coordinador']))
                                            <li class="nav-item">
                                                <a href="#modulo_education" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="true"
                                                    aria-controls="modulo_education" data-key="t-level-1.2">
                                                    Módulo Educación
                                                </a>
                                                <div class="menu-dropdown collapse" id="modulo_education" style="">
                                                    <ul class="nav nav-sm flex-column">
                                                        <li class="nav-item">
                                                            <a href="{{ route('educacion-uin.index') }}" class="nav-link" data-key="t-analytics">
                                                                Ventas
                                                            </a>
                                                        </li>
                                                        {{-- @if (Auth::user()->hasAnyRole(['Agente de Cobranza', 'Administrador', 'Supervisor', 'Coordinador']))
                                                            <li class="nav-item">
                                                                <a href="{{ route('cobranza.index') }}" class="nav-link" data-key="t-analytics">
                                                                    Cobranza
                                                                </a>
                                                            </li>
                                                        @endif --}}
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#modulo_seguridad" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="true"
                                                    aria-controls="modulo_seguridad" data-key="t-level-1.2">
                                                    Módulo Seguridad
                                                </a>
                                                <div class="menu-dropdown collapse" id="modulo_seguridad" style="">
                                                    <ul class="nav nav-sm flex-column">
                                                        <li class="nav-item">
                                                            <a href="#modulo_adt" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="true"
                                                                aria-controls="modulo_adt" data-key="t-level-1.2">
                                                                ADT
                                                            </a>
                                                            <div class="menu-dropdown collapse" id="modulo_adt" style="">
                                                                <ul class="nav nav-sm flex-column">
                                                                    <li class="nav-item">
                                                                        <a href="{{ route('seguridad-adt.index') }}" class="nav-link" data-key="t-analytics">
                                                                            Ventas
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#modulo_telecomunicaciones" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="true"
                                                    aria-controls="modulo_telecomunicaciones" data-key="t-level-1.2">
                                                    Módulo Telecom
                                                </a>
                                                <div class="menu-dropdown collapse" id="modulo_telecomunicaciones" style="">
                                                    <ul class="nav nav-sm flex-column">
                                                        <li class="nav-item">
                                                            <a href="#modulo_izi" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="true"
                                                                aria-controls="modulo_izi" data-key="t-level-1.2">
                                                                Telecom
                                                            </a>
                                                            <div class="menu-dropdown collapse" id="modulo_izi" style="">
                                                                <ul class="nav nav-sm flex-column">
                                                                    <li class="nav-item">
                                                                        <a href="{{ route('izzi.index') }}" class="nav-link" data-key="t-analytics">
                                                                            Ventas
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif

                            @if (Auth::user()->hasAnyRole(['Administrador', 'RH']))
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#modulo_rrhh" data-bs-toggle="collapse" role="button" aria-expanded="true"
                                        aria-controls="modulo_rrhh">
                                        <i class="ri-folder-user-line"></i> <span data-key="t-dashboards">Módulo R.R.H.H.</span>
                                    </a>
                                    <div class="menu-dropdown collapse" id="modulo_rrhh" style="">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="#usuario" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="true"
                                                    aria-controls="usuario" data-key="t-level-1.2">
                                                    Usuarios
                                                </a>
                                                <div class="menu-dropdown collapse" id="usuario" style="">
                                                    <ul class="nav nav-sm flex-column">
                                                        <li class="nav-item">
                                                            <a href="{{ route('usuarios') }}" class="nav-link" data-key="t-analytics">
                                                                Todos los Usuarios
                                                            </a>
                                                            <a href="{{ route('roles.index') }}" class="nav-link" data-key="t-analytics">
                                                                Roles
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#asistencia" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="true"
                                                    aria-controls="asistencia" data-key="t-level-1.2">
                                                    Asistencias
                                                </a>
                                                <div class="menu-dropdown collapse" id="asistencia" style="">
                                                    <ul class="nav nav-sm flex-column">
                                                        <li class="nav-item">
                                                            <a href="{{ route('asistencias') }}" class="nav-link" data-key="t-analytics">
                                                                Asistencias
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endif

                            {{-- Solo lo puede ver administrador este bloque html --}}
                            @if (Auth::user()->hasAnyRole(['Administrador', 'RH']))
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarTables" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarTables">
                                        <i class="ri-layout-grid-line"></i> <span data-key="t-tables">Catálogos</span>
                                    </a>
                                    <div class="menu-dropdown collapse" id="sidebarTables" style="">
                                        <ul class="nav nav-sm flex-column">
                                            @if (Auth::user()->hasAnyRole(['Administrador']))
                                            <li class="nav-item">
                                                <a href="{{ route('paises.index') }}" class="nav-link" data-key="t-basic-tables">Paises</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('aseguradoras.index') }}" class="nav-link" data-key="t-grid-js">Aseguradoras</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('proyectos.index') }}" class="nav-link" data-key="t-list-js">Proyectos</a>
                                            </li>
                                            @endif
                                            @if (Auth::user()->hasAnyRole(['Administrador', 'RH']))
                                            <li class="nav-item">
                                                <a href="{{ route('campaigns.index') }}" class="nav-link" data-key="t-list-js">Campañas</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('grupos.index') }}" class="nav-link" data-key="t-list-js">Segmento</a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                                @if (Auth::user()->hasAnyRole(['Administrador']))
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="{{ route('cronjobs.index') }}">
                                        <i class="ri-settings-6-line"></i> <span data-key="t-dashboards">Tareas Automáticas</span>
                                    </a>
                                </li>
                                @endif
                            @endif
                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>

                <div class="sidebar-background"></div>
            </div>
            <!-- Left Sidebar End -->
            <!-- Vertical Overlay-->
            <div class="vertical-overlay"></div>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    @yield('content')
                </div>
                <!-- End Page-content -->

                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> ©
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Exponente Digital - Incremental Sales
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

    {{-- File input --}}
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/buffer.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/filetype.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/piexif.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/sortable.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/fileinput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/locales/LANG.js"></script>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('./assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('./assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('./assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('./assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('./assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('./assets/js/plugins.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="{{ asset('./assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ asset('./assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('./assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('./assets/js/pages/dashboard-crm.init.js') }}"></script>

    {{-- Data tables --}}
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.3.0/js/responsive.bootstrap4.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.2.0/js/dataTables.rowGroup.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- password-addon init -->
    <script src="{{ asset('assets/js/pages/password-addon.init.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    {{-- Spanish jS --}}
    <script src="{{ asset('js/Spanish.js')}}"></script>
    <script src="{{ asset('js/es.js')}}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @yield('scripts')
</body>

</html>
