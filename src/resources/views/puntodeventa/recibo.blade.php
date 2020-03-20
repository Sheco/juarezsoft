@extends('layouts.app')
@section('content')
    <div class="container">
<pre>
Ticket#:  {{ $venta->id }}
Fecha:    {{ $venta->fecha_hora->format('Y-M-d H:i') }}
Total:    ${{ number_format($venta->total, 2) }}
Vendedor: {{ $venta->user->name }}

@foreach ($venta->productos as $producto)
    {{ sprintf("%3d", $producto->pivot->cantidad) }} {{$producto->nombre}}
@endforeach

Para facturar, favor de proporcionar el Ticket#, la fecha y el monto total.
</pre>
    <a href="{{ route('puntodeventa.index') }}" class="btn btn-primary">Continuar</a>
    </div>
@endsection
