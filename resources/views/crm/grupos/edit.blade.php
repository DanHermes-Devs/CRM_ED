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
                        <form action="{{ route('grupos.update', $grupo->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="nombre_grupo" class="form-label">Nombre del Grupo:</label>
                                    <input type="text" class="form-control @error('nombre_grupo') is-invalid @enderror" id="nombre_grupo" name="nombre_grupo" placeholder="Nombre del grupo" value="{{ $grupo->nombre_grupo }}">
                                    
                                    @error('nombre_grupo')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="turno" class="form-label">Turno:</label>
                                    <select class="form-select @error('turno') is-invalid @enderror" id="turno" name="turno">
                                        <option value="">Seleccione una opción</option>
                                        <option value="1" {{ $grupo->turno == 1 ? 'selected' : '' }}>Matutino</option>
                                        <option value="2" {{ $grupo->turno == 2 ? 'selected' : '' }}>Vespertino</option>
                                        <option value="3" {{ $grupo->turno == 3 ? 'selected' : '' }}>Nocturno</option>
                                    </select>
                                    
                                    @error('turno')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="campaign_id" class="form-label">Campaña:</label>
                                    <select class="form-select @error('campaign_id') is-invalid @enderror" id="campaign_id" name="campaign_id">
                                        <option value="">Seleccione una opción</option>
                                        @foreach ($campanas as $campana)
                                            <option value="{{ $campana->id }}" {{ $grupo->campaign_id == $campana->id ? 'selected' : '' }}>{{ $campana->nombre_campana }}</option>
                                        @endforeach
                                    </select>
                                    
                                    @error('campaign_id')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="pais_id" class="form-label">País:</label>
                                    <select class="form-select @error('pais_id') is-invalid @enderror" id="pais_id" name="pais_id">
                                        <option value="">Seleccione una opción</option>
                                        @foreach ($paises as $pais)
                                            <option value="{{ $pais->id }}" {{ $grupo->pais_id == $pais->id ? 'selected' : '' }}>{{ $pais->pais }}</option>
                                        @endforeach
                                    </select>
                                    
                                    @error('pais_id')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="estatus" class="form-label">Estatus:</label>
                                    <select class="form-select @error('estatus') is-invalid @enderror" id="estatus" name="estatus">
                                        <option value="">Seleccione una opción</option>
                                        <option value="1" {{ $grupo->estatus == 1 ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ $grupo->estatus == 0 ? 'selected' : '' }}>Inactivo</option>
                                    </select>                                    

                                    @error('estatus')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="supervisor" class="form-label">Supervisor:</label>
                                    <select class="form-select @error('supervisor') is-invalid @enderror" id="supervisor" name="supervisor">
                                        <option value="">Seleccione una opción</option>
                                        @foreach ($supervisores as $supervisor)
                                            <option value="{{ $supervisor->id }}" {{ $grupo->supervisor == $supervisor->id ? 'selected' : '' }}>{{ $supervisor->name }}</option>
                                        @endforeach
                                    </select>                                    

                                    @error('supervisor')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción:</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $grupo->descripcion) }}</textarea>

                                    @error('descripcion')
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