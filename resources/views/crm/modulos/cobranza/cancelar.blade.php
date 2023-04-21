@extends('crm.layouts.app')

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">COBRANZA</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">COBRANZA</li>
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
                    <h1>Cancelar recibo</h1>
                    <p>¿Estás seguro de que deseas cancelar el recibo con ID {{ $recibo->id }}?</p>

                    <form method="post" action="{{ route('cobranza.cancelar', $recibo->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Sí, cancelar recibo</button>
                        <a href="{{ route('cobranza.index') }}" class="btn btn-secondary">No, regresar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
