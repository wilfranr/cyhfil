<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Configura el entorno de testing para tests Unit
     * Usa SQLite en memoria para evitar dependencias de base de datos externa
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Configurar SQLite en memoria para tests Unit
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ],
        ]);
    }
}
