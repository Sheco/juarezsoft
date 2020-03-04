@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Filtros</div>
        <div class="card-body">
          <form method="get" action="{{ route('ventas.productos') }}">

            <div class="form-group">
              <label for="fecha_inicio">Fecha de inicio:</label>
              <input type="date" name="fecha_inicio" id="fecha_inicio" 
                value="{{ $fecha_inicio }}" class="form-control">
            </div>

            <div class="form-group">
              <label for="fecha_fin">Fecha final:</label>
              <input type="date" name="fecha_final" id="fecha_final"
                value="{{ $fecha_final }}" class="form-control">
            </div>

            <div class="form-group">
              <label for="producto_id">Producto:</label>
              <select name="producto_id" id="producto_id" class="form-control">
                <option value="">-- Todos</option>
              @foreach ($productos as $producto)
                <option value="{{ $producto->id }}"{{ 
                  $producto->id == $producto_id? " selected": ""
                }}>{{ $producto->departamento->nombre }} / {{ $producto->nombre }}</option>
              @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="orden">Ordenación:</label>
              <select name="orden" class="form-control">
                <option value=""{{ $orden==""? " selected": "" }}>
                  Total
                </option>
                <option value="fecha,total"{{ $orden=="fecha,total"?" selected": ""}}>
                  Fecha, Total
                </option>
              </select>
            </div>
            <input type="submit" value="Aplicar" class="btn btn-primary">
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <table class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Total</th>
            </tr>
          </thead>
          @foreach ($datos as $dato)
            <tr>
              <td>{{ $dato->fecha }}</td>
              <td>{{ $dato->nombre }}</td>
              <td align="right">{{ number_format($dato->cantidad, 0) }}</td>
              <td align="right">${{ number_format($dato->total, 2) }}</td>
            </tr>
          @endforeach
        </table>

      </div>
    </div>
  </div>
</div>
@endsection
