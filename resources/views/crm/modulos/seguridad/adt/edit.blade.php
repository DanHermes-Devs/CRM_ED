@extends('crm.layouts.app')

@section('content')
<style>
    #status {
    display: block;
    width: 100%;
    position: relative;
    margin:0;
    left: 0;
    right:0;
    top:0;
}
</style>
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">EDITAR {{ $adt->cliente_nombre }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">EDITAR {{ $adt->cliente_nombre }}</li>
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
                        {{-- PARA MANDAR MENSAJE DE GUARDADO --}}
                        @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>¡Éxito!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <h4 class="text-left mb-3">EDITAR {{ $adt->cliente_nombre }}</h4>
                            <a href="{{ route('seguridad-adt.index') }}" class="btn btn-info mb-3">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-arrow-left-line"></i>
                                    Regresar
                                </div>
                            </a>
                        </div>

                        {{-- Formulario para agregar nuevo usuario --}}
                        <form action="{{ route('seguridad-adt.update', $adt->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')

                            {{-- dd($coti) --}}

                            <button type="submit" class="btn btn-primary waves-effect waves-light mb-3">Actualizar</button>
                        </form>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div>
    <!-- container-fluid -->
    <script>
        $(document).ready(function() {
            $('#confirmed_account').on('change', function() {
                var selectedValue = $(this).val();
                if (selectedValue === "NO") {
                    // $("#status option[value='Alumno']").prop("disabled", true);
                    $("#account_UIN").prop("readonly", true);
                    $("#account_UIN").val('');
                } else {
                    // $("#status option[value='Alumno']").prop("disabled", false); // Desbloquear opción
                    $("#account_UIN").prop("readonly", false);
                }
            });
            $('.form-check-input').change(function() {
                var checked = $('.checkbox:checked').length;
                if (checked === 8) {
                    $("#status option[value='Alumno']").prop("disabled", false);
                    $("#account_UIN").prop("readonly", false);
                } else {
                    $("#status option[value='Alumno']").prop("disabled", true);
                    $("#account_UIN").prop("readonly", true);
                    $("#account_UIN").val('');
                }
            });
            $("#mastercbox").change(function() {
                var isChecked = $(this).prop("checked");
                $(".depencbox").prop("checked", isChecked);
            });
        });
    </script>
@endsection
