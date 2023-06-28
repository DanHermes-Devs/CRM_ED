@component('mail::message')
# Reporte por hora Uin

<table style="width: 900px; margin-bottom: 2rem; border-collapse: collapse;">
    <thead>
        <tr style="border-bottom: 1px solid #dee2e6;">
            <th style="padding: .75rem; text-align: left!important;">Hora</th>
            <th style="padding: .75rem; text-align: left!important;">Leads</th>
            <th style="padding: .75rem; text-align: left!important;">Llamadas</th>
            <th style="padding: .75rem; text-align: left!important;">Contactación</th>
            <th style="padding: .75rem; text-align: left!important;">Cotizaciones</th>
            <th style="padding: .75rem; text-align: left!important;">Ratio</th>
            <th style="padding: .75rem; text-align: left!important;">Cobradas</th>

        </tr>
    </thead>
    <tbody>

    @if (!empty($leads) && is_array($leads))
        @for ($i = 0; $i < count($leads); $i++)
            <td style="padding: .75rem;">{{$leads[$i]}}</td>
            <td style="padding: .75rem;">{{$leads[$i]}}</td>
            <td style="padding: .75rem;">{{$llamadas[$i]}}</td>
            <td style="padding: .75rem;">{{$llamadas[$i]/$leads[$i]}}</td>
            <td style="padding: .75rem;">{{$preventas[$i]}}</td>
            <td style="padding: .75rem;">{{$preventas[$i]/$leads[$i]}}</td>
            <td style="padding: .75rem;">{{$ventas[$i]}}</td>
        @endfor
    @endif
    </tbody>
</table>

{{ config('app.name') }}
@endcomponent
