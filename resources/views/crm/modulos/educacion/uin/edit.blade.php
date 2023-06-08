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
                            <input type="hidden" name="client_modality" id="client_modality" value="{{ $coti->client_modality }}">
                            <h5><label for="documents_portal" class="form-label">Documentos cargados:</label></h5>

                            @if ($coti->client_modality == 'PRESENCIAL')
                                <h5 class="alert alert-success text-center"><label for="documents_portal" class="form-label">LOS DOCUMENTOS NO SON NECESARIOS DEBIDO A QUE YA FUE COBRADA O ES ALUMNO</label></h5>
                            @else
                            <div class="row mb-5">
                                <div class="col-xl-3 mt-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="SI" name="birth_certifcate"  id="birth_certifcate" {{ $coti->birth_certifcate == 'SI' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="birth_certifcate">Acta de nacimiento</label>
                                    </div>
                                </div>
                                <div class="col-xl-3 mt-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="SI" name="curp_certificate" id="curp_certificate" {{ $coti->curp_certificate == 'SI' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="curp_certificate">Curp</label>
                                    </div>
                                </div>
                                <div class="col-xl-3 mt-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="SI" name="ine_certifcate" id="ine_certifcate" {{ $coti->ine_certifcate == 'SI' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ine_certifcate">INE</label>
                                    </div>
                                </div>
                                <div class="col-xl-3 mt-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="SI" name="inscripcion_certificate" id="inscripcion_certificate" {{ $coti->inscripcion_certificate == 'SI' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inscripcion_certificate">Solicitud de inscripción</label>
                                    </div>
                                </div>
                                <div class="col-xl-3 mt-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="SI" name="domicilio_certifcate" id="domicilio_certifcate" {{ $coti->domicilio_certifcate == 'SI' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="domicilio_certifcate">Comprobante de domicilio</label>
                                    </div>
                                </div>
                                <div class="col-xl-3 mt-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="SI" name="estudio_certifcate" id="estudio_certifcate" {{ $coti->estudio_certifcate == 'SI' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="estudio_certifcate">Certificado de estudios nivel medio- titulo</label>
                                    </div>
                                </div>
                                <div class="col-xl-3 mt-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="SI" name="cotizacion_certifcate" id="cotizacion_certifcate" {{ $coti->cotizacion_certifcate == 'SI' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cotizacion_certifcate">Cotización</label>
                                    </div>
                                </div>
                                <div class="col-xl-3 mt-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="SI" name="pago_certifcate" id="pago_certifcate" {{ $coti->pago_certifcate == 'SI' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pago_certifcate">Comprobante de pago</label>
                                    </div>
                                </div>
                            </div>
                            @endif


                            <div class="row">
                                <div class="mb-3">
                                    <label for="confirmed_account" class="form-label">Cuenta Confirmada:</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="confirmed_account" name="confirmed_account">
                                        <option value="">Seleccione una opción</option>
                                        <option value="SI" {{ $coti->confirmed_account == 'SI' ? 'selected' : '' }}>SI</option>
                                        <option value="NO" {{ $coti->confirmed_account == 'NO' ? 'selected' : '' }}>NO</option>
                                    </select>

                                    @error('documents_portal')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>

                                {{-- <div class="mb-3">
                                    <label for="status" class="form-label">Estatus:</label>
                                    <select class="form-select @error($coti->status) is-invalid @enderror" id="status" name="status" style="">
                                        <option value="">Seleccione una opción</option>
                                        <option value="Cotización" {{ $coti->status == 'Cotización' ? 'selected' : '' }}>Cotizacíón</option>
                                        <option value="Cobrada" {{ $coti->status == 'Cobrada' ? 'selected' : '' }}>Cobrada</option>
                                        <option value="Cobrada con documentos incompletos" {{ $coti->status == 'Cobrada con documentos incompletos' ? 'selected' : '' }}>Cobrada con documentos incompletos</option>
                                        <option value="Alumno" {{ $coti->status == 'Alumno' ? 'selected' : '' }}>Alumno</option>
                                    </select>

                                    @error('status')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div> --}}

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
        });
    </script>
@endsection
