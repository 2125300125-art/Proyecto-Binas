<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Marca;
use App\Models\Presentacion;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_a_brand(): void
    {
        $this->post(route('marcas.store'), ['nombre' => 'Marca de prueba'])
            ->assertRedirect(route('marcas.index'))
            ->assertSessionHas('mensaje', 'Registro guardado exitosamente.');

        $this->assertDatabaseHas('marcas', ['nombre' => 'Marca de prueba']);
        $this->get(route('marcas.index'))->assertOk()->assertSee('Marca de prueba');
    }

    public function test_it_stores_a_category(): void
    {
        $this->post(route('categorias.store'), ['nombre' => 'Categoría de prueba'])
            ->assertRedirect(route('categorias.index'))
            ->assertSessionHas('mensaje');

        $this->assertDatabaseHas('categorias', ['nombre' => 'Categoría de prueba']);
    }

    public function test_it_stores_a_presentation_with_its_description(): void
    {
        $this->post(route('presentaciones.store'), [
            'nombre' => 'Botella 1 L',
            'descripcion' => 'Envase de un litro',
        ])->assertRedirect(route('presentaciones.index'));

        $this->assertDatabaseHas('presentaciones', [
            'nombre' => 'Botella 1 L',
            'descripcion' => 'Envase de un litro',
        ]);
    }

    public function test_it_stores_a_client_with_a_hashed_password(): void
    {
        $this->post(route('clientes.store'), [
            'nombres' => 'Ana',
            'apellidos' => 'Ramírez',
            'correo' => 'ana@example.test',
            'telefono' => '5551234567',
            'contraseña' => 'secreto123',
        ])->assertRedirect(route('clientes.index'));

        $cliente = Cliente::query()->where('correo', 'ana@example.test')->firstOrFail();
        $this->assertTrue(Hash::check('secreto123', $cliente->getAttribute('contraseña')));
    }

    public function test_it_stores_a_product_and_its_uploaded_image(): void
    {
        Storage::fake('public');
        $categoria = Categoria::create(['nombre' => 'Agua']);
        $marca = Marca::create(['nombre' => 'Marca']);
        $presentacion = Presentacion::create(['nombre' => '20 L']);

        $this->get(route('productos.create'))
            ->assertOk()
            ->assertSee('name="categoria_id"', false)
            ->assertSee('name="marca_id"', false)
            ->assertSee('name="presentacion_id"', false);

        $this->post(route('productos.store'), [
            'nombre' => 'Garrafón de agua',
            'categoria_id' => $categoria->id,
            'marca_id' => $marca->id,
            'presentacion_id' => $presentacion->id,
            'precio' => 48,
            'existencia' => 5,
            'imagen' => UploadedFile::fake()->create('garrafon.jpg', 10, 'image/jpeg'),
        ])->assertRedirect(route('productos.index'));

        $producto = Producto::query()->where('nombre', 'Garrafón de agua')->firstOrFail();
        $this->assertNotNull($producto->imagen);
        Storage::disk('public')->assertExists($producto->imagen);
    }

    public function test_invalid_input_returns_to_the_form_with_errors_and_old_values(): void
    {
        $this->from(route('marcas.create'))
            ->post(route('marcas.store'), ['nombre' => ''])
            ->assertRedirect(route('marcas.create'))
            ->assertSessionHasErrors('nombre')
            ->assertSessionHas('_old_input.nombre', '');
    }
}
