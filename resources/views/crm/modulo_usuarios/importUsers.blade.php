@extends('crm.layouts.app')
<style>

</style>
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">IMPORTAR USUARIOS</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">IMPORTAR USUARIOS</li>
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
                    {{-- Descripcion breve de por que debe seleccionar un rol antes de cargar el archivo --}}
                    <div class="alert alert-info" role="alert">
                        <strong>¡Importante!</strong> Por favor seleccione el rol que tendran los usuarios que se importaran.
                    </div>
                    <form action="{{ route('importUsers') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row align-items-end">
                            <div class="col-12 col-md-5">
                                <label for="rol" class="form-label">Rol</label>
                                <select class="form-select @error('rol') is-invalid @enderror" id="rol" name="rol">
                                    <option value="">-- Seleccione un rol --</option>
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol->name }}">{{ $rol->name }}</option>
                                    @endforeach
                                </select>

                                @error('rol')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-5">
                                <label for="users_csv" class="form-label">Por favor seleccione el archivo a importar</label>
                                <input type="file" class="form-control @error('users_csv') is-invalid @enderror" id="users_csv" name="users_csv" accept=".csv">

                                @error('users_csv')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection