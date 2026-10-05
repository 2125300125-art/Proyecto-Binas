<?php

namespace Tests\Feature;

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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ListDatabaseRecordsTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_listing_shows_saved_products_relationship_names_and_id_image(): void
    {
        Storage::fake('public');
        $categoria = Categoria::create(['nombre' => 'Agua purificada']);
        $marca = Marca::create(['nombre' => 'Agua Clara']);
        $presentacion = Presentacion::create(['nombre' => 'Garrafón de 20 litros']);

        $this->post(route('productos.store'), [
            'nombre' => 'Garrafón retornable',
            'categoria_id' => $categoria->id,
            'marca_id' => $marca->id,
            'presentacion_id' => $presentacion->id,
            'precio' => 50,
            'existencia' => 12,
            'imagen' => \Illuminate\Http\UploadedFile::fake()->create('garrafon.jpg', 10, 'image/jpeg'),
        ])->assertRedirect(route('productos.index'));

        $producto = Producto::query()->where('nombre', 'Garrafón retornable')->firstOrFail();
        Storage::disk('public')->assertExists($producto->imagen);

        $this->get(route('productos.listar'))
            ->assertOk()
            ->assertSee('Garrafón retornable')
            ->assertSee('Agua purificada')
            ->assertSee('Agua Clara')
            ->assertSee('Garrafón de 20 litros')
            ->assertSee('storage/' . $producto->imagen)
            ->assertSee('Imagen del registro #' . $producto->id);

        $this->get(route('categorias.listar'))->assertOk()->assertSee('Agua purificada');
        $this->get(route('marcas.listar'))->assertOk()->assertSee('Agua Clara');
        $this->get(route('presentaciones.listar'))->assertOk()->assertSee('Garrafón de 20 litros');
    }

    public function test_order_and_delivery_listings_show_related_records_instead_of_foreign_key_values(): void
    {
        $cliente = Cliente::create([
            'nombres' => 'Lucía',
            'apellidos' => 'Ramírez',
            'correo' => 'lucia@example.test',
            'contraseña' => Hash::make('secreto123'),
            'telefono' => '5551234567',
        ]);
        $metodoPago = MetodoPago::create(['nombre' => 'Efectivo']);
        $direccion = Direccion::create([
            'cliente_id' => $cliente->id,
            'calle' => 'Avenida del Agua',
            'numero' => '25',
            'colonia' => 'Centro',
            'ciudad' => 'Puebla',
            'codigo_postal' => '72000',
        ]);
        $repartidor = Repartidor::create([
            'nombres' => 'Mario',
            'apellidos' => 'López',
            'telefono' => '5559876543',
            'licencia' => 'LIC-AGUA-001',
        ]);
        $pedido = Pedido::create([
            'cliente_id' => $cliente->id,
            'metodo_pago_id' => $metodoPago->id,
            'fecha' => '2026-10-05',
            'total' => 100,
        ]);
        Entrega::create([
            'pedido_id' => $pedido->id,
            'repartidor_id' => $repartidor->id,
            'direccion_id' => $direccion->id,
            'fecha' => '2026-10-06',
            'hora' => '10:30',
        ]);

        $this->get(route('clientes.listar'))->assertOk()->assertSee('Lucía');

        $this->get(route('pedidos.listar'))
            ->assertOk()
            ->assertSee('Lucía Ramírez')
            ->assertSee('Efectivo')
            ->assertDontSee('metodo_pago_id');

        $this->get(route('entregas.listar'))
            ->assertOk()
            ->assertSee('Lucía Ramírez')
            ->assertSee('Mario López')
            ->assertSee('Avenida del Agua 25, Centro, Puebla')
            ->assertDontSee('repartidor_id');
    }

    public function test_order_line_listing_shows_product_name_instead_of_product_id(): void
    {
        $cliente = Cliente::create([
            'nombres' => 'Pedro',
            'apellidos' => 'Soto',
            'correo' => 'pedro@example.test',
            'contraseña' => Hash::make('secreto123'),
            'telefono' => '5551234567',
        ]);
        $metodoPago = MetodoPago::create(['nombre' => 'Transferencia']);
        $pedido = Pedido::create([
            'cliente_id' => $cliente->id,
            'metodo_pago_id' => $metodoPago->id,
            'fecha' => '2026-10-05',
            'total' => 48,
        ]);
        $producto = Producto::create([
            'nombre' => 'Agua purificada 20 L',
            'categoria_id' => Categoria::create(['nombre' => 'Agua'])->id,
            'marca_id' => Marca::create(['nombre' => 'Purísima'])->id,
            'presentacion_id' => Presentacion::create(['nombre' => '20 litros'])->id,
            'precio' => 48,
            'existencia' => 10,
        ]);
        ProductoPedido::create([
            'pedido_id' => $pedido->id,
            'producto_id' => $producto->id,
            'cantidad' => 1,
            'precio' => 48,
        ]);

        $this->get(route('productos_pedido.listar'))
            ->assertOk()
            ->assertSee('Agua purificada 20 L')
            ->assertSee('Pedro Soto')
            ->assertDontSee('producto_id');
    }

    public function test_homepage_links_to_the_catalog_listings_for_the_practice(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee(route('productos.listar'))
            ->assertSee(route('categorias.listar'))
            ->assertSee(route('marcas.listar'))
            ->assertSee(route('presentaciones.listar'))
            ->assertSee(route('clientes.listar'));
    }
}
