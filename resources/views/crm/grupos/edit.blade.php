@extends('crm.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">NUEVO GRUPO</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">NUEVO GRUPO</li>
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
                            <a href="{{ route('grupos.index') }}" class="btn btn-info mb-3">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-arrow-left-line"></i>
                                    Regresar
                                </div>
                            </a>
                        </div>

                        {{-- Formulario para agregar nuevo usuario --}}
                        <form action="{{ route('grupos.store') }}" method="POST" novalidate>
                            @csrf
                            <div class="row">
                                <div class="mb-3">
                                    <label for="grupo" class="form-label">Nombre del Grupo:</label>
                                    <input type="text" class="form-control @error('grupo') is-invalid @enderror" id="grupo" name="grupo" placeholder="Nombre del grupo" value="{{ old('grupo', $grupo->grupo) }}">
                                    
                                    @error('grupo')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción:</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $grupo->descripcion) }}</textarea>

                                    @error('descripcion')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>

                                {{-- Checkboxes con lista de proyectos --}}
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="proyecto" class="form-label">Proyectos:</label>
                                        <select name="proyecto" id="proyecto" class="form-select @error('proyecto') is-invalid @enderror">
                                            @foreach ($proyectos as $proyecto)
                                                {{-- Mostramos el proyecto seleccionado desde la bD --}}
                                                <option value="{{ $proyecto->id }}" {{ $proyecto->id == $grupo->proyecto_id ? 'selected' : '' }}>{{ $proyecto->proyecto }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                    
                                {{-- Checkboxes con lista de usuarios --}}
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="usuarios" class="form-label">Usuarios:</label>
                                        <select name="usuarios[]" id="usuarios" class="form-select @error('usuarios') is-invalid @enderror" multiple>
                                            @foreach ($usuarios as $usuario)
                                                {{-- Validamos que la variable $json_users tenga algo --}}
                                                @if (!$json_users)
                                                    <option value="{{ $usuario->id }}" {{ in_array($usuario->id, $json_users) ? 'selected' : '' }}>{{ $usuario->name }}</option>
                                                @else
                                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        @error('usuarios')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="estatus" class="form-label">Estatus:</label>
                                    <select class="form-select @error('estatus') is-invalid @enderror" id="estatus" name="estatus">
                                        <option value="">Seleccione una opción</option>
                                        <option value="1" {{ old('estatus') == 1 ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ old('estatus') == 0 ? 'selected' : '' }}>Inactivo</option>
                                    </select>

                                    @error('estatus')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary waves-effect waves-light mb-3">Guardar grupo</button>
                        </form>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div>
    <!-- container-fluid -->
@endsection