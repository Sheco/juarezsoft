<div class="container">
    <div class="row">


<div class="form-group col-md-6">
    <label for="nombre">Nombre:</label>
    {{ Form::text('nombre', null, ['class'=>'form-control']) }}
</div>

<div class="form-group col-md-6">
    <label for="codigo">Código:</label>
    {{ Form::text('codigo', null, ['class'=>'form-control']) }}
</div>

<div class="form-group col-md-6">
    <label for="precio">Precio</label>
    {{ Form::text('precio', null, ['class'=>'form-control']) }}
</div>

<div class="form-group col-md-6">
    <label for="stock">Abasto</label>
    {{ Form::text('stock', null, ['class'=>'form-control']) }}
    <small>Solo editar la cantidad de abasto en casos especiales, en condiciones normales se debe usar el modulo de solicitudes de compra</small>
</div>

<div class="form-group col-md-6">
    <label for="departamento_id">Departamento</label>
    {{ Form::select('departamento_id', $departamentos->pluck('nombre', 'id'),
        null, ['class'=>'form-control']) }}
</div>

<div class="form-group col-md-6">
    <label for="frecuenciaCompras">Frecuencia de compras</label>
    {{ Form::text('frecuenciaCompras', null, ['class'=>'form-control']) }}
        <small class="text-secondary">Este debe ser un numero del 0 all 100, mientras mas alto,
            mas probable es que este producto sea comprado en el generador
            automatico</small>
</div>


    </div>
</div>
