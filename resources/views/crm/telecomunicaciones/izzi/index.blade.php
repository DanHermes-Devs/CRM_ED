@extends('crm.layouts.app')
<style>
    div#tabla_telecom_wrapper {
        width: 100%;
    }
</style>
@section('template_title')
    Telecomunicaciones
@endsection

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">TELECOMUNICACIONES</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">TELECOMUNICACIONES</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card crm-widget py-4 px-3">
                <div class="card-body">
                    <table id="tabla_telecom" class="table table-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Status</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        
        $('#tabla_telecom').DataTable({
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