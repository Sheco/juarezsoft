@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Filtros</div>
        <div class="card-body">
          <form method="get" action="{{ route('users.nomina') }}">

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

      <div class="col-md-8 mt-3">
        <table class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th>Puesto</th>
              <th>Nombre</th>
              <th>#Ventas</th>
              <th>$Total Ventas</th>
              <th>$Comisión</th>
              <th>$Sueldo base</th>
              <th>$Sueldo</th>
            </tr>
          </thead>
          @foreach ($datos as $dato)
            <tr>
              <th>{{ $dato->rol }}</d>
              <td>{{ $dato->nombre }}</td>
              <td align="right">{{ number_format($dato->cantidad, 0) }}</td>
              <td align="right">${{ number_format($dato->ventas, 2) }}</td>
              <td align="right">${{ number_format($dato->comision, 2) }}</td>
              <td align="right">${{ number_format($dato->sueldo, 2) }}</td>
              <td align="right">${{ number_format($dato->sueldo_final, 2) }}</td>
            </tr>
          @endforeach
        </table>

      </div>
    </div>
  </div>
</div>
@endsection



