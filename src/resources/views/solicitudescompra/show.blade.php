@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Solicitud de compra</div>
        <div class="card-body container"><div class="row">
            <div class="form-group col-md-6">
                <label><b>Producto</b></label>
                <div class="form-control">
                    {{$solicitud->producto->nombre}}
                </div>
            </div>

            <div class="form-group col-md-6">
                <label><b>Proveedor</b></label>
                <div class="form-control">
                    {{$solicitud->proveedor->nombre}}
                </div>
            </div>

            <div class="form-group col-md-6">
                <label><b>Cantidad</b></label>
                <div class="form-control">
                    {{number_format($solicitud->cantidad, 0)}}
                </div>
            </div>

            <div class="form-group col-md-6">
                <label><b>Precio</b></label>
                <div class="form-control">
                    ${{number_format($solicitud->precio(), 2)}}
                </div>
            </div>

            <div class="form-group col-md-6">
                <label><b>Total</b></label>
                <div class="form-control">
                    ${{number_format($solicitud->cantidad*$solicitud->precio(), 2)}}
                </div>
            </div>

            <div class="form-group col-md-6">
                <label><b>Status</b></label>
                <div class="form-control">
                    {{$solicitud->status}}
                </div>
            </div>

            <div class="form-group col-md-6">
                @if ($solicitud->status==="nueva")
                    <a href="{{ route('solicitudescompra.pagada', $solicitud) }}" class="btn btn-primary">Pagar</a>
                    <a href="{{ route('solicitudescompra.cancelar', $solicitud) }}" class="btn btn-danger">Cancelar solicitud</a>
                @elseif ($solicitud->status==="pagada")
                    <a href="{{ route('solicitudescompra.surtida', $solicitud) }}" class="btn btn-info text-light">Marcar como surtida</a>
                @endif
                <a href="{{ route('solicitudescompra.index') }}" class="btn btn-secondary">Regresar a la lista</a>

            
        </div></div>
</div>
@endsection
