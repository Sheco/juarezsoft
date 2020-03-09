@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Filtros</div>
        <div class="card-body">
          <form method="get" action="{{ route('ventas.diarias') }}">

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

            <input type="submit" value="Aplicar" class="btn btn-primary">
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
          <canvas id="ventas"></canvas>
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
                    label: 'Total',
                    yAxisID: 'total',
                    backgroundColor: 'rgb(54, 162, 235)',
                    borderColor: 'rgb(54, 162, 235)',
                    fill: false,
                    data: {!! json_encode($totales) !!}
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
                    }]
                }
            }
        })
    }
</script>
@endsection

