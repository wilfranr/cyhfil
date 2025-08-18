<?php

namespace Tests\Unit;

use Tests\TestCase;

class RedondeoTest extends TestCase
{
    /** @test */
    public function entiende_comportamiento_round()
    {
        // Verificar cómo funciona round() con diferentes valores
        $this->assertEquals(142800, round(142847, -2));
        $this->assertEquals(142900, round(142899, -2));
        $this->assertEquals(142900, round(142850, -2)); // 142850 redondea a 142900
        $this->assertEquals(142900, round(142900, -2));
        
        // Verificar que round() funciona como esperamos
        $this->assertEquals(100, round(147, -2));
        $this->assertEquals(200, round(199, -2));
        $this->assertEquals(200, round(200, -2));
    }
}
