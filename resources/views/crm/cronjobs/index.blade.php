@extends('crm.layouts.app')
<style>
    div#tabla_cronjobs_wrapper {
        width: 100%;
    }
</style>
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">CRON JOBS</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">CRON JOBS</li>
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
                        <a href="{{ route('crear-cronjob') }}" class="btn btn-primary waves-effect waves-light mb-3 d-flex align-items-center gap-1">
                            <i class="ri-add-circle-line"></i>
                            Agregar CronJob
                        </a>
                        <!-- Tables Without Borders -->
                        <table class="table table-middle table-nowrap mb-0" id="tabla_cronjobs">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nombre CronJOb</th>
                                    <th scope="col">Skilldata</th>
                                    <th scope="col">Frecuencia de ejecución</th>
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
        
        $('#tabla_cronjobs').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            bAutoWidth: false,
            ajax: {
                url: "{{ route('cronjobs.index') }}",
                type: "GET",
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name_cronJob', name: 'name_cronJob'},
                {data: 'skilldata', name: 'skilldata'},
                {data: 'frequency', name: 'frequency'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            language: idiomaDataTable
        });
        
    });
</script>
@endsection