@extends ('layouts.app')
@section('content')
<div class="container">
    {{ Form::model($obj, ['route'=>'users.store']) }}
    <div class="card">
        <div class="card-header">Crear un nuevo usuario</div>
        <div class="card-body">
            @include ("users._form")

            <div class="form-group">
                Favor de guardar este usuario para poder asignarle roles.
            </div>

            <div class="form-group">
                <input type="submit" value="Guardar" class="btn btn-primary">
                <a href="{{route('users.index')}}" class="btn btn-danger">Cancelar</a>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>
@endsection
