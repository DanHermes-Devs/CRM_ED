@component('mail::message')
# Polizas Enviadas

@component('mail::table')
| ID de Póliza | Nueva Póliza | Skilldata | Contact ID | Fecha de Inserción |
|:-------------:|:------------:|:---------:|:----------:|:------------------:|
@foreach ($processedRecordsLog as $record)
| {{$record->nPoliza}} | {{$record->nueva_poliza}} | {{$record->skilldata}} | {{$record->contactId}} | {{$record->ocmdaytosend}} |
@endforeach
@endcomponent

Gracias,
{{ config('app.name') }}
@endcomponent
