<div class="col-md-6">
    <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Producto</th>
            <th>Precio</th>
        </tr>
    </thead>
    @foreach ($sesion as $producto)
        <tr>
            <td>{{$producto[1]}}</td>
            <td>{{$producto[0]->nombre }}</td>
            <td align="right">${{number_format($producto[0]->precio,2)}}</td>
        </tr>
    @endforeach
    <tr>
        <th></td>
        <th class="text-right">Total</th>
        <th class="text-right">${{number_format($total, 2)}}</th>
    </tr>
    </table>
</div>
