@extends ('layouts.app')
@section('content')
<div class="container">
    {{ Form::model($obj, ['route'=>['productos.update', $obj->id ]]) }}
    @method('PUT')
    <div class="card">
        <div class="card-header">Editar producto</div>
        <div class="card-body">
            @include ("productos._form")


            <div class="form-group">
                <input type="submit" value="Guardar" class="btn btn-primary">
                <a href="{{route('productos.index')}}" class="btn btn-secondary">Cancelar</a>
            </div>

        </div>
    </div>
    {{ Form::close() }}
    </form>
    
    <div class="card mt-3">
        <div class="card-header bg-danger text-light">Eliminar producto</div>
        <div class="card-body">
            <form method="post" action="{{ route('productos.destroy', $obj->id)}}">
                @method('DELETE')
                @csrf
                <p>
                Eliminar permanentemente el producto, esta operacion no puede ser revertida, usela con cuidado.
                </p>
                <input type="submit" value="Eliminar" class="btn btn-danger" onclick="return confirm('Esta seguro que desea borrar este producto?')">
            </form>
        </div>
    </div>
</div>
@endsection

