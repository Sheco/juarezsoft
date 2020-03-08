<div class="form-group">
    <label for="name">Nombre:</label>
    {{ Form::text("name", null, [
        'class'=>'form-control',
        'autofocus'
        ]) }}
</div>

<div class="form-group">
    <label for="email">Email:</label>
    {{ Form::email('email', null, [
        'class'=>'form-control' ]) }}
</div>

<div class="form-group">
    <label for="password">Password</label>
    {{ Form::password('password', [
        'class'=>'form-control' ]) }}
</div>

<div class="form-group">
    <label for="frecuenciaVentas">Frecuencia de ventas</label>
    {{ Form::number('frecuenciaVentas', null, [
        'class'=>'form-control' ]) }}
        <small class="text-secondary">Este debe ser un numero del 0 all 100, mientras mas alto,
            mas probable es que este usuario genere ventas con el generador
            automatico</small>
</div>


