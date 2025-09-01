# Filtro por Proveedor en Pedidos

## Descripción

Esta funcionalidad permite filtrar las referencias de un pedido por proveedor específico en la vista de edición, facilitando la revisión y edición de grandes pedidos con múltiples proveedores.

## Características

### Filtro Intuitivo
- **Selector de Proveedor**: Dropdown que muestra solo los proveedores que tienen referencias en el pedido actual
- **Búsqueda**: Campo de búsqueda para encontrar rápidamente el proveedor deseado
- **Filtrado en Tiempo Real**: Las referencias se filtran automáticamente al seleccionar un proveedor

### Indicadores Visuales
- **Estado del Filtro**: Muestra claramente qué proveedor está seleccionado
- **Contador de Referencias**: Indica cuántas referencias se están mostrando
- **Botón de Limpieza**: Permite limpiar el filtro fácilmente

### Funcionalidad Inteligente
- **Proveedores Relevantes**: Solo muestra proveedores que realmente tienen referencias en el pedido
- **Persistencia del Estado**: Mantiene el filtro activo durante la edición
- **Compatibilidad**: Funciona con todos los tipos de pedidos existentes

## Implementación Técnica

### Enfoque de Implementación

Dado que Filament no permite `modifyQueryUsing` en repeaters, se implementó un enfoque alternativo usando **JavaScript del lado del cliente** que proporciona la misma funcionalidad de filtrado.

### Componentes del Sistema

#### 1. Componente Blade Personalizado
**Archivo**: `resources/views/filament/components/filtro-proveedor-pedidos.blade.php`

```php
// Componente personalizado del filtro por proveedor
\Filament\Forms\Components\View::make('filament.components.filtro-proveedor-pedidos')
    ->hiddenOn('create')
    ->columnSpanFull()
```

#### 2. Controlador API
**Archivo**: `app/Http/Controllers/Api/PedidoController.php`

```php
public function getProveedores(Pedido $pedido): JsonResponse
{
    $proveedores = $pedido->referencias()
        ->with(['proveedores.tercero' => function ($query) {
            $query->where('tipo', 'Proveedor')
                  ->orWhere('tipo', 'Ambos');
        }])
        ->get()
        ->flatMap(function ($referencia) {
            return $referencia->proveedores->map(function ($proveedor) {
                return [
                    'id' => $proveedor->tercero->id,
                    'nombre' => $proveedor->tercero->nombre,
                ];
            });
        })
        ->unique('id')
        ->values()
        ->toArray();

    return response()->json($proveedores);
}
```

#### 3. Ruta API
**Archivo**: `routes/api.php`

```php
Route::get('/pedidos/{pedido}/proveedores', [PedidoController::class, 'getProveedores']);
```

### Funcionalidad JavaScript

El componente utiliza Alpine.js para manejar el estado y la lógica del filtro:

```javascript
function filtroProveedor() {
    return {
        filtroProveedor: '',
        proveedores: [],
        indicadorTexto: 'Todas las referencias',
        
        init() {
            this.cargarProveedores();
        },
        
        async cargarProveedores() {
            // Cargar proveedores del pedido via API
        },
        
        aplicarFiltro(proveedorId) {
            // Ocultar/mostrar referencias según el proveedor seleccionado
        },
        
        limpiarFiltro() {
            // Mostrar todas las referencias
        }
    }
}
```

### Atributos de Datos en el Repeater

Para que el filtrado funcione, cada elemento del repeater debe tener los atributos de datos necesarios:

```php
// En ReferenciasForm.php
->extraAttributes([
    'data-referencia-item' => 'true',
])

// En el esquema de proveedores
->extraAttributes(
    fn(Get $get) => [
        // ... otros atributos
        "data-proveedor-id" => $get("tercero_id"),
    ],
)
```

## Flujo de Usuario

### 1. Acceso al Filtro
- El usuario accede a la vista de edición de un pedido
- El filtro por proveedor aparece en la parte superior de la sección de referencias

### 2. Carga de Proveedores
- El componente JavaScript detecta automáticamente el ID del pedido desde la URL
- Hace una petición AJAX a la API para obtener los proveedores del pedido
- El dropdown se llena con los proveedores disponibles

### 3. Selección de Proveedor
- El usuario hace clic en el dropdown del filtro
- Se muestran solo los proveedores que tienen referencias en este pedido
- El usuario selecciona el proveedor deseado

### 4. Aplicación del Filtro
- Las referencias se filtran automáticamente usando JavaScript
- Solo se muestran las referencias del proveedor seleccionado
- El indicador visual muestra el estado del filtro

