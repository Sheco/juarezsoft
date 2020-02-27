# ERP para controlar las tiendas del Ing. Juarez

## Planteamiento resumido
El Ingeniero tiene varias tiendas, distribuidas a lo largo de México.

## Características técnicas:
- Cada tienda tiene que poder operar de manera local, sin depender de un enlace a Internet.
- El dueño podrá conectarse por VPN, para tener una acceso seguro al sistema.

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
    - fecha

- venta_productos
    - producto_id
    - cantidad

# Tareas

- Migraciónes de la estructura de la base de datos
- Poblar la base de datos con los catalogos estaticos
    - Usuarios
    - Departamentos
    - Productos
    - Proveedores (y los productos que proveen)
- Script para simular el proceso de solicitudes de compra, para hacer stock
    - API de solicitudes de compra
        - Funcion que reciba los siguientes argumentos
            - Proveedor
            - Producto
            - Cantidad
        - Funcion para marcar como pagada una solicitud de compra
        - Funcion para marcar como entregada una solicitud de compra
- Script para simular ventas aleatorias
    - API de ventas
        - Una funcion que recibe
            - Vendedor
            - Lista de productos y cantidad
    - Objetivos
        - Semanal $750,000  por dia
        - Fines de semana $2,500,000 por día
        - Ventas especiales (un dia al mes) $1,750,00.00
- Reporte de Ventas diarias generales
- Reporte de Ventas diarias por producto
- Reporte de Ventas por empleado
- Reporte de Nomina
- Reporte de Inventario
- Reporte de Cuentas por pagar
- Interfaz de captura de catalogos estaticos
- Interfaz de punto de venta
- Facturación

# Interfaz de usuario

## Inicio de sesión

Para poder usar el sistema es obligatorio iniciar sesión con usuario y clave.

Por defecto, se incluirá un usuario administrador con el cual se podrán agregar
más usuarios y poblar los catalogos de la base de datos.

## Pantalla principal

Al iniciar sesión, se muestra la interfaz de punto de venta, que incluye un 
campo de texto para capturar el código del producto.

Debajo de este campo, se mostrará una lista de los productos que se han
agregado a la sesión.

A un lado de esta lista, se mostrará el total a cobrar, así como un boton
para guardar la venta y un botón para cancelar la venta.

## Módulos de administración

Si el usuario actual tiene permisos de administrador, en el menu superior
tendrá bajo un menú contextual, una lista de módulos administrativos.

Se pueden encontrar los siguientes módulos administrativos:

Módulos de gestion de catalogos:
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

# Requerimientos e instalación

Este software está escrito en PHP usando el framework Laravel, por lo cual puede ser instalado en cualquier equipo que tenga ```PHP 7.2``` o superior, favor de revisar los [requerimentos del Framework](https://laravel.com/docs/6.x/installation)

La aplicación es compatible con cualquiera de las bases de datos populares, como MySQL, PostgreSQL y sqlite, para efectos prácticos y de demostración, se usara sqlite, para un entorno de producción se recomienda una base de datos más robusta, como PostgreSQL.

Para instalar las depenencias de PHP usarémos [composer](http://getcomposer.org).

Para compilar recursos estáticos (css), se necesita tener instalado ```NodeJS 10.x``` o superior, así como también su compañero ```npm```.

Las instrucciones a continuación asumen un entorno *nix, como Linux o MacOS, para preparar la aplicación.

Primero necesitamos clonar el repositorio con el código fuente.
```
$ git clone https://github.com/Sheco/juarezsoft
```

Todo lo que sigue, lo tenemos que ejecutar dentro del directorio ```src/```.
A continuación, tenemos que instalar las dependencias y compilar los recursos estáticos:

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

Para acceder la aplicación, se debe configurar el webserver (Apache/Nginx) para que la raíz de documentos para esta app sea el directorio ```src/public/```, para efectos de prueba, se puede lanzar un servidor web temporal usando:

```
$ php artisan serve
```

Dependiendo de cómo se haya publicado la aplicación web, ahora ya podremos entrar a la dirección apropiada.
