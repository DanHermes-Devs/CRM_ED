@extends('crm.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">EDITAR PAÍS</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">EDITAR PAÍS</li>
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
                            <h4 class="text-left mb-3">EDITAR PAÍS</h4>
                            <a href="{{ route('paises.index') }}" class="btn btn-info mb-3">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-arrow-left-line"></i>
                                    Regresar
                                </div>
                            </a>
                        </div>

                        {{-- Formulario para agregar nuevo usuario --}}
                        <form action="{{ route('paises.update', $pais->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-3">
                                    <label for="pais" class="form-label">Nombre del País:</label>
                                    <input type="text" class="form-control @error('pais') is-invalid @enderror" id="pais" name="pais" placeholder="Nombre del país" value="{{ old('pais', $pais->pais) }}">
                                    
                                    @error('pais')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción:</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="5"  >{{ old('descripcion', $pais->descripcion) }}</textarea>

                                    @error('descripcion')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
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
                            <button type="submit" class="btn btn-primary waves-effect waves-light mb-3">Actualizar país</button>
                        </form>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div>
    <!-- container-fluid -->
@endsection
