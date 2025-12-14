<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Leer el archivo JSON
        $jsonPath = base_path('docs/categories.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error("El archivo {$jsonPath} no existe.");
            return;
        }

        $jsonContent = File::get($jsonPath);
        
        // Limpiar posibles caracteres BOM o espacios al inicio
        $jsonContent = trim($jsonContent);
        
        // Limpiar marcadores de cita que pueden estar en el JSON
        // Remover [cite_start] que aparece antes de las claves JSON (ej: [cite_start]"descripcion_general")
        $jsonContent = preg_replace('/\[cite_start\]"/', '"', $jsonContent);
        // Remover [cite_start] que aparece en cualquier lugar
        $jsonContent = preg_replace('/\[cite_start\]/', '', $jsonContent);
        // Remover [cite: ...] del contenido
        $jsonContent = preg_replace('/\s*\[cite:\s*[^\]]+\]/', '', $jsonContent);
        
        // Parsear el JSON
        $data = json_decode($jsonContent, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error("Error al parsear el JSON: " . json_last_error_msg());
            $this->command->error("Posición del error: " . json_last_error());
            return;
        }

        if (!isset($data['categorias']) || !is_array($data['categorias'])) {
            $this->command->error("El JSON no contiene un array 'categorias' válido.");
            return;
        }

        $this->command->info("Iniciando inserción de categorías y subcategorías...");

        // Limpiar tablas antes de insertar
        // Deshabilitar temporalmente las foreign keys para poder hacer truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('subcategorias_landing')->truncate();
        DB::table('categorias_landing')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categoriasCount = 0;
        $subcategoriasCount = 0;

        foreach ($data['categorias'] as $categoriaData) {
            // Validar que tenga nombre
            if (!isset($categoriaData['nombre']) || empty($categoriaData['nombre'])) {
                $this->command->warn("Se omitió una categoría sin nombre.");
                continue;
            }

            // Limpiar marcadores de cita de las descripciones
            $descripcionGeneral = $categoriaData['descripcion_general'] ?? null;
            if ($descripcionGeneral) {
                $descripcionGeneral = preg_replace('/\s*\[cite:\s*[^\]]+\]/', '', $descripcionGeneral);
                $descripcionGeneral = preg_replace('/\s*\[cite_start\]/', '', $descripcionGeneral);
                $descripcionGeneral = trim($descripcionGeneral);
            }

            // Insertar categoría
            $categoriaId = DB::table('categorias_landing')->insertGetId([
                'nombre' => $categoriaData['nombre'],
                'descripcion_general' => $descripcionGeneral,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $categoriasCount++;
            $this->command->info("Categoría creada: {$categoriaData['nombre']}");

            // Insertar subcategorías
            if (isset($categoriaData['subcategorias']) && is_array($categoriaData['subcategorias'])) {
                $subcategoriaIndex = 0;
                foreach ($categoriaData['subcategorias'] as $subcategoriaData) {
                    // Validar que tenga nombre
                    if (!isset($subcategoriaData['nombre']) || empty($subcategoriaData['nombre'])) {
                        $this->command->warn("  └─ Se omitió una subcategoría sin nombre.");
                        continue;
                    }

                    // Limpiar marcadores de cita de las descripciones
                    $descripcion = $subcategoriaData['descripcion'] ?? null;
                    if ($descripcion) {
                        $descripcion = preg_replace('/\s*\[cite:\s*[^\]]+\]/', '', $descripcion);
                        $descripcion = preg_replace('/\s*\[cite_start\]/', '', $descripcion);
                        $descripcion = trim($descripcion);
                    }

                    // Las primeras 4 subcategorías se mostrarán en el navbar
                    $mostrarEnNavbar = $subcategoriaIndex < 4;
                    $ordenNavbar = $mostrarEnNavbar ? ($subcategoriaIndex + 1) : null;

                    // Obtener imagen del config usando el slug
                    $slug = \Illuminate\Support\Str::slug($subcategoriaData['nombre']);
                    $imageMap = config('productos_imagenes');
                    $imagen = $imageMap[$slug] ?? $imageMap['default'];

                    DB::table('subcategorias_landing')->insert([
                        'categoria_id' => $categoriaId,
                        'nombre' => $subcategoriaData['nombre'],
                        'descripcion' => $descripcion,
                        'imagen' => $imagen,
                        'mostrar_en_navbar' => $mostrarEnNavbar,
                        'orden_navbar' => $ordenNavbar,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $subcategoriasCount++;
                    $subcategoriaIndex++;
                    $this->command->info("  └─ Subcategoría creada: {$subcategoriaData['nombre']}" . ($mostrarEnNavbar ? ' (Navbar)' : ''));
                }
            }
        }

        $this->command->info("¡Proceso completado exitosamente!");
        $this->command->info("Total de categorías insertadas: {$categoriasCount}");
        $this->command->info("Total de subcategorías insertadas: {$subcategoriasCount}");
    }
}
