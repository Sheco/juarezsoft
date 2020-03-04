@extends ('layouts.app')
@section('content')
<div class="container">
    <form method="POST" action="{{route('users.update', [$obj->id])}}">
        @method('PUT')
        @csrf
    <div class="card">
        <div class="card-header">Crear un nuevo usuario</div>
        <div class="card-body">
            @include ("users._form")

            <div class="form-group">
                <div class="container">
                    <div class="row">
                        <div class="col-3">
                            <select name="role" id="role" class="form-control">
                            @foreach ($roles as $role)
                                <option value="{{$role->name}}">{{$role->name}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <button class="btn btn-secondary" onclick="addRole(); return false;">Asignar rol</button>
                        </div>
                    </div>
                </div>
                <div id="roles">
                    @include ("users.roles")
                </div>
            </div>

            <div class="form-group">
                <input type="submit" value="Guardar" class="btn btn-primary">
                <a href="{{route('users.index')}}" class="btn btn-secondary">Cancelar</a>
            </div>

        </div>
    </div>
    </form>
    
    <div class="card mt-3">
        <div class="card-header bg-danger text-light">Eliminar usuario</div>
        <div class="card-body">
            <form method="post" action="{{ route('users.destroy', $obj->id)}}">
                @method('DELETE')
                @csrf
                <p>
                Eliminar permanentemente el usuario, esta operacion no puede ser revertida, usela con cuidado.
                </p>
                <input type="submit" value="Eliminar" class="btn btn-danger" onclick="return confirm('Esta seguro que desea borrar este usuario?')">
            </form>
        </div>
    </div>
</div>
<script>
const urls = {
    addRole: '{{ route('users.addRole', $obj->id) }}',
    delRole: '{{ route('users.delRole', $obj->id) }}'
}

async function addRole() {
    let role = document.getElementById('role').value
    try {
        let response = await fetch(urls.addRole, {
            method: 'post',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ role })
        })
        document.getElementById('roles').innerHTML = await response.text()

    } catch (e) {
        alert(e)
    }
}

    async function delRole(name) {
        try {
            let response = await fetch(urls.delRole, {
                method: 'post',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ role: name })
            })
            document.getElementById('roles').innerHTML = await response.text()
        } catch (e) {
            alert(e)
        }
    }

</script>
@endsection

