<?php

namespace Database\Seeders;

use App\Models\Administrador;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Direccion;
use App\Models\Entrega;
use App\Models\Marca;
use App\Models\MetodoPago;
use App\Models\Pedido;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\ProductoPedido;
use App\Models\Repartidor;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles
        $rolAdmin = Rol::firstOrCreate(['nombre' => 'Administrador'], ['estado' => true]);
        $rolOperador = Rol::firstOrCreate(['nombre' => 'Operador'], ['estado' => true]);
        $rolRepartidor = Rol::firstOrCreate(['nombre' => 'Repartidor'], ['estado' => true]);

        // 2. Usuarios del sistema
        User::firstOrCreate(
            ['email' => 'jose.jbjh28@gmail.com'],
            ['name' => 'Fernando Jiménez', 'password' => Hash::make('password123')]
        );
        User::firstOrCreate(
            ['email' => 'jose.jbjh2002@gmail.com'],
            ['name' => 'Jose Jiménez', 'password' => Hash::make('password123')]
        );

        // 3. Administradores
        Administrador::firstOrCreate(
            ['correo' => 'fernando.admin@aguaclara.test'],
            [
                'nombre' => 'Fernando',
                'apellidos' => 'Jiménez',
                'usuario' => 'fjimenez',
                'contraseña' => Hash::make('password123'),
                'rol_id' => $rolAdmin->id,
                'estado' => true,
            ]
        );
        Administrador::firstOrCreate(
            ['correo' => 'mariana.lopez@aguaclara.test'],
            [
                'nombre' => 'Mariana',
                'apellidos' => 'López Rivera',
                'usuario' => 'mlopez',
                'contraseña' => Hash::make('password123'),
                'rol_id' => $rolAdmin->id,
                'estado' => true,
            ]
        );

        // 4. Categorías
        $catPurificada = Categoria::firstOrCreate(['nombre' => 'Agua Purificada'], ['estado' => true]);
        $catAlcalina = Categoria::firstOrCreate(['nombre' => 'Agua Alcalina'], ['estado' => true]);
        $catAccesorios = Categoria::firstOrCreate(['nombre' => 'Accesorios y Dispensadores'], ['estado' => true]);

        // 5. Marcas
        $marcaBonafont = Marca::firstOrCreate(['nombre' => 'Bonafont'], ['estado' => true]);
        $marcaCiel = Marca::firstOrCreate(['nombre' => 'Ciel'], ['estado' => true]);
        $marcaEpura = Marca::firstOrCreate(['nombre' => 'Epura'], ['estado' => true]);

        // 6. Presentaciones
        $presGarrafon = Presentacion::firstOrCreate(
            ['nombre' => 'Garrafón retornable 20 L'],
            ['descripcion' => 'Envase retornable de policarbonato reforzado para 20 litros.', 'estado' => true]
        );
        $presBotella = Presentacion::firstOrCreate(
            ['nombre' => 'Botella individual 1.5 L'],
            ['descripcion' => 'Botella desechable de 1.5 litros para uso diario.', 'estado' => true]
        );
        $presGarrafon10 = Presentacion::firstOrCreate(
            ['nombre' => 'Garrafón de agua 10 L'],
            ['descripcion' => 'Envase retornable mediano de 10 litros.', 'estado' => true]
        );

        // 7. Productos
        $prod1 = Producto::firstOrCreate(
            ['nombre' => 'Garrafón Bonafont 20 L'],
            [
                'descripcion' => 'Agua ligera de manantial, garrafón de 20 litros.',
                'categoria_id' => $catPurificada->id,
                'marca_id' => $marcaBonafont->id,
                'presentacion_id' => $presGarrafon->id,
                'precio' => 52.00,
                'existencia' => 50,
                'descuento' => 0.00,
                'estado' => true,
            ]
        );
        $prod2 = Producto::firstOrCreate(
            ['nombre' => 'Garrafón Ciel 20 L'],
            [
                'descripcion' => 'Agua purificada enriquecida con minerales.',
                'categoria_id' => $catPurificada->id,
                'marca_id' => $marcaCiel->id,
                'presentacion_id' => $presGarrafon->id,
                'precio' => 48.00,
                'existencia' => 40,
                'descuento' => 2.00,
                'estado' => true,
            ]
        );
        $prod3 = Producto::firstOrCreate(
            ['nombre' => 'Botella Epura 1.5 L'],
            [
                'descripcion' => 'Agua purificada baja en sodio 1.5 L.',
                'categoria_id' => $catPurificada->id,
                'marca_id' => $marcaEpura->id,
                'presentacion_id' => $presBotella->id,
                'precio' => 16.50,
                'existencia' => 100,
                'descuento' => 0.00,
                'estado' => true,
            ]
        );

        // 8. Clientes
        $cliente1 = Cliente::firstOrCreate(
            ['correo' => 'jose.jbjh28@gmail.com'],
            [
                'nombres' => 'fernando',
                'apellidos' => 'Jimenez',
                'contraseña' => Hash::make('password123'),
                'telefono' => '3310208143',
                'estado' => true,
            ]
        );
        $cliente2 = Cliente::firstOrCreate(
            ['correo' => 'jose.jbjh2002@gmail.com'],
            [
                'nombres' => 'Jose',
                'apellidos' => 'Jimenez',
                'contraseña' => Hash::make('password123'),
                'telefono' => '3322211326',
                'estado' => true,
            ]
        );
        $cliente3 = Cliente::firstOrCreate(
            ['correo' => 'ana.ramirez@correo.test'],
            [
                'nombres' => 'Ana Sofía',
                'apellidos' => 'Ramírez Cruz',
                'contraseña' => Hash::make('password123'),
                'telefono' => '5552468101',
                'estado' => true,
            ]
        );

        // 9. Direcciones
        $dir1 = Direccion::firstOrCreate(
            ['cliente_id' => $cliente1->id, 'calle' => 'Avenida Reforma', 'numero' => '120'],
            [
                'colonia' => 'Centro',
                'ciudad' => 'Guadalajara',
                'codigo_postal' => '44100',
                'referencia' => 'Casa blanca con cancel negro',
                'estado' => true,
            ]
        );
        $dir2 = Direccion::firstOrCreate(
            ['cliente_id' => $cliente2->id, 'calle' => 'Calle Lago', 'numero' => '45'],
            [
                'colonia' => 'Jardines',
                'ciudad' => 'Guadalajara',
                'codigo_postal' => '44520',
                'referencia' => 'Frente al parque municipal',
                'estado' => true,
            ]
        );

        // 10. Métodos de pago
        $metodo1 = MetodoPago::firstOrCreate(
            ['nombre' => 'Efectivo'],
            ['descripcion' => 'Pago contra entrega en efectivo al recibir el pedido.', 'estado' => true]
        );
        $metodo2 = MetodoPago::firstOrCreate(
            ['nombre' => 'Transferencia bancaria'],
            ['descripcion' => 'Transferencia SPEI previa a la entrega.', 'estado' => true]
        );
        $metodo3 = MetodoPago::firstOrCreate(
            ['nombre' => 'Tarjeta de crédito / débito'],
            ['descripcion' => 'Pago mediante terminal bancaria al momento de la entrega.', 'estado' => true]
        );

        // 11. Repartidores
        $rep1 = Repartidor::firstOrCreate(
            ['licencia' => 'LIC-AG-2041'],
            [
                'nombres' => 'Carlos',
                'apellidos' => 'Mendoza Ruiz',
                'telefono' => '3311223344',
                'estado' => true,
            ]
        );
        $rep2 = Repartidor::firstOrCreate(
            ['licencia' => 'LIC-AG-1850'],
            [
                'nombres' => 'Patricia',
                'apellidos' => 'Flores Vega',
                'telefono' => '3355443322',
                'estado' => true,
            ]
        );

        // 12. Pedidos
        $pedido1 = Pedido::firstOrCreate(
            ['cliente_id' => $cliente1->id, 'fecha' => date('Y-m-d'), 'total' => 104.00],
            [
                'metodo_pago_id' => $metodo1->id,
                'iva' => 14.34,
                'descuento' => 0.00,
                'estado' => true,
            ]
        );
        $pedido2 = Pedido::firstOrCreate(
            ['cliente_id' => $cliente2->id, 'fecha' => date('Y-m-d'), 'total' => 96.00],
            [
                'metodo_pago_id' => $metodo2->id,
                'iva' => 13.24,
                'descuento' => 2.00,
                'estado' => true,
            ]
        );

        // 13. Productos por pedido
        ProductoPedido::firstOrCreate(
            ['pedido_id' => $pedido1->id, 'producto_id' => $prod1->id],
            [
                'cantidad' => 2,
                'precio' => $prod1->precio,
                'descuento' => 0.00,
                'estado' => true,
            ]
        );
        ProductoPedido::firstOrCreate(
            ['pedido_id' => $pedido2->id, 'producto_id' => $prod2->id],
            [
                'cantidad' => 2,
                'precio' => $prod2->precio,
                'descuento' => 2.00,
                'estado' => true,
            ]
        );

        // 14. Entregas
        Entrega::firstOrCreate(
            ['pedido_id' => $pedido1->id, 'repartidor_id' => $rep1->id],
            [
                'direccion_id' => $dir1->id,
                'fecha' => date('Y-m-d'),
                'hora' => '10:30:00',
                'estado' => true,
            ]
        );
        Entrega::firstOrCreate(
            ['pedido_id' => $pedido2->id, 'repartidor_id' => $rep2->id],
            [
                'direccion_id' => $dir2->id,
                'fecha' => date('Y-m-d'),
                'hora' => '12:00:00',
                'estado' => true,
            ]
        );
    }
}
