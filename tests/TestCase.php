<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * Configura el entorno de testing para tests Feature
     * Usa MySQL testing para simular el entorno de producción
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Configurar MySQL testing para tests Feature
        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'testing',
        ]);
    }
}
