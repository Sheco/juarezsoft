# ERP para controlar las tiendas del Ing. Juarez

## Planteamiento resumido
El Ingeniero tiene varias tiendas, distribuidas a lo largo de México.

## Características técnicas:
- Cada tienda tiene que poder operar de manera local, sin depender de un enlace a Internet.
- El dueño podrá conectarse por VPN, para tener un acceso seguro al sistema.

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

## Instalación usando Docker

Para una manera mucho mas sencilla de tener la aplicación corriendo, podemos usar Docker, para esto lo unico que necesitamos es correr el script `init-docker.sh`, en un equipo que tenga instalado Docker y docker-compose.

Este entorno de Docker incluye la apliación corriendo con PHP-FPM y un webserver nginx que sirve tanto los recursos estaticos así tambien actua como proxy reverso para acceder a los endpoints PHP.

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
    - departamento_id (Referencia a departamentos.id)
    - precio
    - stock

- proveedores
    - id
    - nombre

- proveedor_productos
    - id
    - producto_id (Referencia a productos.id)
    - precio

- solicitudes_compra
    - id
    - proveedor_id (Referencia a proveedores.id)
    - producto_id (Referencia a productos.id)
    - cantidad
    - status (nueva, pagada, entregada, cancelada)
    - fecha_solicitud
    - fecha_pago
    - fecha_entregado

- ventas
    - id
    - usr_id (Referencia a users.id)
    - fecha

- venta_productos
    - venta_id (Referencia a ventas.id)
    - producto_id (Referencia a productos.id)
    - cantidad
    - precio

- facturas
    - id
    - venta_id (Referencia a ventas.id)
    - status (normal, cancelada)
    - rfc
    - nombre
    - direccion
    - codigopostal
    - telefono
    - email
    
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
- Reporte de Ventas diarias generales (agregado por mes)
    - Fecha
    - Total
- Reporte de Ventas diarias por producto (agregado de día)
    - Producto
    - cantidad vendida
    - promedio de cantidad vendida en la ultima semana
    - Stock disponible
    - Estimado de dias que durará el stock
- Reporte de Ventas por empleado (agregado por mes)
    - Empleado
    - Importe vendido
    - Bono de venta
- Reporte de Nomina (agregado por mes)
    - Empleado
    - Sueldo a pagar (incluyendo bonos)
- Reporte de Cuentas por pagar
    - Proveedor
    - Saldo
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
- Usuarios
- Departamentos
- Productos
- Proveedores
- Solicitudes de compra
- Facturación

Modulos especiales:
- Generación de ventas aleatorias

Reportes:
- Ventas diarias generales
- Ventas diarias por producto
- Ventas por empleado
- Nomina
- Inventario
- Cuentas por pagar

## Detalle de los módulos de administración

### Roles

El módulo de roles muestra la lista de roles registrados en el sistema, los roles definen las tareas que un usuario tiene disponibles, limitando la funcionalidad de la aplicación.

Los roles predeterminados son: Administrador, Gerente, Subgerente, Jéfe de piso y Vendedor. 

Cada rol tiene definido el sueldo que el usuario percibe.

### Usuarios

El módulo de usuarios muestra la lista de usuarios registrados en el sistema, ofreciendo la capacidad de administrarlos, ya se creando usuarios, editandolos o incluso borrandolos.

### Productos

Este modulo permite administrar el catalogo de productos que estaran disponibles en la aplicación.

Esto incluye la asignación de nombre, código y precio de lista.

Por igual aquí mismo se puede controlar el abasto de cada producto, iniciando el proceso de solicitud de compra. Esto se hace teniendo disponible una lista de proveedores que venden el producto así como el precio al que venden dicho producto, lo cual es útil para darle seguimiento al margen de utilidad.

Adicionalmente tambien se muestra una grafica de comportamiento que muestra la cantidad y el monto total que se ha vendido por cada uno de los productos.

### Solicitudes de compra

Es aquí donde se le da seguimiento a las solicitudes de compra de productos, este modulo permite controlar a un nivel controlado dicho proceso, pues cada solicitud puede tener varios estados, como:

- Nueva: Una solicitud de compra nueva es aquella que se acaba de generar, pendiente de que sea pagada.
- Pagada: Una vez que el departamento interno realice el pago al proveedor, pendiente de que sea surtida.
- Surtida: El proveedor ya ha entregado el producto, terminando el proceso.
- Cancelada: Si por algún motivo la solicitud es inválida, esta sera marcada como cancelada.

En cada paso se registra la fecha en la que se cambia el estado, para efectos de seguimiento.

### Facturación

Cuando se usa el punto de venta y se efectua exitosamenta venta, se hace disponible la información de facturación, tanto en pantalla como tambien en el ticket impreso, el cliente podra acudir a una terminal de facturación para proporcionar estos datos y solicitar una factura.

Tras ingresar el numero de operación, la fecha y el monto total de la misma, el usuario tendrá la oportunidad de ingresar sus datos fiscales, con los cuales se generara la factura.

## Detalle de los reportes

### Nómina

Este modulo calcula el tabulador de nómina tomando en cuenta el rol de cada empleado, y las ventas realizadas en el periodo para determinar su percepción total.

### Ventas díarias

Este reporte muestra una grafica de comportamiento con un resultado agregado de total de cada día en un rango de fechas especifico.

Aquí se pueden ver las tendencias de fines de semana así como tambien los días con ventas especiales.

### Ventas por producto

Cuando se necesite ver un detalle de comportamiento por producto se podra usar este reporte el cual permite especificar un rango de fechas, un producto especifico y un metodo de ordenación con el cual se mostrara el resultado.

### Ventas por vendedor

Por el otro lado, tambien se puede obtener un reporte que resume el total de las ventas que realizo un vendedor en un rango de fechas especifico.









