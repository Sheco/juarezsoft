@extends('layouts.app')

@section('content')
<div class="container">
<h4>Usuarios</h4>

<a href="{{route('users.create')}}" class="btn btn-primary">Crear nuevo</a>
<table class="table table-bordered table-striped table-hover mt-3">
<thead>
    <tr>
        <th>Email</th>
        <th>Nombre</th>
        <th>Roles</th>
        <th></th>
    </tr>
</thead>
@foreach ($datos as $obj) 
    <tr>
        <td><a href="{{ route('users.edit', [$obj->id]) }}">
                {{$obj->email}}</a></td>
        <td>{{$obj->name}}</td>
        <td>{{$obj->roles->pluck('name')->join(', ')}}</td>
        <td><a href="{{route('users.ventas', $obj->id)}}">Ventas</a></td>
    </tr>
@endforeach
</table>
</div>
@endsection
