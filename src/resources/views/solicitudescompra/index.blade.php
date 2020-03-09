@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Solicitudes de compra</div>
        <div class="card-body container"><div class="row">
                <div class="col-md-2">
                    <div class="list-group">
                        @foreach ($statuses as $_status)
                            <a class="list-group-item{{ $status==$_status? " active": ""}}" href="{{route('solicitudescompra.index.status', $_status)}}">{{$_status}}</a>
                        @endforeach
                    </div>
                </div>
                <div class="col">
            <table class="table table-bordered table-striped">
            <thead><tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Proveedor</th>
                <th>Fecha</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
            </tr></thead>
            @foreach ($lista as $row)
            <tr>
                <td><a href="{{route('solicitudescompra.show', $row)}}">{{$row->id}}</a></td>
                <td>{{$row->producto->nombre}}</td>
                <td>{{$row->proveedor->nombre}}</td>
                <td>{{$row->$columnaFecha}}</td>
                <td align="right">{{number_format($row->cantidad, 0)}}</td>
                <td align="right">${{number_format($row->precio(), 2)}}</td>
                <td align="right">${{number_format($row->cantidad*$row->precio(), 2)}}</td>
            </tr>
            @endforeach
                </div>
        </div></div>
    </div>
</div>
@endsection
