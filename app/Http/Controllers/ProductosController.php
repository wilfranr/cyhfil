<?php

namespace App\Http\Controllers;

use App\Models\CategoriaLanding;
use App\Models\SubcategoriaLanding;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    /**
     * Mostrar la página de productos con todas las categorías
     */
    public function index()
    {
        // Obtener todas las categorías con sus subcategorías
        $categorias = CategoriaLanding::with('subcategorias')->get();
        
        return view('products', compact('categorias'));
    }
    
    /**
     * Mostrar el detalle de una subcategoría específica
     */
    public function show($categoriaSlug, $subcategoriaSlug)
    {
        // Buscar la categoría con sus subcategorías por slug
        $categoria = CategoriaLanding::with('subcategorias')->get()->first(function($cat) use ($categoriaSlug) {
            return $cat->slug === $categoriaSlug;
        });
        
        if (!$categoria) {
            abort(404, 'Categoría no encontrada');
        }
        
        // Buscar la subcategoría dentro de esa categoría por slug
        $subcategoria = $categoria->subcategorias->first(function($sub) use ($subcategoriaSlug) {
            return $sub->slug === $subcategoriaSlug;
        });
        
        if (!$subcategoria) {
            abort(404, 'Subcategoría no encontrada');
        }
        
        // Cargar la relación de categoría en la subcategoría
        $subcategoria->setRelation('categoria', $categoria);
        
        return view('product-detail', compact('subcategoria'));
    }
    
    /**
     * Método helper para obtener categorías del navbar
     * (usado por ViewComposer)
     */
    public static function getCategoriasNavbar()
    {
        return CategoriaLanding::with(['subcategorias' => function($q) {
            $q->limit(4);
        }])->limit(5)->get();
    }
}
