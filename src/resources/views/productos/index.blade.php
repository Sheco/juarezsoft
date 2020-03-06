@extends('layouts.app')

@section('content')
<div class="container">
<h4>Productos</h4>

<a href="{{route('productos.create')}}" class="btn btn-primary">Crear nuevo</a>
<table class="table table-bordered table-striped table-hover mt-3">
<thead>
    <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Abasto</th>
    </tr>
</thead>
@foreach ($datos as $obj) 
    <tr>
        <td><a href="{{ route('productos.show', [$obj->id]) }}">
                {{$obj->codigo}}</a></td>
        <td>{{$obj->nombre}}</td>
        <td>${{number_format($obj->precio,2)}}</td>
        <td>{{number_format($obj->stock, 0)}}</td>
    </tr>
@endforeach
</table>
</div>
@endsection
