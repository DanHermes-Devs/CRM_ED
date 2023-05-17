@extends('crm.layouts.app')

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">CREAR CRON JOB</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">CREAR CRON JOB</li>
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
                    <form action="{{ route('store-cronjob') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="name_cronJob" class="form-label">Nombre de Cron Job</label>
                            <input type="text" name="name_cronJob" id="name_cronJob" class="form-control @error ('name_cronJob') is-invalid @enderror" placeholder="Nombre de Cron Job">

                            @error('name_cronJob')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="skilldata" class="form-label">Nombre Skilldata (Campaña)</label>
                            <input type="text" name="skilldata" id="skilldata" class="form-control @error ('skilldata') is-invalid @enderror" placeholder="Nombre Skilldata (Campaña)">

                            @error('skilldata')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        
                        <div class="mb-3">
                            <label for="aseguradora" class="form-label">Aseguradora</label>
                            <select id="aseguradora" name="aseguradora" class="form-select">
                                <option>-- Selecciona una Aseguradora --</option>
                                @foreach ($aseguradoras as $aseguradora)
                                    <option value="{{ $aseguradora->nombre_aseguradora }}">{{ $aseguradora->nombre_aseguradora }}</option>
                                @endforeach
                            </select>

                            @error('aseguradora')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="idload" class="form-label">ID Load</label>
                            <input type="text" pattern="\d+" name="idload" id="idload" class="form-control @error ('idload') is-invalid @enderror" placeholder="ID Load">

                            @error('idload')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="motor_b" class="form-label">Motor B</label>
                            <input type="text" name="motor_b" id="motor_b" class="form-control @error ('motor_b') is-invalid @enderror" placeholder="Motor B">

                            @error('motor_b')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="motor_c" class="form-label">Motor C</label>
                            <input type="text" name="motor_c" id="motor_c" class="form-control @error ('motor_c') is-invalid @enderror" placeholder="Motor C">

                            @error('motor_c')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="frecuency" class="form-label">Frecuencia de ejecución</label>
                            <select name="frequency" id="frecuency" class="form-select @error ('frequency') is-invalid @enderror">
                                <option>-- Selecciona una opción --</option>
                                <option value="everyMinute">everyMinute - Cada minuto</option>
                                <option value="everyFiveMinutes">everyFiveMinutes - Cada 5 minutos</option>
                                <option value="everyTenMinutes">everyTenMinutes - Cada 10 minutos</option>
                                <option value="everyFifteenMinutes">everyFifteenMinutes - Cada 15 minutos</option>
                                <option value="everyThirtyMinutes">everyThirtyMinutes - Cada 30 minutos</option>
                                <option value="hourly">hourly - Cada hora</option>
                                <option value="daily">daily - Cada día</option>
                                <option value="dailyAt">dailyAt - Cada día a una hora específica (requiere una hora adicional)</option>
                                <option value="twiceDaily">twiceDaily - Dos veces al día (requiere dos horas adicionales)</option>
                                <option value="weekly">weekly - Cada semana</option>
                                <option value="monthly">monthly - Cada mes</option>
                                <option value="quarterly">quarterly - Cada trimestre</option>
                                <option value="yearly">yearly - Cada año</option>
                            </select>

                            @error('frequency')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar CronJob</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection