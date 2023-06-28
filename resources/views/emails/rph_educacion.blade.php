@component('mail::message')
# RPH | UNIVERSIDAD INSURGENTES

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
        @if (!empty($processedData) && is_array($processedData))
            @for ($i = 0; $i < count($processedData['keys']); $i++)
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: .75rem;">{{$processedData['keys'][$i]}}</td>
                    <td style="padding: .75rem;">{{$processedData['leads'][$processedData['keys'][$i]]}}</td>
                    <td style="padding: .75rem;">{{$processedData['llamadas'][$processedData['keys'][$i]]}}</td>
                    <td style="padding: .75rem;">{{number_format(($processedData['llamadas'][$processedData['keys'][$i]]/$processedData['leads'][$processedData['keys'][$i]])*100,0).'%'}}</td>
                    <td style="padding: .75rem;">{{$processedData['preventas'][$processedData['keys'][$i]]}}</td>
                    <td style="padding: .75rem;">{{number_format(($processedData['preventas'][$processedData['keys'][$i]]/$processedData['leads'][$processedData['keys'][$i]])*100,1).'%'}}</td>
                    <td style="padding: .75rem;">{{$processedData['ventas'][$processedData['keys'][$i]]}}</td>
                </tr>
            @endfor
        @endif
    </tbody>
</table>

{{ config('app.name') }}
@endcomponent
