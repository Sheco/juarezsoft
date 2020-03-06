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
                <div>{{$obj->precio}}</div>
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
            TODO
        </div>
    </div>
</div>
@endsection
