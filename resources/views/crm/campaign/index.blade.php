@extends('crm.layouts.app')
<style>
    div#tabla_campana_wrapper {
        width: 100%;
    }
</style>
@section('template_title')
    Campañas
@endsection

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">CAMPAÑAS</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">CAMPAÑAS</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card crm-widget py-4 px-3">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>¡Éxito!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>¡Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="d-flex flex-column align-items-end justify-content-center">
                        <a href="{{ route('campaigns.create') }}" class="btn btn-primary waves-effect waves-light mb-3 d-flex align-items-center gap-1">
                            <i class="ri-add-circle-line"></i>
                            {{ __('Agregar campaña') }}
                        </a>
                        <!-- Tables Without Borders -->
                        <table class="table table-middle table-nowrap mb-0" id="tabla_campana">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nombre Campaña</th>
                                    <th scope="col">Descripcion Campaña</th>
                                    <th scope="col">Estatus</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- end row -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        
        $('#tabla_campana').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            bAutoWidth: false,
            ajax: {
                url: "{{ route('campaigns.index') }}",
                type: "GET",
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'nombre_campana', name: 'nombre_campana'},
                {data: 'descripcion_campana', name: 'descripcion_campana'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            columnDefs: [
                {
                    targets: 3,
                    render: function (data, type, row) {
                        if (data == '1') {
                            return '<span class="badge rounded-pill badge-soft-success badge-border">Activo</span>';
                        } else {
                            return '<span class="badge rounded-pill badge-soft-warning badge-border">Inactivo</span>';
                        }
                    }
                }
            ],
            language: idiomaDataTable
        });
        
    });
</script>
@endsection