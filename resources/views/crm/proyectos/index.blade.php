@extends('crm.layouts.app')
<style>
    div#tabla_proyectos_wrapper {
        width: 100%;
    }
</style>
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">PROYECTOS</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">PROYECTOS</li>
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
                        <div class="d-flex flex-column align-items-end justify-content-center">
                            @can('crear-proyectos')
                                <a href="{{ route('proyectos.create') }}" class="btn btn-primary waves-effect waves-light mb-3 d-flex align-items-center gap-1">
                                    <i class="ri-add-circle-line"></i>
                                    Agregar proyecto
                                </a>
                            @endcan
                            <!-- Tables Without Borders -->
                            <table class="table table-middle table-nowrap mb-0" id="tabla_proyectos">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Proyecto</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Estatus</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div><!-- end row -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div>
    <!-- container-fluid -->

    <script>
        $(document).ready(function() {
            
            $('#tabla_proyectos').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bAutoWidth: false,
                ajax: {
                    url: "{{ route('proyectos.index') }}",
                    type: "GET",
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'proyecto', name: 'proyecto'},
                    {data: 'descripcion', name: 'descripcion'},
                    {data: 'estatus', name: 'estatus'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                columnDefs: [
                    {
                        targets: 3,
                        render: function(data, type, row) {
                            if (row.estatus == 1) {
                                return '<span class="badge rounded-pill badge-soft-success badge-border">Activo</span>';
                            } else {
                                return '<span class="badge rounded-pill badge-soft-warning badge-border">Desactivado</span>';
                            }
                            return `<span class="badge rounded-pill badge-soft-${row.estatus == 'Activo' ? 'success' : 'danger'} badge-border text-${row.estatus == 'Activo' ? 'success' : 'danger'}">${row.estatus}</span>`;
                        }
                    },
                ],
                language: idiomaDataTable
            });
            
        });
    </script>
@endsection
