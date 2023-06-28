@component('mail::message')
# Pólizas Enviadas - Volcado a Motor A

<table style="width: 900px; margin-bottom: 2rem; border-collapse: collapse;">
    <thead>
        <tr style="border-bottom: 1px solid #dee2e6;">
            <th style="padding: .75rem; text-align: left!important;">Póliza (nPoliza)</th>
            <th style="padding: .75rem; text-align: left!important;">Nueva Póliza (nueva_poliza)</th>
            <th style="padding: .75rem; text-align: left!important;">Skilldata</th>
            <th style="padding: .75rem; text-align: left!important;">Fecha de Inserción</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($processedRecordsLog as $record)
        <tr style="border-bottom: 1px solid #dee2e6;">
            <td style="padding: .75rem;">{{$record['nPoliza']}}</td>
            <td style="padding: .75rem;">{{$record['nueva_poliza']}}</td>
            <td style="padding: .75rem;">{{$record['skilldata']}}</td>
            <td style="padding: .75rem;">{{$record['ocmdaytosend']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ config('app.name') }}
@endcomponent
