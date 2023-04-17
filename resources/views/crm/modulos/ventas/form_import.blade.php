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
                    <form action="{{ route('ventas.importVentas') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row align-items-end">
                            <div class="col-12 col-md-5">
                                <label for="ventas_csv" class="form-label">Por favor seleccione el archivo a importar</label>
                                <input type="file" class="form-control" id="ventas_csv" name="ventas_csv" accept=".csv">
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