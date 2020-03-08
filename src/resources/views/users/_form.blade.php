<div class="form-group">
    <label for="name">Nombre:</label>
    <input type="text" name="name" id="name" 
         class="form-control" value="{{$obj->name}}" autofocus>
</div>

<div class="form-group">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" 
        class="form-control" value="{{$obj->email}}">
</div>

<div class="form-group">
    <label for="password">Password</label>
    <input type="password" name="password" id="password"
       class="form-control" value="">
</div>

<div class="form-group">
    <label for="frecuenciaVentas">Frecuencia de ventas</label>
    <input type="number" name="frecuenciaVentas" id="frecuenciaVentas"
        class="form-control" value="{{$obj->frecuenciaVentas}}">
        <small class="text-secondary">Este debe ser un numero del 0 all 100, mientras mas alto,
            mas probable es que este usuario genere ventas con el generador
            automatico</small>
</div>


