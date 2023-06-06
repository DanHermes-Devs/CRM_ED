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
                    <h4 class="mb-sm-0">EDITAR {{ $coti->client_name }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">EDITAR {{ $coti->client_name }}</li>
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
                            <h4 class="text-left mb-3">EDITAR {{ $coti->client_name }}</h4>
                            <a href="{{ route('educacion-uin.index') }}" class="btn btn-info mb-3">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-arrow-left-line"></i>
                                    Regresar
                                </div>
                            </a>
                        </div>

                        {{-- Formulario para agregar nuevo usuario --}}
                        <form action="{{ route('educacion-uin.update', $coti->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-3">
                                    <label for="documents_portal" class="form-label">Documentos cargados:</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="documents_portal" name="documents_portal">
                                        <option value="">Seleccione una opción</option>
                                        <option value="SI" {{ $coti->documents_portal == 'SI' ? 'selected' : '' }}>SI</option>
                                        <option value="NO" {{ $coti->documents_portal == 'NO' ? 'selected' : '' }}>NO</option>
                                    </select>

                                    @error('documents_portal')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">status:</label>
                                    <select class="form-select @error($coti->status) is-invalid @enderror" id="status" name="status" style="">
                                        <option value="">Seleccione una opción</option>
                                        <option value="Cotización" {{ $coti->status == 'Cotización' ? 'selected' : '' }}>Cotizacíón</option>
                                        <option value="Cobrada" {{ $coti->status == 'Cobrada' ? 'selected' : '' }}>Cobrada</option>
                                        <option value="Cobrada sin documentos" {{ $coti->status == 'Cobrada sin documentos' ? 'selected' : '' }}>Cobrada sin documentos</option>
                                        <option value="Alumno" {{ $coti->status == 'Alumno' ? 'selected' : '' }}>Alumno</option>
                                    </select>

                                    @error('status')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="account_UIN" class="form-label">Cuenta UIN:</label>
                                    <input type="text" class="form-control @error('account_UIN') is-invalid @enderror" id="account_UIN" name="account_UIN" value="{{ $coti->account_UIN != '' ? $coti->account_UIN : '' }}" >

                                    @error('account_UIN')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
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
            $('#documents_portal').on('change', function() {
            var selectedValue = $(this).val();

            if (selectedValue === "NO") {
            $("#status option[value='Alumno']").prop("disabled", true); // Bloquear opción
            $("#account_UIN").prop("readonly", true);
            $("#account_UIN").val('');
            } else {
            $("#status option[value='Alumno']").prop("disabled", false); // Desbloquear opción
            $("#account_UIN").prop("readonly", false);
            }
        });
        });
    </script>
@endsection
