@extends('crm.layouts.app')

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">EDITAR USUARIO</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">EDITAR USUARIO</li>
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
                        <h4 class="text-left mb-3">EDITAR USUARIO</h4>
                        <a href="{{ route('usuarios') }}" class="btn btn-info mb-3">
                            <div class="d-flex align-items-center gap-1">
                                <i class="ri-arrow-left-line"></i>
                                Regresar
                            </div>
                        </a>
                    </div>

                    {{-- Formulario para editar usuario --}}
                    <form action="{{ route('actualizar-usuario', $usuario->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="usuario" class="form-label">Usuario</label>
                                    <input type="text" class="form-control" id="usuario" name="usuario" value="{{ $usuario->usuario }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $usuario->name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="{{ $usuario->apellido_paterno }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="{{ $usuario->apellido_materno }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $usuario->email }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estatus" class="form-label">Estatus</label>
                                    <select class="form-select" id="estatus" name="estatus">
                                        <option value="1" {{ $usuario->estatus == 1 ? 'selected' : '' }}>Activo</option>
                                        <option value="2" {{ $usuario->estatus == 2 ? 'selected' : '' }}>Desactivado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="roles" class="form-label">Rol:</label>
                                    <select class="form-select @error('roles') is-invalid @enderror" id="roles" name="roles">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($roles as $rol)
                                            {{-- Si el valor es igual al que tiene en la base de datos mantener seleccionado --}}
                                            <option value="{{ $rol }}" {{ $rol == $usuario->roles[0]->name ? 'selected' : '' }}>{{ $rol }}</option>
                                        @endforeach
                                    </select>

    
                                    @error('roles')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Campo para la contraseña --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Actualizar usuario</button>
                            </div>
                        </div>
                    </form>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
</div>
<!-- container-fluid -->
@endsection
