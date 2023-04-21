@extends('crm.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">CREAR USUARIO</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">CREAR USUARIO</li>
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
                            <h4 class="text-left mb-3">AGREGAR NUEVO USUARIO</h4>
                            <a href="{{ route('usuarios') }}" class="btn btn-info mb-3">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-arrow-left-line"></i>
                                    Regresar
                                </div>
                            </a>
                        </div>

                        {{-- Formulario para agregar nuevo usuario --}}
                        <form action="{{ route('store-usuario') }}" method="POST" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="usuario" class="form-label">Usuario:</label>
                                        <input type="text" class="form-control @error('usuario') is-invalid @enderror" id="usuario" name="usuario" placeholder="Usuario" value="{{ old('usuario') }}">
                                        
                                        @error('usuario')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre:</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="nombre" name="name" placeholder="Nombre" value="{{ old('name') }}">
        
                                        @error('name')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="apellido_paterno" class="form-label">Apellido Paterno:</label>
                                        <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror" id="apellido_paterno" name="apellido_paterno" placeholder="Apellido Paterno" value="{{ old('apellido_paterno') }}">
        
                                        @error('apellido_paterno')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="apellido_materno" class="form-label">Apellido Materno:</label>
                                        <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror" id="apellido_materno" name="apellido_materno" placeholder="Apellido Materno" value="{{ old('apellido_materno') }}">
        
                                        @error('apellido_materno')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Correo Electrónico:</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Correo Electrónico" value="{{ old('email') }}">
        
                                        @error('email')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="estatus" class="form-label">Estatus:</label>
                                        <select class="form-select @error('estatus') is-invalid @enderror" id="estatus" name="estatus">
                                            <option value="">Selecciona una opción</option>
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
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="roles" class="form-label">Rol:</label>
                                        <select class="form-select @error('roles') is-invalid @enderror" id="roles" name="roles">
                                            <option value="">Selecciona una opción</option>
                                            @foreach ($roles as $rol)
                                                <option value="{{ $rol }}">{{ $rol }}</option>
                                            @endforeach
                                        </select>

        
                                        @error('roles')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Contraseña:</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Contraseña">
        
                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary waves-effect waves-light mb-3">Guardar usuario</button>
                        </form>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div>
    <!-- container-fluid -->
@endsection
