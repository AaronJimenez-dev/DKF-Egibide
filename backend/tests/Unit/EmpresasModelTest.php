<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Empresas;

class EmpresasModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_se_puede_crear_empresa(): void
    {
        $empresa = Empresas::create([
            'nombre' => 'Empresa Unit',
            'cif' => 'B12345678',
            'direccion' => 'Dirección Test',
            'telefono' => '699999999',
            'email' => 'unit@empresa.com',
        ]);

        $this->assertDatabaseHas('empresas', [
            'id' => $empresa->id,
            'nombre' => 'Empresa Unit',
            'cif' => 'B12345678',
        ]);
    }

    public function test_campos_fillable_funcionan(): void
    {
        $empresa = Empresas::create([
            'nombre' => 'Empresa Fillable',
            'cif' => 'A11111111',
            'direccion' => 'Dirección',
            'telefono' => '611111111',
            'email' => 'fillable@empresa.com',
        ]);

        $this->assertEquals('Empresa Fillable', $empresa->nombre);
        $this->assertEquals('A11111111', $empresa->cif);
        $this->assertEquals('fillable@empresa.com', $empresa->email);
    }
}
