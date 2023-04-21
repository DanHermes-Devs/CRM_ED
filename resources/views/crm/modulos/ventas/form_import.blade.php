@extends('crm.layouts.app')
<style>

</style>
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">IMPORTAR VENTAS</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">IMPORTAR VENTAS</li>
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

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>¡Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form action="{{ route('ventas.importVentas') }}" id="import-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row align-items-end">
                            <div class="col-12 col-md-5">
                                <label for="ventas_csv" class="form-label">Por favor seleccione el archivo a importar</label>
                                <input type="file" class="form-control" id="ventas_csv" name="ventas_csv" accept=".csv">
                            </div>

                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-primary w-100" id="btn-guardar">Guardar</button>
                            </div>
                        </div>
                    </form>
                
                    <div id="alert" class="alert alert-info d-none" role="alert">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            El proceso de importación puede demorar unos minutos. Por favor, espera.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  $('#import-form').on('submit', function(event) {
    // Muestra el loader y la alerta
    $('#loader').removeClass('d-none');
    $('#alert').removeClass('d-none');

    // Bloqueamos el boton de guardar para evitar que se haga doble click
    $('#btn-guardar').attr('disabled', true);
  });
});
</script>
@endsection