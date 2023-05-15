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
                            <input type="text" name="name_cronJob" id="name_cronJob" class="form-control" placeholder="Nombre de Cron Job">
                        </div>

                        <div class="mb-3">
                            <label for="skilldata" class="form-label">Nombre Skilldata (Campaña)</label>
                            <input type="text" name="skilldata" id="skilldata" class="form-control" placeholder="Nombre Skilldata">
                        </div>
                        
                        <div class="mb-3">
                            <label for="idload" class="form-label">ID Load</label>
                            <input type="text" pattern="\d+" name="idload" id="idload" class="form-control" placeholder="ID Load">
                        </div>

                        <div class="mb-3">
                            <label for="frecuency" class="form-label">Frecuencia de ejecución</label>
                            <select name="frequency" id="frecuency" class="form-select">
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
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar CronJob</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection