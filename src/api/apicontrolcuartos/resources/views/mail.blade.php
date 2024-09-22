<table style="width: 100%; text-align:center; border: 0;">
    <tr>
        <td>
        **** CORTE RESUMIDO ****
        </td>
    </tr>
    <tr>
        <td>
        {{ $nombreEmpresa }}
        </td>
    </tr>
    <tr>
        <td>
        {{ $fechaTicket }}
        </td>
    </tr>
    <tr>
        <td>
        Ultimo Corte: {{ $fechaTicket }}
        </td>
    </tr>
    <tr>
        <td>
        Fechas de Corte: <br> {{ $fechaInicioSel }} al {{ $fechaFinSel }}
        </td>
    </tr>
    <tr>
        <td>
        Rango de Folios {{ $folioInicio }} - {{ $folioFin }}
        </td>
    </tr>
    <tr>
        <td>
        Alquileres Contabilizados: {{ $totalTickets }}
        </td>
    </tr>
    <tr>
        <td>
        Importe Total: $ {{ $MontoTotal }}
        </td>
    </tr>
    <tr>
        <td>
        ** TOTAL_DEL_CORTE_: $ {{ $MontoTotal }}
        </td>
    </tr>
</table>
<br>
<br>
<h2>Detalle de Corte</h2>
<table style="width: 100%; text-align: center;">
<tr>
    <td>
    <table style="border: 1;">
    <tr>
        <td>Folio</td>
        <td>Descripcion</td>
        <td>Fecha Entrada</td>
        <td>Fecha Salida</td>
        <td>Total Minutos</td>
        <td>Monto Pagado</td>
    </tr>
    @foreach ($data as $item)
        <tr>
            <td>
                {{ $item->folio }}
            </td>
            <td>
                {{ $item->descripcion_alquiler }}
            </td>
            <td>
                {{ $item->fecha_entrada }}
            </td>
            <td>
                {{ $item->fecha_salida }}
            </td>
            <td style="text-align: right;">
                {{ $item->total_minutos }}
            </td>
            <td style="text-align: right;">
                $ {{ $item->total_pagado }}
            </td>
        </tr>
    @endforeach
</table>
    </td>
</tr>
</table>

