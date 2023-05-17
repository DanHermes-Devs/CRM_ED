@extends('crm.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">EDITAR ASEGURADORA</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">EDITAR ASEGURADORA</li>
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
                        
                        <div class="d-flex justify-content-between">
                            <h4 class="text-left mb-3">AGREGAR NUEVO GRUPO</h4>
                            <a href="{{ route('aseguradoras.index') }}" class="btn btn-info mb-3">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-arrow-left-line"></i>
                                    Regresar
                                </div>
                            </a>
                        </div>

                        {{-- Formulario para agregar nuevo usuario --}}
                        <form action="{{ route('actualizar-aseguradora', $insurance->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre_aseguradora" class="form-label">Nombre del Grupo:</label>
                                        <input type="text" class="form-control @error('nombre_aseguradora') is-invalid @enderror" id="nombre_aseguradora" name="nombre_aseguradora" placeholder="Nombre del grupo" value="{{ old('nombre_aseguradora', $insurance->nombre_aseguradora) }}">
                                        
                                        @error('nombre_aseguradora')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="estatus" class="form-label">Estatus:</label>
                                        <select class="form-select @error('estatus') is-invalid @enderror" id="estatus" name="estatus">
                                            <option>-- Seleccione una opción --</option>
                                            {{-- Mostramos la opcion seleccionada --}}
                                            <option value="1" {{ old('estatus', $insurance->status) == 1 ? 'selected' : '' }}>Activo</option>
                                            <option value="0" {{ old('estatus', $insurance->status) == 0 ? 'selected' : '' }}>Inactivo</option>
                                        </select>
    
                                        @error('estatus')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary waves-effect waves-light mb-3">Actualizar aseguradora</button>
                        </form>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div>
    <!-- container-fluid -->
@endsection