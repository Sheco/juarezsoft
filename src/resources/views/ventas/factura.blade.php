<p>Factura <b>#{{ $factura->id }}</b></p>

JuarezSoft SA de CV, JUSO800505-ABC<br>
Calle 13 # 91, Avenida Juarez<br>
CP 97000<br>
<br>
<table>
<tr>
  <td><b>Fecha:</b></td>
  <td>{{ $factura->created_at->format('Y-m-d H:i') }}</td>
</tr>
<tr>
  <td><b>Razón Fiscal:</b></td>
  <td> {{ $factura->nombre }}</td>
</tr>
<tr>
  <td><b>RFC:</b></td>
  <td> {{ $factura->rfc }}</td>
</tr>
<tr>
  <td><b>Dirección:</b></td>
  <td> {{ $factura->direccion }}</td>
</tr>
<tr>
  <td><b>Codigo Postal:</b></td>
  <td> {{ $factura->codigopostal }}</td>
</tr>
<tr>
  <td><b>Teléfono:</b></td>
  <td> {{ $factura->telefono }}</td>
</tr>
</table>
<br>
<table border cellspacing="0" cellpadding="4">
@foreach ($venta->productos as $producto)
    <tr>
        <td align="right">{{ number_format($producto->pivot->cantidad,0) }}</td>
        <td>{{$producto->nombre }}</td>
        <td align="right">${{ number_format($producto->pivot->precio,2) }}</td>
    </tr>
@endforeach
    <tr>
        <td></td>
        <td align="right"><b>Total:</b></td>
        <td align="right">${{number_format($venta->total(), 2)}}</td>
    </tr>
</table>




