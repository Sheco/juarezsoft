@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Filtros</div>
        <div class="card-body">
          <form method="get" action="{{ route('ventas.vendedores') }}">

            <div class="form-group">
              <label for="fecha_inicio">Fecha:</label>
              <input type="date" name="fecha_inicio" id="fecha_inicio" 
                value="{{ $fecha_inicio }}" class="form-control">
            <small>Seleccionar cualquier día del mes a reportar</small>
            </div>

            <input type="submit" value="Aplicar" class="btn btn-primary">
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <table class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Ventas</th>
              <th>Total</th>
              <th>Comisión</th>
            </tr>
          </thead>
          @foreach ($datos as $dato)
            <tr>
              <td>{{ $dato->nombre }}</td>
              <td align="right">{{ number_format($dato->cantidad, 0) }}</td>
              <td align="right">${{ number_format($dato->total, 2) }}</td>
              <td align="right">${{ number_format($dato->total*0.02, 2) }}</td>
            </tr>
          @endforeach
        </table>

      </div>
    </div>
  </div>
</div>
@endsection


