<?php

/**
 * Script de verificación para confirmar que el problema de AUTO_INCREMENT se haya resuelto
 * Este script verifica que todas las tablas tengan AUTO_INCREMENT en sus campos ID
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Cargar Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🔍 Verificando corrección de AUTO_INCREMENT en campos ID...\n\n";

// Tablas a verificar
$tablesToCheck = [
    'terceros',
    'tercero_contacto', 
    'tercero_fabricantes',
    'tercero_maquina',
    'tercero_sistemas',
    'tercero_marcas'
];

$allFixed = true;

foreach ($tablesToCheck as $table) {
    if (!Schema::hasTable($table)) {
        echo "⚠️  Tabla {$table} no existe\n";
        continue;
    }
    
    try {
        // Verificar estructura del campo id
        $columnInfo = DB::select("
            SELECT 
                COLUMN_NAME,
                COLUMN_DEFAULT,
                IS_NULLABLE,
                EXTRA,
                COLUMN_TYPE
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = '{$table}' 
            AND COLUMN_NAME = 'id'
        ");
        
        if (empty($columnInfo)) {
            echo "❌ Tabla {$table}: Campo 'id' no encontrado\n";
            $allFixed = false;
            continue;
        }
        
        $column = $columnInfo[0];
        $hasAutoIncrement = str_contains($column->EXTRA ?? '', 'auto_increment');
        $hasPrimaryKey = str_contains($column->EXTRA ?? '', 'auto_increment');
        
        if ($hasAutoIncrement) {
            echo "✅ Tabla {$table}: Campo 'id' tiene AUTO_INCREMENT\n";
        } else {
            echo "❌ Tabla {$table}: Campo 'id' NO tiene AUTO_INCREMENT\n";
            $allFixed = false;
        }
        
        // Verificar PRIMARY KEY
        $primaryKeyInfo = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = '{$table}' 
            AND CONSTRAINT_TYPE = 'PRIMARY KEY'
        ");
        
        if (!empty($primaryKeyInfo)) {
            echo "   ✅ Tabla {$table}: Tiene PRIMARY KEY\n";
        } else {
            echo "   ❌ Tabla {$table}: NO tiene PRIMARY KEY\n";
            $allFixed = false;
        }
        
    } catch (Exception $e) {
        echo "❌ Error al verificar tabla {$table}: " . $e->getMessage() . "\n";
        $allFixed = false;
    }
}

echo "\n";

// Verificar restricciones de clave foránea para terceros
echo "🔍 Verificando restricciones de clave foránea...\n";

try {
    $foreignKeys = DB::select("
        SELECT 
            TABLE_NAME,
            CONSTRAINT_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE REFERENCED_TABLE_SCHEMA = DATABASE() 
        AND REFERENCED_TABLE_NAME = 'terceros' 
        AND REFERENCED_COLUMN_NAME = 'id'
        ORDER BY TABLE_NAME
    ");
    
    if (!empty($foreignKeys)) {
        echo "✅ Se encontraron " . count($foreignKeys) . " restricciones de clave foránea activas\n";
        foreach ($foreignKeys as $fk) {
            echo "   - {$fk->TABLE_NAME}.{$fk->CONSTRAINT_NAME} → {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
        }
    } else {
        echo "⚠️  No se encontraron restricciones de clave foránea para terceros\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error al verificar restricciones de clave foránea: " . $e->getMessage() . "\n";
}

echo "\n";

// Resumen final
if ($allFixed) {
    echo "🎉 ¡PROBLEMA RESUELTO! Todos los campos ID tienen AUTO_INCREMENT correctamente configurado.\n";
    echo "✅ El error 'Field id doesn\'t have a default value' ya no debería ocurrir.\n";
} else {
    echo "⚠️  AÚN HAY PROBLEMAS: Algunas tablas no tienen AUTO_INCREMENT configurado correctamente.\n";
    echo "🔧 Ejecuta nuevamente la migración o el script SQL para completar la corrección.\n";
}

echo "\n";

// Probar inserción en tabla terceros
echo "🧪 Probando inserción en tabla terceros...\n";

try {
    $testData = [
        'nombre' => 'Test Tercero',
        'tipo_documento' => 'NIT',
        'numero_documento' => 'TEST123',
        'direccion' => 'Dirección de prueba',
        'telefono' => '123456789',
        'email' => 'test@example.com',
        'estado' => 'activo',
        'tipo' => 'Cliente'
    ];
    
    $id = DB::table('terceros')->insertGetId($testData);
    
    if ($id > 0) {
        echo "✅ Inserción exitosa! ID generado: {$id}\n";
        
        // Limpiar datos de prueba
        DB::table('terceros')->where('id', $id)->delete();
        echo "✅ Datos de prueba eliminados\n";
    } else {
        echo "❌ Inserción falló - no se generó ID\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error en inserción de prueba: " . $e->getMessage() . "\n";
    echo "🔧 El problema de AUTO_INCREMENT aún no está completamente resuelto.\n";
}

echo "\n✨ Verificación completada.\n";
