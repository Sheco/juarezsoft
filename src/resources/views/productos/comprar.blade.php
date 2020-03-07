@extends('layouts.app')
@section('content')
    <form method="post" action="{{ route('productos.comprarGuardar', [
        $producto, $proveedor
    ]) }}">
        @csrf
    <div class="container">
        <div class="card">
            <div class="card-header">Comprar {{ $producto->nombre }} de {{ $proveedor->nombre }}</div>
            <div class="card-body"><div class="container"><div class="row">
                <div class="col-md-12">
                Crear una solicitud de compra de <b>{{$producto->nombre}}</b> del proveedor <b>{{ $proveedor->nombre }}</b>
                </div>
                <div class="form-group col-md-6">
                    <label for="cantidad"><b>Cantidad</b></label>
                    <input type="number" name="cantidad" id="cantidad" class="form-control" onchange="calculate(this)">
                </div>

                <div class="form-group col-md-6">
                    <label for="total"><b>Total</b></label>
                    <div id="total">$0.00</div>
                </div>

                <div class="form-group col-md-3">
                    <label>&nbsp;</label>
                    <input type="submit" value="Crear solicitud de compra" class="btn btn-primary form-control">
                </div>
            </div></div></div>
        </div>
    </div>
    </form>
<script>
    let precio = {{ $proveedor->pivot->precio }}
    function calculate(input) {
        let totalField = document.getElementById('total')
        totalField.innerHTML = '$'+ Intl.NumberFormat().format(precio*input.value)
    }
    calculate(document.getElementById('cantidad'))
</script>
@endsection
