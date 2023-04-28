@extends('crm.layouts.app')

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">EDITAR CRON JOB</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">EDITAR CRON JOB</li>
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
                    <form action="{{ route('actualizar-cronjob', $cronJob->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name_cronJob" class="form-label">Nombre de Cron Job</label>
                            <input type="text" name="name_cronJob" id="name_cronJob" class="form-control @error ('name_cronJob') is-invalid @enderror" value="{{ $cronJob->name_cronJob }}">

                            @error('name_cronJob')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="skilldata" class="form-label">Nombre Skilldata</label>
                            <input type="text" name="skilldata" id="skilldata" class="form-control @error ('skilldata') is-invalid @enderror" value="{{ $cronJob->skilldata }}">

                            @error('skilldata')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="frecuency" class="form-label">Frecuencia de ejecución</label>
                            <select name="frequency" id="frecuency" class="form-select @error ('frequency') is-invalid @enderror">
                                <option>-- Selecciona una opción --</option>
                                <option value="everyMinute" {{ $cronJob->frequency == 'everyMinute' ? 'selected' : '' }}>everyMinute - Cada minuto</option>
                                <option value="everyFiveMinutes" {{ $cronJob->frequency == 'everyFiveMinutes' ? 'selected' : '' }}>everyFiveMinutes - Cada 5 minutos</option>
                                <option value="everyTenMinutes" {{ $cronJob->frequency == 'everyTenMinutes' ? 'selected' : '' }}>everyTenMinutes - Cada 10 minutos</option>
                                <option value="everyThirtyMinutes" {{ $cronJob->frequency == 'everyThirtyMinutes' ? 'selected' : '' }}>everyThirtyMinutes - Cada 30 minutos</option>
                                <option value="hourly" {{ $cronJob->frequency == 'hourly' ? 'selected' : '' }}>hourly - Cada hora</option>
                                <option value="daily" {{ $cronJob->frequency == 'daily' ? 'selected' : '' }}>daily - Cada día</option>
                                <option value="weekly" {{ $cronJob->frequency == 'weekly' ? 'selected' : '' }}>weekly - Cada semana</option>
                                <option value="monthly" {{ $cronJob->frequency == 'monthly' ? 'selected' : '' }}>monthly - Cada mes</option>
                                <option value="quarterly" {{ $cronJob->frequency == 'quarterly' ? 'selected' : '' }}>quarterly - Cada trimestre</option>
                                <option value="yearly" {{ $cronJob->frequency == 'yearly' ? 'selected' : '' }}>yearly - Cada año</option>
                            </select>

                            @error('frequency')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Actualizar CronJob</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection