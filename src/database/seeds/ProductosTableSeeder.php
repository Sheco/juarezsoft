<?php

use Illuminate\Database\Seeder;
use App\Producto;
use App\Proveedor;

class ProductosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function() {
            DB::table('proveedor_productos')->truncate();
            Proveedor::truncate();
            Producto::truncate();

            /* Crear a los proveedores */
            $datos_proveedores = [
                [ "nombre" => "Almacen Caja de Metal" ],
                [ "nombre" => "Bodega ahorraras" ],
                [ "nombre" => "Distribuidora Castillo" ],
                [ "nombre" => "Distribuidora Mexicana" ],
                [ "nombre" => "Proveedor X" ],
            ];

            $proveedores = [];
            foreach ($datos_proveedores as $dp) {
                $proveedores[] = Proveedor::create($dp);    
            }

            /* Luego crear productos */
            $productos = [
                [ "nombre"=>"Corbata", "codigo"=>"corbata", "precio"=>500, "departamento_id"=>1, 'frecuenciaCompras'=> 50],
                [ "nombre"=>"Pantalón mezclilla", "codigo"=>"pantalonmezcilla", "precio"=>800, "departamento_id"=>1, 'frecuenciaCompras'=> 90],
                [ "nombre"=>"Pantalón de vestir", "codigo"=>"pantalonvestir", "precio"=>1200, "departamento_id"=>7, 'frecuenciaCompras'=> 30],

                [ "nombre"=>"Vestido", "codigo"=>"vestido", "precio"=>1500, "departamento_id"=>2, 'frecuenciaCompras'=> 100],
                [ "nombre"=>"Tacones", "codigo"=>"tacones", "precio"=>800, "departamento_id"=>2, 'frecuenciaCompras'=> 60],
                [ "nombre"=>"Bolsa", "codigo"=>"bolsa", "precio"=>800, "departamento_id"=>2, 'frecuenciaCompras'=> 100],

                [ "nombre"=>"Juguete Toy Story", "codigo"=>"juguetetoystory", "precio"=>500, "departamento_id"=>3, 'frecuenciaCompras'=> 80],
                [ "nombre"=>"Triciclo", "codigo"=>"triciclo", "precio"=>1500, "departamento_id"=>3, 'frecuenciaCompras'=> 70],
                [ "nombre"=>"Pistola NERF", "codigo"=>"pistolanerf", "precio"=>300, "departamento_id"=>3, 'frecuenciaCompras'=> 80],

                [ "nombre"=>"Carriola", "codigo"=>"carriola", "precio"=>2000, "departamento_id"=>4, 'frecuenciaCompras'=> 70],
                [ "nombre"=>"Cuna", "codigo"=>"cuna", "precio"=>5000, "departamento_id"=>4, 'frecuenciaCompras'=> 60],
                [ "nombre"=>"Pañales", "codigo"=>"panales", "precio"=>500, "departamento_id"=>4, 'frecuenciaCompras'=> 100],

                [ "nombre"=>"Camiseta Jr", "codigo"=>"camisetajr", "precio"=>500, "departamento_id"=>5, 'frecuenciaCompras'=> 80],
                [ "nombre"=>"Zapatos Jr", "codigo"=>"zapatosjr", "precio"=>700, "departamento_id"=>5, 'frecuenciaCompras'=> 90],
                [ "nombre"=>"Pantalones Jr", "codigo"=>"pantalonesjr", "precio"=>800, "departamento_id"=>5, 'frecuenciaCompras'=> 80],

                [ "nombre"=>"Jersey deportivo", "codigo"=>"jerseydeportivo", "precio"=>900, "departamento_id"=>6, 'frecuenciaCompras'=> 80],
                [ "nombre"=>"Balon de futból", "codigo"=>"balonfutbol", "precio"=>1000, "departamento_id"=>6, 'frecuenciaCompras'=> 70],
                [ "nombre"=>"Bicicleta", "codigo"=>"bicicleta", "precio"=>3000, "departamento_id"=>6, 'frecuenciaCompras'=> 80],
                [ "nombre"=>"Patineta", "codigo"=>"patineta", "precio"=>800, "departamento_id"=>6, 'frecuenciaCompras'=> 90],

                [ "nombre"=>"Reloj Rolex", "codigo"=>"relojrolex", "precio"=>3000, "departamento_id"=>7, 'frecuenciaCompras'=> 40],
                [ "nombre"=>"Reloj de oro", "codigo"=>"relojoro", "precio"=>7000, "departamento_id"=>7, 'frecuenciaCompras'=> 40],
                [ "nombre"=>"Reloj para dama", "codigo"=>"relojdama", "precio"=>2500, "departamento_id"=>7, 'frecuenciaCompras'=> 60],
                [ "nombre"=>"Apple Watch", "codigo"=>'applewatch', 'precio'=>12000, 'departamento_id'=>7, 'frecuenciaCompras'=>50],

                [ "nombre"=>"Consola XBox One S", "codigo"=>"xboxonex", "precio"=>8000, "departamento_id"=>8, 'frecuenciaCompras'=> 50],
                [ "nombre"=>"Consola Nintendo Switch", "codigo"=>"nintendoswitch", "precio"=>6000, "departamento_id"=>8, 'frecuenciaCompras'=> 50],
                [ "nombre"=>"Consola Playstation 4", "codigo"=>"playstation4", "precio"=>8000, "departamento_id"=>8, 'frecuenciaCompras'=> 50],

                [ "nombre"=>"Libro Harry Potter 1", "codigo"=>"libroharrypotter1", "precio"=>400, "departamento_id"=>9, 'frecuenciaCompras'=> 30],
                [ "nombre"=>"Película Harry Potter 1", "codigo"=>"peliculaharrypotter1", "precio"=>600, "departamento_id"=>9, 'frecuenciaCompras'=> 30],
                [ "nombre"=>"Musica de Harry Potter 1", "codigo"=>"musicaharrypotter1", "precio"=>300, "departamento_id"=>9, 'frecuenciaCompras'=> 30],
            ];

            foreach($productos as $producto) {
                $producto = Producto::create($producto);

                /* A cada producto se le asigará aleatoreamente de uno a tres proveedores
                 * Y cada proveedor tendra un precio aleatorio menor al precio de lista del producto */
                $numero_proveedores = rand(1, 3);

                $proveedores_asignados = collect($proveedores)
                    ->shuffle()
                    ->slice(0, $numero_proveedores);

                echo "El producto $producto->nombre ($$producto->precio) tiene $numero_proveedores proveedor(es)\n";
                foreach($proveedores_asignados as $prov) {
                    $markup = (150+rand(0, 150))/100;
                    $precio = round($producto->precio/$markup, 2);
                    echo "- $prov->nombre (markup $markup: $$precio)\n";

                    $producto->proveedores()->attach($prov->id, [
                        "precio"=>$precio
                    ]);
                }
            }
        });
    }
}
