@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Bienvenido</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Esta usted adentro del sistema JuarezSoft.
                </div>
            </div>
        </div>

    </div>
    <div class="row mt-3">
    @can('administrar inventario')
    @if ($inventario->count())
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Productos agotandose</div>
                <div class="card-body">
                    Los siguientes productos estan a punto de agotarse. A continuación se muestra la cantidad vendida en los ultimos 7 dias así como tambien la cantidad que se tiene de abasto.
                    <table class="table table-bordered table-striped table-hover mt-3">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Vendidos</th>
                            <th>Abasto</th>
                        </tr>
                    </thead>
                    @foreach ($inventario as $producto)
                        <tr>
                            <td><a href="{{ route("productos.show", $producto->id) }}">{{$producto->nombre}}</a></td>
                            <td align="right">{{number_format($producto->vendidos,0)}}</td>
                            <td align="right">{{number_format($producto->stock, 0)}}</td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    @endif
    @endcan

    @can('ver reportes')
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Reportes de Ventas</div>
                <div class="card-body nav flex-column list-group">
                        <a class="nav-link" href="{{ route('ventas.diarias') }}">
                            Ventas diarias
                        </a>
                        <a class="nav-link" href="{{ route('ventas.productos') }}">
                            Ventas por producto
                        </a>
                        <a class="nav-link" href="{{ route('ventas.vendedores') }}">
                            Ventas por vendedor
                        </a>
                </div>
            </div>
        </div>
    @endcan

    @can('ver administración')
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Administración</div>
                <div class="card-body nav flex-column list-column">
                        @can('administrar usuarios')
                            <a class="nav-link" href="{{ route('users.index') }}">
                                Usuarios
                            </a>
                            <a class="nav-link" href="{{ route('users.nomina') }}">
                                Nómina
                            </a>
                        @endcan
                        @can('administrar inventario')
                            <a class="nav-link" href="{{ route('productos.index') }}">Productos</a>
                            <a class="nav-link" href="{{ route('solicitudescompra.index') }}">Solicitudes de compra</a>
                        @endcan
                </div>
            </div>
        </div>
    @endcan

    </div>
</div>
@endsection
