@extends('crm.layouts.app')
<style>
    fieldset {
        border: 1px solid #ccc !important;
        padding: 20px !important;
        border-radius: 5px !important;
        margin-bottom: 20px !important;
    }

    legend {
        font-weight: bold !important;
        margin-bottom: 10px !important;
    }

    input[type="text"] {
        padding: 8px !important;
        border: 1px solid #ccc !important;
        border-radius: 3px !important;
        margin-bottom: 10px !important;
    }

    /*============================================= 
    TABLET HORIZONTAL (LG revisamos en 1024px) 
    =============================================*/ 
    
    @media (max-width:1199px) and (min-width:992px){ 
    
    } 
    
    /*============================================= 
    TABLET VERTICAL (MD revisamos en 768px) 
    =============================================*/ 
    
    @media (max-width:991px) and (min-width:768px){ 
    
    } 
    
    /*============================================= 
    MÓVIL HORIZONTAL (SM revisamos en 576px) 
    =============================================*/ 
    
    @media (max-width:767px) and (min-width:576px){ 

    } 
    
    /*============================================= 
    MOVIL VERTICAL (revisamos en 320px) 
    =============================================*/ 
    
    @media (max-width:575px){
        .flex-column .btn_responsive {
            width: 100%!important;
        }

        .flex-column .input_responsive {
            width: 100%!important;
        }
    }
