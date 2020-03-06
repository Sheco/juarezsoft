@extends('layouts.app')
@section('content')
    <form method="post" action="{{ route('productos.comprarGuardar', [
        $producto, $proveedor
    ]) }}">
        @csrf
    <div class="container">
        <div class="card">
            <div class="card-header">Comprar {{ $producto->nombre }} de {{ $proveedor->nombre }}</div>
            <div class="card-body"><div class="container"><div class="row">
                <div class="col-md-12">
                Crear una solicitud de compra de <b>{{$producto->nombre}}</b> del proveedor <b>{{ $proveedor->nombre }}</b>
                </div>
                <div class="form-group col-md-6">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" name="cantidad" id="cantidad" class="form-control">
                </div>

                <div class="form-group col-md-2">
                    <label>&nbsp;</label>
                    <input type="submit" value="Comprar" class="btn btn-primary form-control">
                </div>
            </div></div></div>
        </div>
    </div>
    </form>
@endsection
