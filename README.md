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