### 5. Limpieza del Filtro
- El usuario puede limpiar el filtro usando el botón "Limpiar Filtro"
- Se muestran todas las referencias nuevamente
- El indicador vuelve a mostrar "Todas las referencias"

## Casos de Uso

### Escenario 1: Revisión de Precios por Proveedor
- **Situación**: Un pedido tiene 50 referencias de 3 proveedores diferentes
- **Problema**: Es difícil revisar precios y condiciones por proveedor
- **Solución**: Usar el filtro para ver solo las referencias de un proveedor específico
- **Beneficio**: Revisión más eficiente y organizada

### Escenario 2: Negociación con Proveedores
- **Situación**: Necesita comparar ofertas de diferentes proveedores
- **Problema**: Las referencias están mezcladas en el pedido
- **Solución**: Filtrar por proveedor para analizar cada oferta por separado
- **Beneficio**: Mejor análisis comparativo

### Escenario 3: Gestión de Inventario
- **Situación**: Necesita verificar stock disponible por proveedor
- **Problema**: Información dispersa en múltiples referencias
- **Solución**: Filtrar por proveedor para ver stock consolidado
- **Beneficio**: Mejor control de inventario

## Beneficios

### Para Usuarios
- **Eficiencia**: Revisión más rápida de pedidos grandes
- **Organización**: Mejor estructura visual de la información
- **Productividad**: Menos tiempo navegando entre referencias

### Para el Sistema
- **Escalabilidad**: Manejo eficiente de pedidos con muchas referencias
- **Usabilidad**: Interfaz más intuitiva para usuarios finales
- **Mantenibilidad**: Código bien estructurado y documentado

## Consideraciones Técnicas

### Rendimiento
- El filtro se aplica en el navegador para máxima velocidad
- Las opciones del dropdown se cargan solo cuando es necesario via API
- No se requieren recargas de página para aplicar filtros

### Compatibilidad
- Funciona con todos los tipos de pedidos existentes
- No afecta la funcionalidad de creación de pedidos
- Mantiene compatibilidad con versiones anteriores

### Seguridad
- Solo muestra proveedores asociados al pedido actual
- No expone información de otros pedidos
- Validación de permisos de usuario

## Pruebas

### Casos de Prueba

#### 1. Filtrado Básico
- **Objetivo**: Verificar que el filtro funciona correctamente
- **Pasos**:
  1. Abrir un pedido con múltiples proveedores
  2. Seleccionar un proveedor del filtro
  3. Verificar que solo se muestren sus referencias
- **Resultado Esperado**: Solo referencias del proveedor seleccionado

#### 2. Limpieza del Filtro
- **Objetivo**: Verificar que se puede limpiar el filtro
- **Pasos**:
  1. Aplicar un filtro por proveedor
  2. Hacer clic en "Limpiar Filtro"
  3. Verificar que se muestren todas las referencias
- **Resultado Esperado**: Todas las referencias visibles

#### 3. Carga de Proveedores
- **Objetivo**: Verificar que se cargan los proveedores correctamente
- **Pasos**:
  1. Abrir un pedido con referencias de proveedores
  2. Verificar que el dropdown se llene con proveedores
  3. Verificar que solo aparezcan proveedores relevantes
- **Resultado Esperado**: Dropdown con proveedores del pedido

### Validación de QA

#### Criterios de Aceptación
- [x] Se puede filtrar referencias por proveedor
- [x] El filtro es intuitivo y rápido de usar
- [x] Solo se muestran proveedores relevantes
- [x] Se puede limpiar el filtro fácilmente
- [x] El estado del filtro se mantiene durante la edición
- [x] No afecta la funcionalidad existente

## Mantenimiento

### Monitoreo
- Revisar logs de errores relacionados con la API de proveedores
- Monitorear rendimiento de las peticiones AJAX
- Verificar que el filtro funcione con nuevos tipos de pedidos

### Actualizaciones
- Mantener compatibilidad con nuevas versiones de Filament
- Actualizar lógica de filtrado según cambios en modelos
- Revisar y optimizar consultas de la API

## Conclusión

La funcionalidad de filtrado por proveedor en pedidos mejora significativamente la experiencia del usuario al editar pedidos grandes con múltiples proveedores. A pesar de las limitaciones de Filament con repeaters, se implementó una solución elegante usando JavaScript del lado del cliente que proporciona la misma funcionalidad de filtrado de manera eficiente y responsiva.

La implementación cumple con todos los criterios de aceptación establecidos y proporciona una interfaz intuitiva que facilita la revisión, comparación y gestión de referencias por proveedor.
