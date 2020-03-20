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
                    </div>
                </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">Facturar</div>
            <div class="card-body">
                @if (!$venta->estaFacturada())
                <form method="post" action="{{ route('ventas.facturar', $venta) }}" class="container">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Razon físcal</label>
                            {{ Form::text('nombre', null, [
                                'class'=>'form-control']) }}
                        </div>

                        <div class="col-md-6 form-group">
                            <label>RFC</label>
                            {{ Form::text('rfc', null, [
                                'class'=>'form-control']) }}
                        </div>

                        <div class="col-md-6 form-group">
                            <label>Dirección</label>
                            {{ Form::text('direccion', null, [
                                'class'=>'form-control']) }}
                        </div>

                        <div class="col-md-6 form-group">
                            <label>Código postal</label>
                            {{ Form::text('codigopostal', null, [
                                'class'=>'form-control']) }}
                        </div>

                        <div class="col-md-6 form-group">
                            <label>Teléfono</label>
                            {{ Form::text('telefono', null, [
                                'class'=>'form-control']) }}
                        </div>

                        <div class="col-md-6 form-group">
                            <label>E-Mail</label>
                            {{ Form::email('email', null, [
                                'class'=>'form-control']) }}
                        </div>

                        <div class="col-md-12">
                            <input type="submit" value="Facturar" class="btn btn-primary">
                            <a href="javascript:window.history.back()" class="btn btn-secondary">Regresar</a>
                        </div>
                    </div>
                </form>
                @else
                    <p>Esta venta ya esta facturada.</p> 
                    <a target="_blank" href="{{ route('ventas.factura', $venta) }}" class="btn btn-primary">Ver/Imprimir factura</a>
                @endif

            </div>
        </div>

    </div>
@endsection
