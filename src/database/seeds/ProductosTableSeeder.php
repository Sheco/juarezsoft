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
                [ "nombre"=>"Corbata", "codigo"=>"corbata", "precio"=>200, "departamento_id"=>1],
                [ "nombre"=>"Pantalón mezclilla", "codigo"=>"pantalonmezcilla", "precio"=>400, "departamento_id"=>1],
                [ "nombre"=>"Pantalón de vestir", "codigo"=>"pantalonvestir", "precio"=>700, "departamento_id"=>1],

                [ "nombre"=>"Vestido", "codigo"=>"vestido", "precio"=>600, "departamento_id"=>2],
                [ "nombre"=>"Tacones", "codigo"=>"tacones", "precio"=>400, "departamento_id"=>2],
                [ "nombre"=>"Bolsa", "codigo"=>"bolsa", "precio"=>300, "departamento_id"=>2],

                [ "nombre"=>"Juguete Toy Story", "codigo"=>"juguetetoystory", "precio"=>300, "departamento_id"=>3],
                [ "nombre"=>"Triciclo", "codigo"=>"triciclo", "precio"=>1000, "departamento_id"=>3],
                [ "nombre"=>"Pistola NERF", "codigo"=>"pistolanerf", "precio"=>200, "departamento_id"=>3],

                [ "nombre"=>"Carriola", "codigo"=>"carriola", "precio"=>700, "departamento_id"=>4],
                [ "nombre"=>"Cuna", "codigo"=>"cuna", "precio"=>1000, "departamento_id"=>4],
                [ "nombre"=>"Pañales", "codigo"=>"panales", "precio"=>200, "departamento_id"=>4],

                [ "nombre"=>"Camiseta Jr", "codigo"=>"camisetajr", "precio"=>200, "departamento_id"=>5],
                [ "nombre"=>"Zapatos Jr", "codigo"=>"zapatosjr", "precio"=>300, "departamento_id"=>5],
                [ "nombre"=>"Pantalones Jr", "codigo"=>"pantalonesjr", "precio"=>400, "departamento_id"=>5],

                [ "nombre"=>"Jersey deportivo", "codigo"=>"jerseydeportivo", "precio"=>500, "departamento_id"=>6],
                [ "nombre"=>"Balon de futból", "codigo"=>"balonfutbol", "precio"=>500, "departamento_id"=>6],
                [ "nombre"=>"Bicicleta", "codigo"=>"bicicleta", "precio"=>2000, "departamento_id"=>6],
                [ "nombre"=>"Patineta", "codigo"=>"patineta", "precio"=>400, "departamento_id"=>6],

                [ "nombre"=>"Reloj Rolex", "codigo"=>"relojrolex", "precio"=>1500, "departamento_id"=>7],
                [ "nombre"=>"Reloj de oro", "codigo"=>"relojoro", "precio"=>7000, "departamento_id"=>7],
                [ "nombre"=>"Reloj para dama", "codigo"=>"relojdama", "precio"=>1800, "departamento_id"=>7],

                [ "nombre"=>"Consola XBox One S", "codigo"=>"xboxonex", "precio"=>8000, "departamento_id"=>7],
                [ "nombre"=>"Consola Nintendo Switch", "codigo"=>"nintendoswitch", "precio"=>6000, "departamento_id"=>7],
                [ "nombre"=>"Consola Playstation 4", "codigo"=>"playstation4", "precio"=>8000, "departamento_id"=>7],

                [ "nombre"=>"Libro Harry Potter 1", "codigo"=>"libroharrypotter1", "precio"=>200, "departamento_id"=>8],
                [ "nombre"=>"Película Harry Potter 1", "codigo"=>"peliculaharrypotter1", "precio"=>400, "departamento_id"=>8],
                [ "nombre"=>"Musica de Harry Potter 1", "codigo"=>"musicaharrypotter1", "precio"=>100, "departamento_id"=>8],
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