</style>
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">EXPEDIENTE</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">EXPEDIENTE</li>
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

                        {{-- Mostramos el mensaje flash de exito de creacion de usuario aqui --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>¡Éxito!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('usuarios') }}" class="btn btn-info mb-3">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-arrow-left-line"></i>
                                    Regresar
                                </div>
                            </a>
                        </div>

                        {{-- Formulario para agregar nuevo usuario --}}
                        <form action="{{ route('store.crearExpediente') }}" method="POST" novalidate
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id_usuario" id="id_usuario" value="{{ $usuario->id }}">

                            <fieldset>
                                <legend>EXPEDIENTE DEL USUARIO: {{ $usuario->usuario }}</legend>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-3">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre del agente:</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre"
                                                placeholder="Nombre" value="{{ $usuario->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="mb-3">
                                            <label for="perfil" class="form-label">Perfil:</label>
                                            <input type="text" class="form-control" id="perfil" name="perfil"
                                                placeholder="perfil" value="{{ $usuario->roles->first()->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre del supervisor:</label>
                                            <select name="id_supervisor" id="id_supervisor"
                                                class="form-select @error('id_supervisor') is-invalid @enderror">
                                                <option>-- Selecciona un supervisor --</option>
                                                @foreach ($supervisores as $supervisor)
                                                    {{-- Mostramos el supervisor que tiene asignado con un if ternario --}}
                                                    <option value="{{ $supervisor->id }}"
                                                        {{ isset($expediente) && $supervisor->id == $expediente->id_supervisor ? 'selected' : '' }}>
                                                        {{ $supervisor->name }}</option>
                                                @endforeach
                                            </select>
    
                                            @error('id_supervisor')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Proyecto:</label>
                                            <select name="id_proyecto" id="id_proyecto" class="form-select">
                                                <option>-- Selecciona un proyecto --</option>
                                                @foreach ($proyectos as $proyecto)
                                                    <option value="{{ $proyecto->id }}"
                                                        {{ isset($expediente) && $proyecto->id == $expediente->id_proyecto ? 'selected' : '' }}>
                                                        {{ $proyecto->proyecto }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="row">
                                <div class="ml-3 mr-3">
                                    <div class="alert alert-warning">
                                        En caso de que el archivo PDF tenga un peso mayor a 2MB, favor de comprimirlo en 
                                        <a href="https://www.ilovepdf.com/es/comprimir_pdf" target="_blank" style="text-decoration: underline!important;" noreferrer>I❤PDF</a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <fieldset>
                                        <legend>Cargar INE</legend>
                                        <div class="mb-3 d-flex align-items-end gap-3 w-100 mb-3 d-flex align-items-end gap-3 w-100 flex-column flex-md-row">
                                            <div class="ine @if ($expediente && $expediente->ruta_ine) w-75 @else w-100 @endif input_responsive">
                                                <input id="ruta_ine" name="ruta_ine" class="form-control w-100"
                                                    type="file" accept=".jpeg, .jpg, .png, .pdf"
                                                    data-show-preview="true">
                                            </div>
                                            @if ($expediente && $expediente->ruta_ine)
                                                <a href="{{ asset('storage/uploads/ine/' . $usuario->usuario . '/' . $expediente->ruta_ine) }}"
                                                    target="_blank" class="btn btn-success w-25 btn_responsive">Ver Archivo</a>
                                            @endif
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <fieldset>
                                        <legend>Cargar Acta de Nacimiento</legend>
                                        <div class="mb-3 d-flex align-items-end gap-3 w-100 mb-3 d-flex align-items-end gap-3 w-100 flex-column flex-md-row">
                                            <div class="acta_nacimiento @if ($expediente && $expediente->ruta_acta_nacimiento) w-75 @else w-100 @endif input_responsive">
                                                <input type="file" accept=".jpeg, .jpg, .png, .pdf" class="form-control"
                                                    id="ruta_acta_nacimiento" name="ruta_acta_nacimiento"
                                                    data-show-preview="true">
                                            </div>

                                            @if ($expediente && $expediente->ruta_acta_nacimiento)
                                                <a href="{{ asset('storage/uploads/acta_nacimiento/' . $usuario->usuario . '/' . $expediente->ruta_acta_nacimiento) }}"
                                                    target="_blank" class="btn btn-success w-25 btn_responsive">Ver Archivo</a>
                                            @endif
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <fieldset>
                                        <legend>Cargar CURP</legend>
                                        <div class="mb-3 d-flex align-items-end gap-3 w-100 mb-3 d-flex align-items-end gap-3 w-100 flex-column flex-md-row">
                                            <div class="curp @if ($expediente && $expediente->ruta_curp) w-75 @else w-100 @endif input_responsive">
                                                <input type="file" accept=".jpeg, .jpg, .png, .pdf" class="form-control"
                                                    id="ruta_curp" name="ruta_curp" data-show-preview="true">
                                            </div>

                                            @if ($expediente && $expediente->ruta_curp)
                                                <a href="{{ asset('storage/uploads/curp/' . $usuario->usuario . '/' . $expediente->ruta_curp) }}"
                                                    target="_blank" class="btn btn-success w-25 btn_responsive">Ver Archivo</a>
                                            @endif
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <fieldset>
                                        <legend>Cargar Constancia Fiscal</legend>
                                        <div class="mb-3 mb-3 d-flex align-items-end gap-3 w-100 mb-3 d-flex align-items-end gap-3 w-100 flex-column flex-md-row">
                                            <div class="constancia_fiscal @if ($expediente && $expediente->ruta_constancia_fiscal) w-75 @else w-100 @endif input_responsive">
                                                <input type="file" accept=".jpeg, .jpg, .png, .pdf" class="form-control"
                                                    id="ruta_constancia_fiscal" name="ruta_constancia_fiscal"
                                                    data-show-preview="true">
                                            </div>

                                            @if ($expediente && $expediente->ruta_constancia_fiscal)
                                                <a href="{{ asset('storage/uploads/constancia_fiscal/' . $usuario->usuario . '/' . $expediente->ruta_constancia_fiscal) }}"
                                                    target="_blank" class="btn btn-success w-25 btn_responsive">Ver Archivo</a>
                                            @endif
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <fieldset>
                                        <legend>Cargar NSS</legend>
                                        <div class="mb-3 mb-3 d-flex align-items-end gap-3 w-100 mb-3 d-flex align-items-end gap-3 w-100 flex-column flex-md-row">
                                            <div class="nss @if ($expediente && $expediente->ruta_nss) w-75 @else w-100 @endif input_responsive">
                                                <input type="file" accept=".jpeg, .jpg, .png, .pdf" class="form-control"
                                                    id="ruta_nss" name="ruta_nss" data-show-preview="true">
                                            </div>

                                            @if ($expediente && $expediente->ruta_nss)
                                                <a href="{{ asset('storage/uploads/nss/' . $usuario->usuario . '/' . $expediente->ruta_nss) }}"
                                                    target="_blank" class="btn btn-success w-25 btn_responsive">Ver Archivo</a>
                                            @endif
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <fieldset>
                                        <legend>Cargar Comprobante de Estudios</legend>
                                        <div class="mb-3 mb-3 d-flex align-items-end gap-3 w-100 mb-3 d-flex align-items-end gap-3 w-100 flex-column flex-md-row">
                                            <div class="comp_estudios @if ($expediente && $expediente->ruta_comp_estudios) w-75 @else w-100 @endif input_responsive">
                                                <input type="file" accept=".jpeg, .jpg, .png, .pdf" class="form-control"
                                                    id="ruta_comp_estudios" name="ruta_comp_estudios"
                                                    data-show-preview="true">
                                            </div>

                                            @if ($expediente && $expediente->ruta_comp_estudios)
                                                <a href="{{ asset('storage/uploads/comp_estudios/' . $usuario->usuario . '/' . $expediente->ruta_comp_estudios) }}"
                                                    target="_blank" class="btn btn-success w-25 btn_responsive">Ver Archivo</a>
                                            @endif
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <fieldset>
                                        <legend>Cargar Comprobante de Domicilio</legend>
                                        <div class="mb-3 mb-3 d-flex align-items-end gap-3 w-100 mb-3 d-flex align-items-end gap-3 w-100 flex-column flex-md-row">
                                            <div class="comp_domicilio @if ($expediente && $expediente->ruta_comp_domicilio) w-75 @else w-100 @endif input_responsive">
                                                <input type="file" accept=".jpeg, .jpg, .png, .pdf" class="form-control"
                                                    id="ruta_comp_domicilio" name="ruta_comp_domicilio"
                                                    data-show-preview="true">
                                            </div>

                                            @if ($expediente && $expediente->ruta_comp_domicilio)
                                                <a href="{{ asset('storage/uploads/comp_domicilio/' . $usuario->usuario . '/' . $expediente->ruta_comp_domicilio) }}"
                                                    target="_blank" class="btn btn-success w-25 btn_responsive">Ver Archivo</a>
                                            @endif
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <fieldset>
                                        <legend>Cargar Estado Bancario</legend>
                                        <div class="mb-3 mb-3 d-flex align-items-end gap-3 w-100 mb-3 d-flex align-items-end gap-3 w-100 flex-column flex-md-row">
                                            <div class="edo_bancario @if ($expediente && $expediente->ruta_edo_bancario) w-75 @else w-100 @endif input_responsive">
                                                <input type="file" accept=".jpeg, .jpg, .png, .pdf" class="form-control"
                                                    id="ruta_edo_bancario" name="ruta_edo_bancario" data-show-preview="true">
                                            </div>

                                            @if ($expediente && $expediente->ruta_edo_bancario)
                                                <a href="{{ asset('storage/uploads/edo_bancario/' . $usuario->usuario . '/' . $expediente->ruta_edo_bancario) }}"
                                                    target="_blank" class="btn btn-success w-25 btn_responsive">Ver Archivo</a>
                                            @endif
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <fieldset>
                                        <legend>Cargar Aviso Ret. Infonavit</legend>
                                        <div class="mb-3 mb-3 d-flex align-items-end gap-3 w-100 mb-3 d-flex align-items-end gap-3 w-100 flex-column flex-md-row">
                                            <div class="aviso_ret_infonavit @if ($expediente && $expediente->ruta_aviso_ret_infonavit) w-75 @else w-100 @endif input_responsive">
                                                <input type="file" accept=".jpeg, .jpg, .png, .pdf" class="form-control"
                                                    id="ruta_aviso_ret_infonavit" name="ruta_aviso_ret_infonavit"
                                                    data-show-preview="true">
                                            </div>

                                            @if ($expediente && $expediente->ruta_aviso_ret_infonavit)
                                                <a href="{{ asset('storage/uploads/aviso_ret_infonavit/' . $usuario->usuario . '/' . $expediente->ruta_aviso_ret_infonavit) }}"
                                                    target="_blank" class="btn btn-success w-25 btn_responsive">Ver Archivo</a>
                                            @endif
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <fieldset>
                                        <legend>Cargar Aviso Ret. Fonacot</legend>
                                        <div class="mb-3 mb-3 d-flex align-items-end gap-3 w-100 mb-3 d-flex align-items-end gap-3 w-100 flex-column flex-md-row">
                                            <div class="aviso_ret_fonacot @if ($expediente && $expediente->ruta_aviso_ret_fonacot) w-75 @else w-100 @endif input_responsive">
                                                <input type="file" accept=".jpeg, .jpg, .png, .pdf" class="form-control"
                                                    id="ruta_aviso_ret_fonacot" name="ruta_aviso_ret_fonacot"
                                                    data-show-preview="true">
                                            </div>

                                            @if ($expediente && $expediente->ruta_aviso_ret_fonacot)
                                                <a href="{{ asset('storage/uploads/aviso_ret_fonacot/' . $usuario->usuario . '/' . $expediente->ruta_aviso_ret_fonacot) }}"
                                                    target="_blank" class="btn btn-success w-25 btn_responsive">Ver Archivo</a>
                                            @endif
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <fieldset>
                                        <legend>Cargar Contrato</legend>
                                        <div class="mb-3 mb-3 d-flex align-items-end gap-3 w-100 mb-3 d-flex align-items-end gap-3 w-100 flex-column flex-md-row">
                                            <div class="contrato @if ($expediente && $expediente->ruta_contrato) w-75 @else w-100 @endif input_responsive">
                                                <input type="file" accept=".jpeg, .jpg, .png, .pdf" class="form-control"
                                                    id="ruta_contrato" name="ruta_contrato"
                                                    data-show-preview="true">
                                            </div>

                                            @if ($expediente && $expediente->ruta_contrato)
                                                <a href="{{ asset('storage/uploads/contrato/' . $usuario->usuario . '/' . $expediente->ruta_contrato) }}"
                                                    target="_blank" class="btn btn-success w-25 btn_responsive">Ver Archivo</a>
                                            @endif
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <fieldset>
                                        <legend>Cargar Responsiva</legend>
                                        <div class="mb-3 mb-3 d-flex align-items-end gap-3 w-100 mb-3 d-flex align-items-end gap-3 w-100 flex-column flex-md-row">
                                            <div class="responsiva @if ($expediente && $expediente->ruta_responsiva) w-75 @else w-100 @endif input_responsive">
                                                <input type="file" accept=".jpeg, .jpg, .png, .pdf" class="form-control"
                                                    id="ruta_responsiva" name="ruta_responsiva"
                                                    data-show-preview="true">
                                            </div>

                                            @if ($expediente && $expediente->ruta_responsiva)
                                                <a href="{{ asset('storage/uploads/responsiva/' . $usuario->usuario . '/' . $expediente->ruta_responsiva) }}"
                                                    target="_blank" class="btn btn-success w-25 btn_responsive">Ver Archivo</a>
                                            @endif
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary waves-effect waves-light mb-3">Actualizar
                                expediente</button>
                        </form>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div>
    <!-- container-fluid -->

    <script>
        $(document).ready(function() {
            configureFileInput("#ruta_ine");
            configureFileInput("#ruta_acta_nacimiento");
            configureFileInput("#ruta_curp");
            configureFileInput("#ruta_constancia_fiscal");
            configureFileInput("#ruta_nss");
            configureFileInput("#ruta_comp_estudios");
            configureFileInput("#ruta_comp_domicilio");
            configureFileInput("#ruta_edo_bancario");
            configureFileInput("#ruta_aviso_ret_infonavit");
            configureFileInput("#ruta_aviso_ret_fonacot");
            configureFileInput("#ruta_contrato");
            configureFileInput("#ruta_responsiva");
        });

        function configureFileInput(selector) {
            $(selector).fileinput({
                showUpload: false,
                dropZoneEnabled: false,
                maxFileCount: 1,
                allowedFileExtensions: ["jpeg", "jpg", "png", "pdf"],
                language: "es",
            });
        }
    </script>
@endsection
