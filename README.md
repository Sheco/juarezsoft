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
