@extends('layouts.app')
@section('content')
<form method="post" action="{{ route("productos.storeProveedor", $obj) }}">
    @csrf
<div class="container">
    <div class="card">
        <div class="card-header">Agregar proveedor al producto {{$obj->nombre}}</div>
        <div class="body"><div class="container">
    <div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="proveedor_id">Proveedor</label>
            {{ Form::select('proveedor_id', 
                $proveedores->pluck('nombre', 'id'), 
                null, [
                    'class'=>'form-control',
                    'id'=>'proveedor_id',
                ]) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="precio">Precio</label>
            {{ Form::text('precio', null, [
                'class'=>'form-control'
                ]) }}
        </div>
    </div>
    <div class="col-md-6">
        <input type="submit" value="Agregar" class="btn btn-success">
        <a href="{{ route("productos.show", $obj) }}" class="btn btn-secondary">Cancelar</a>
    </div>
    </div>
    </div>
    </div>
</div>
</div>
</form>
@endsection
