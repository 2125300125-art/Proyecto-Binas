<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_module_pages_render(): void
    {
        foreach ($this->modules() as $module) {
            $this->get(route("{$module}.listar"))->assertOk();
            $this->get(route("{$module}.crear"))->assertOk();
            $this->get(route("{$module}.editar", 1))->assertOk();
            $this->get(route("{$module}.mostrar", 1))->assertOk();
        }
    }

    public function test_module_actions_redirect_without_persisting(): void
    {
        foreach ($this->simulatedModules() as $module) {
            $listRoute = route("{$module}.listar");

            $this->post(route("{$module}.guardar"))
                ->assertRedirect($listRoute)
                ->assertSessionHas('success');

            $this->put(route("{$module}.actualizar", 1))
                ->assertRedirect($listRoute)
                ->assertSessionHas('success');

            $this->delete(route("{$module}.borrar", 1))
                ->assertRedirect($listRoute)
                ->assertSessionHas('success');
        }
    }

    private function modules(): array
    {
        return [
            'administradores',
            'productos',
            'clientes',
            'pedidos',
            'repartidores',
            'entregas',
            'categorias',
            'marcas',
            'presentaciones',
            'direcciones',
            'metodos_pago',
            'roles',
        ];
    }

    private function simulatedModules(): array
    {
        return array_values(array_diff($this->modules(), [
            'productos',
            'clientes',
            'categorias',
            'marcas',
            'presentaciones',
        ]));
    }
}
