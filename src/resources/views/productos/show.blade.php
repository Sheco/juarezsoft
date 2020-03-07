@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Datos del producto</div>
        <div class="card-body">
            <div class="container"><div class="row">
            <div class="form-group col-md-6">
                <label><b>Nombre</b></label>
                <div>{{$obj->nombre}}</div>
            </div>
            <div class="form-group col-md-6">
                <label><b>Código</b></label>
                <div>{{$obj->codigo}}</div>
            </div>
            <div class="form-group col-md-6">
                <label><b>Precio</b></label>
                <div>{{number_format($obj->precio, 2)}}</div>
            </div>
            <div class="form-group col-md-6">
                <label><b>Abasto</b></label>
                <div>{{$obj->stock}}</div>
            </div>
            <div class="form-group col-md-6">
                <label><b>Departamento</b></label>
                <div>{{$obj->departamento->nombre}}</div>
            </div>
            <div class="form-group col-md-6">
                <label><b>Frecuencia de compras</b></label>
                <div>{{$obj->frecuenciaCompras}}</div>
            </div>

            <div class="col-md-12">
                <a href="{{ route('productos.edit', $obj->id) }}" class="btn btn-primary">Editar</a>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">Regresar a la lista</a>
            </div>
            </div></div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">Proveedores</div>
        <div class="card-body">
            <a href="{{ route("productos.addProveedor", $obj) }}" class="btn btn-success">Agregar proveedor</a>
            <table class="table table-bordered table-striped mt-3">
                <thead><tr>
                    <th>Nombre</th>
                    <th class="text-right">Precio de compra</th>
                    <th></th>
                </tr></thead>
                @foreach ($obj->proveedores->sortBy('pivot.precio') as $proveedor)
                <tr>
                    <td>{{ $proveedor->nombre }}</td>
                    <td align="right">${{ number_format($proveedor->pivot->precio, 2) }}</td>
                    <td>
                        <a href="{{ route('productos.comprar', [ 
                            $obj,
                            $proveedor
                        ]) }}" class="btn btn-info text-light" style="float: left; margin-right: 1em;">Comprar</a>
                        <form method="post" action="{{ route('productos.delProveedor', $obj) }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="proveedor_id" value="{{ $proveedor->id }}">
                            <input type="submit" value="Borrar" class="btn btn-danger">
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Gráficas</div>
                <div class="body"> <canvas id="ventas"></canvas></div>
            </div>
        </div>
    </div>
</div>
<script>
    window.onload = function() {
        window.stats = new Chart(
            document.getElementById('ventas').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($fechas) !!},
                datasets: [{
                    label: 'Unidades vendidas',
                    yAxisID: 'unidades',
                    fill: false,
                    data: {!! json_encode($vendidos) !!}
                },{
                    label: 'Total',
                    yAxisID: 'total',
                    backgroundColor: 'rgb(54, 162, 235)',
                    borderColor: 'rgb(54, 162, 235)',
                    fill: false,
                    data: {!! json_encode($total) !!}
                }]
            },
            options: {
                responsive: true,
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: { 
                            display: true,
                            labelString: 'Fecha'
                        }
                    }],
                    yAxes: [{
                        id: 'total',
                        position: 'left',
                        type: 'linear',
                        ticks: {
                            callback: function(value, index, values) {
                                return '$'+ Intl.NumberFormat().format((value))
                            }
                        }
                    }, {
                        id: 'unidades',
                        position: 'right',
                        type: 'linear',
                    }]
                }
            }
        })
    }
        
</script>
@endsection
