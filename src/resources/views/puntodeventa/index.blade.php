@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <form method="post" action="{{ route('puntodeventa.agregar') }}" class="form-inline" id="formaBuscar">
            @csrf
                <div class="input-group">
                    <input type="text" name="codigo" id="codigo" class="form-control" placeholder="Código de producto" autofocus autocomplete="off"> 
                    <div class="input-group-append">
                        <input type="submit" value="Agregar" class="btn btn-success">
                    </div>
                </div>
            
        </form>
    </div>
    <div class="row mt-3" id="sesion">{!! $sesion !!}</div>
    <div class="row mt-3">
        <form method="post" action="{{ route("puntodeventa.guardar") }}">
            @csrf
            <input type="submit" value="Guardar venta" class="btn btn-primary">
            <a href="{{ route('puntodeventa.limpiar') }}" class="btn btn-danger">Limpiar</a>
        </form>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let forma = $("#formaBuscar")
    forma.submit(function() {
        $.post(forma.attr("action"), forma.serialize())
            .done(function(data) {
                if(!data.status) {
                    alert('No se encontró el producto')
                    return
                }
                $("#codigo").prop('value', '')
                $("#sesion").load('{{ route("puntodeventa.sesion") }}');
            })
        return false
    })
})
</script>
@endsection
