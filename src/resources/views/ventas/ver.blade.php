@extends("layouts.app")
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Venta {{$venta->id}}</div>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            <strong>Fecha</strong>
                            <div>{{ $venta->fecha_hora->format("Y/M/d H:ia") }}</div>
                        </div>
                        <div class="col-4">
                            <strong>Vendedor</strong>
                            <div>{{ $venta->user->name }}</div>
                        </div>
                        <div class="col-4">
                            <strong>Total</strong>
                            <div>${{ number_format($venta->total, 2) }}</td>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                    <table class="table table-bordered table-striped table-hover mt-3">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</td>
                        </tr>
                    </thead>
                    @foreach ($venta->productos as $producto)
                        <tr>
                            <td>{{ $producto->nombre }}</td>
                            <td align="right">{{ number_format($producto->pivot->cantidad, 0) }}</td>
                            <td align="right">${{ number_format($producto->pivot->precio, 2) }}</td>
                            <td align="right">${{ number_format($producto->pivot->cantidad * $producto->pivot->precio, 2) }}</td>
                        </tr>
                    @endforeach

                    </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <a href="javascript:window.history.back()" class="btn btn-secondary">Regresar</a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
