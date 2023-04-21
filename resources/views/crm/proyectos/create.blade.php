@extends('crm.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">NUEVO PROYECTO</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">NUEVO PROYECTO</li>
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
                            <h4 class="text-left mb-3">AGREGAR NUEVO PROYECTO</h4>
                            <a href="{{ route('proyectos.index') }}" class="btn btn-info mb-3">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-arrow-left-line"></i>
                                    Regresar
                                </div>
                            </a>
                        </div>

                        {{-- Formulario para agregar nuevo usuario --}}
                        <form action="{{ route('proyectos.store') }}" method="POST" novalidate>
                            @csrf
                            <div class="row">
                                <div class="mb-3">
                                    <label for="proyecto" class="form-label">Nombre del Proyecto:</label>
                                    <input type="text" class="form-control @error('proyecto') is-invalid @enderror" id="proyecto" name="proyecto" placeholder="Nombre del proyecto" value="{{ old('proyecto') }}">
                                    
                                    @error('proyecto')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción:</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="5">{{ old('descripcion') }}</textarea>

                                    @error('descripcion')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="id_pais" class="form-label">País:</label>
                                    <select class="form-select @error('id_pais') is-invalid @enderror" id="id_pais" name="id_pais">
                                        <option value="">Seleccione una opción</option>
                                        @foreach ($paises as $pais)
                                            {{-- Si el pais tiene estatus 1, lo mostramos en el select --}}
                                            @if ($pais->estatus == 1)
                                                <option value="{{ $pais->id }}" {{ old('id_pais') == $pais->id ? 'selected' : '' }}>{{ $pais->pais }}</option>
                                            @endif
                                        @endforeach
                                    </select>
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
                            <button type="submit" class="btn btn-primary waves-effect waves-light mb-3">Guardar proyecto</button>
                        </form>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div>
    <!-- container-fluid -->
@endsection
