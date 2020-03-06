@extends ('layouts.app')
@section('content')
<div class="container">
    {{ Form::model($obj, ['route'=>'productos.store']) }}
    @csrf
    <div class="card">
        <div class="card-header">Crear un nuevo producto</div>
        <div class="card-body">
            @include ("productos._form")

            <div class="form-group">
                <input type="submit" value="Guardar" class="btn btn-primary">
                <a href="{{route('productos.index')}}" class="btn btn-danger">Cancelar</a>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>
@endsection
