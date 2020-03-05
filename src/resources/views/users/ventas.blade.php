@extends("layouts.app")
@section("content")
<div class="content">
<div class="row">
    <div class="col-6">
    <form method="get" action="{{route('users.ventas', $user->id)}}">
    <div class="card">
        <div class="card-header">Filtros</div>
        <div class="card-body">
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" id="fecha"
                    class="form-control" value="{{$fecha}}"
                    onchange="this.form.submit()">
            </div>
        </div>
    </div>
    </form>
    </div>

    <div class="col-6">
    <table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Hora</th>
            <th>Productos</th>
            <th>Total</th>
        </tr>
    </thead>
    @php 
        $total = 0;
    @endphp
    @foreach ($datos as $dato) 
        @php
            $total += $dato->total;
        @endphp
        <tr>
            <td><a href="{{route('ventas.ver', $dato->id)}}">{{$dato->id}}</a></td>
            <td>{{(new \Carbon\Carbon($dato->fecha_hora))->format('h:ia')}}</td>
            <td>{{number_format($dato->productos, 0)}}</td>
            <td>${{number_format($dato->total,2)}}</td>
        </tr>
    @endforeach
        <tr style="font-weight: bold">
            <td></td>
            <td></td>
            <td align="right">Total:</td>
            <td>${{number_format($total, 2)}}
        </tr>
    </table>
    </div>
</div>
@endsection
