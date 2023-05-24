@extends('crm.layouts.app')
<style>
    div#tabla_usuarios_wrapper {
        width: 100%;
    }
</style>
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">USUARIOS</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">USUARIOS</li>
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
                            <div class="d-flex gap-3">
                                @can('crear-usuarios')
                                    <a href="{{ route('formImport') }}" class="btn btn-warning waves-effect waves-light mb-3 d-flex align-items-center gap-1">
                                        <i class="ri-file-excel-2-line"></i>
                                        Importar Usuarios
                                    </a>
                                @endcan
                                @can('crear-usuarios')
                                    <a href="{{ route('crear-usuario') }}" class="btn btn-primary waves-effect waves-light mb-3 d-flex align-items-center gap-1">
                                        <i class="ri-add-circle-line"></i>
                                        Agregar usuario
                                    </a>
                                @endcan
                            </div>
                            <!-- Tables Without Borders -->
                            <table class="table table-middle table-nowrap mb-0" id="tabla_usuarios">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Rol/Perfil</th>
                                        <th scope="col">Correo Electrónico</th>
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
            
            $('#tabla_usuarios').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bAutoWidth: false,
                ajax: {
                    url: "{{ route('usuarios') }}",
                    type: "GET",
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'usuario', name: 'usuario'},
                    {data: 'name', name: 'name'},
                    {data: 'roles', name: 'roles'},
                    {data: 'email', name: 'email'},
                    {data: 'estatus', name: 'estatus'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                columnDefs: [
                    {
                        target: 3,
                        render: function(data, type, row) {
                            // Hacemos un foreach a row.roles para obtener el nombre de cada rol
                            let roles = '';
                            row.roles.forEach(rol => {
                                roles += rol.name + ', ';
                            });
                            // Eliminamos la ultima coma y espacio
                            roles = roles.slice(0, -2);
                            // Lo pintamos en un badge
                            return `<span class="badge rounded-pill badge-soft-primary badge-border text-primary">${roles}</span>`;
                        }
                    },
                    {
                        targets: 5,
                        render: function(data, type, row) {
                            console.log(row)
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
