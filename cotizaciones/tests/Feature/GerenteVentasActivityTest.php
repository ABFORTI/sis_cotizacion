<?php

namespace Tests\Feature;

use App\Models\Cotizacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GerenteVentasActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_gerente_ventas_can_view_sales_activity_dashboard_and_json(): void
    {
        $gerente = User::factory()->create([
            'role' => 'gerente_ventas',
        ]);

        $ventas = User::factory()->create([
            'name' => 'Vendedor Uno',
            'email' => 'ventas1@example.com',
            'role' => 'ventas',
        ]);

        Cotizacion::create([
            'fecha' => now()->toDateString(),
            'no_proyecto' => 'P-001',
            'cliente' => 'Cliente A',
            'nombre_del_proyecto' => 'Proyecto A',
            'user_id' => $ventas->id,
            'enviado_a_costeos' => false,
            'enviado_a_ventas' => false,
        ]);

        Cotizacion::create([
            'fecha' => now()->toDateString(),
            'no_proyecto' => 'P-002',
            'cliente' => 'Cliente A',
            'nombre_del_proyecto' => 'Proyecto B',
            'user_id' => $ventas->id,
            'enviado_a_costeos' => true,
            'enviado_a_ventas' => false,
        ]);

        Cotizacion::create([
            'fecha' => now()->toDateString(),
            'no_proyecto' => 'P-003',
            'cliente' => 'Cliente B',
            'nombre_del_proyecto' => 'Proyecto C',
            'user_id' => $ventas->id,
            'enviado_a_costeos' => true,
            'enviado_a_ventas' => true,
        ]);

        $this->actingAs($gerente)
            ->get('/gerente/ventas')
            ->assertOk()
            ->assertSee('Supervisión');

        $this->actingAs($gerente)
            ->getJson('/gerente/ventas/' . $ventas->id . '/actividad')
            ->assertOk()
            ->assertJson([
                'nombre' => 'Vendedor Uno',
                'correo' => 'ventas1@example.com',
                'total_requisiciones' => 3,
            ])
            ->assertJsonPath('clientes.0.nombre', 'Cliente A')
            ->assertJsonPath('clientes.0.total_cotizaciones', 2)
            ->assertJsonPath('clientes.0.proyectos.0.folio', 'P-001')
            ->assertJsonPath('clientes.0.proyectos.0.nombre_proyecto', 'Proyecto A')
            ->assertJsonPath('clientes.0.proyectos.0.estado_flujo', 'Aún no se manda')
            ->assertJsonPath('clientes.0.proyectos.1.folio', 'P-002')
            ->assertJsonPath('clientes.0.proyectos.1.estado_flujo', 'Ya se mandó')
            ->assertJsonPath('clientes.1.nombre', 'Cliente B')
            ->assertJsonPath('clientes.1.total_cotizaciones', 1)
            ->assertJsonPath('clientes.1.proyectos.0.folio', 'P-003')
            ->assertJsonPath('clientes.1.proyectos.0.estado_flujo', 'Regresada al usuario');
    }

    public function test_gerente_ventas_can_access_sales_module(): void
    {
        $gerente = User::factory()->create([
            'role' => 'gerente_ventas',
        ]);

        $this->actingAs($gerente)
            ->get('/cotizaciones')
            ->assertOk();
    }

    public function test_usuario_ventas_no_puede_entrar_al_modulo_de_supervision(): void
    {
        $ventas = User::factory()->create([
            'role' => 'ventas',
        ]);

        $this->actingAs($ventas)
            ->get('/gerente/ventas')
            ->assertForbidden();
    }
}
