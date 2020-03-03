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
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Ventas</div>
                <div class="card-body">
                    <div class="nav flex-column list-group">
                        <li class="nav-item list-group-item">
                            <a class="nav-link" href="{{ route('ventas.diarias') }}">
                                Ventas diarias
                            </a>
                        </li>
                        <li class="nav-item list-group-item">
                            <a class="nav-link" href="{{ route('ventas.productos') }}">
                                Ventas por producto
                            </a>
                        </li>
                        <li class="nav-item list-group-item">
                            <a class="nav-link" href="{{ route('ventas.vendedores') }}">
                                Ventas por vendedor
                            </a>
                        </li>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
