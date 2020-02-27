# ERP para controlar las tiendas del Ing. Juarez

## Planteamiento resumido
El Ingeniero tiene varias tiendas, distribuidas a lo largo de México.

Caracteristicas tecnicas:
- Cada tienda tiene que poder operar de manera local, sin depender de un enlace a Internet.
- El dueño podra conectarse por VPN, para tener una acceso seguro al sistema.

## Estructura de la base de datos

- roles
    - id
    - name
    - sueldo

- users
    - id
    - name
    - email
    - password
    - role_id

- departamentos
    - id
    - nombre

- productos
    - id
    - nombre
    - codigo
    - departamento_id
    - precio
    - stock

- proveedores
    - id
    - nombre

- proveedor_productos
    - id
    - producto_id
    - precio

- solicitudes_compra
    - id
    - proveedor_id
    - producto_id
    - cantidad
    - status (nueva, pagada, entregada, cancelada)
    - fecha_solicitud
    - fecha_pago
    - fecha_entregado

- ventas
    - id
    - usr_id
    - producto_id
    - fecha
    - cantidad

# Procesos

## Inicio de sesión

Para poder usar el sistema es obligatorio iniciar sesión con usuario y clave.

Por defecto, se incluirá un usuario administrador con el cual se podran agregar
mas usuarios y poblar los catalogos de la base de datos.

## Pantalla principal

Al iniciar sesión, se muestra la interfaz de punto de venta, que incluye un 
campo de texto para capturar el codigo del producto.

Debajo de este campo, se mostrará una lista de los productos que se han
agregado a la sesión.

A un lado de esta lista, se mostrara el total a cobrar, así como un boton
para guardar la venta y un boton para cancelar la venta.

## Modulos de administración

Si el usuario actual tiene permisos de administrador, en el menu superior
tendrá bajo un menú contextual, una lista de modulos administrativos.

Se pueden encontrar los siguientes modulos administrativos:

Modulos de gestion de catalogos:
- Roles
- Usuarios
- Departamentos
- Productos
- Proveedores
- Solicitudes de compra

Modulos especiales:
- Generación de ventas aleatorias

Reportes:
- Ventas diarias generales
- Ventas diarias por producto
- Ventas por empleado
- Nomina
- Inventario
- Cuentas por pagar

# Requerimentos e instalación

Este software esta escrito en PHP usando el framework Laravel, por lo cual puede ser instalado en cualquier equipo que tenga ```PHP 7.2``` o superior, favor de revisar los [requerimentos del Framework](https://laravel.com/docs/6.x/installation)

La aplicación es compatible con cualquiera de las bases de datos populares, como MySQL, PostgreSQL y sqlite, para efectos practicos y de demostración, se usara sqlite, para un entorno de produccion se recomienda una base de datos mas robusta, como PostgreSQL.

Para instalar las depdenencias de PHP usaremos [composer](http://getcomposer.org).

Para compilar recursos estaticos (css), se necesita tener instalado ```NodeJS 10.x``` o superior, así como tambien su compañero ```npm```.

Las instrucciones a continuación asumen un entorno *nix, como Linux o MacOS, para preparar la aplicación.

Primero necesitamos clonar el repositorio con el codigo fuente.
```
$ git clone https://github.com/Sheco/juarezsoft
```

Todo lo que sigue, lo tenemos que ejecutar dentro del directorio ```src/```.
A continuación, tenemos que instalar las dependencias y compilar los recursos estaticos:

```
$ composer install
$ npm install
$ npm run production
```

Luego es necesario preparar la configuración.

```
$ cp .env.sample .env
$ php artisan key:generate
$ touch storage/database.sqlite
```

Ahora estamos listos para preparar el contenido de la base de datos:

```
$ php artisan migrate
$ php artisan db:seed
```

Esto genero un usuario predeterminado, ```admin@localhost``` con contraseña ```juarez123```, es importante cambiar la contraseña lo antes posible.
