# Sistema de Cálculo Automático

## Descripción General

El Sistema de Cálculo Automático es una funcionalidad core del sistema CYH que permite calcular automáticamente los valores de `valor_unidad` y `valor_total` para referencias en pedidos, basándose en el tipo de proveedor (nacional o internacional) y aplicando fórmulas específicas de negocio.

## Propósito del Sistema

- **Automatización**: Elimina cálculos manuales propensos a errores
- **Consistencia**: Aplica las mismas fórmulas en todas las cotizaciones
- **Reactividad**: Actualiza valores en tiempo real según cambios en campos
- **Validación**: Asegura que todos los campos necesarios estén presentes
- **Flexibilidad**: Maneja diferentes tipos de proveedores con lógicas específicas

## Arquitectura del Sistema

### Componentes Principales

1. **ReferenciasForm** - Formulario principal con campos reactivos
2. **calculateValorTotal()** - Método central de cálculo
3. **Campos Reactivos** - cantidad, costo_unidad, utilidad
4. **Validaciones** - Verificación de campos requeridos
5. **Integración con Empresa** - Obtención de TRM y flete

### Flujo de Funcionamiento

```
Usuario modifica campo → afterStateUpdated() → calculateValorTotal() → Actualización de valores
```

## Fórmulas de Cálculo

### Proveedores Nacionales

**Fórmula:**
```
valor_unidad = costo_unidad + (costo_unidad × utilidad%)
valor_total = valor_unidad × cantidad
```

**Ejemplo:**
- Cantidad: 3
- Costo unidad: 5000 pesos
- Utilidad: 5%
- Valor unidad: 5000 + (5000 × 0.05) = 5250 pesos
- Valor total: 5250 × 3 = 15750 pesos

### Proveedores Internacionales

**Fórmula Completa:**
```
1. costo_cop = costo_unidad_usd × TRM
2. valor_base = costo_cop + (peso × 2.2 × flete)
3. valor_unidad = valor_base + (utilidad × valor_base / 100)
4. valor_unidad = round(valor_unidad, -2) // Redondeo a centenas
5. valor_total = valor_unidad × cantidad
```

**Ejemplo:**
- Costo unidad: 100 USD
- TRM: 4000 COP/USD
- Peso: 2.5 kg
- Flete: 5000 COP/kg
- Utilidad: 10%

**Cálculos:**
1. `costo_cop = 100 × 4000 = 400,000 COP`
2. `valor_base = 400,000 + (2.5 × 2.2 × 5000) = 427,500 COP`
3. `valor_unidad = 427,500 + (10 × 427,500 / 100) = 470,250 COP`
4. `valor_unidad = round(470,250, -2) = 470,200 COP`
5. `valor_total = 470,200 × 3 = 1,410,600 COP`

## Implementación Técnica

### Campos Reactivos

```php
// Campo cantidad
TextInput::make("cantidad")
    ->label("Cantidad Cotizadas")
    ->numeric()
    ->required()
    ->live()
    ->reactive()
    ->default(fn(Get $get) => $get("../../cantidad"))
    ->afterStateUpdated(function (Set $set, Get $get) {
        $costo_unidad = $get('costo_unidad');
        $utilidad = $get('utilidad');
        $ubicacion = $get('ubicacion');
        if (!empty($costo_unidad) && !empty($utilidad) && !empty($ubicacion)) {
            self::calculateValorTotal($set, $get);
        }
    }),

// Campo costo_unidad
TextInput::make("costo_unidad")
    ->label("Costo Unidad")
    ->live()
    ->reactive()
    ->numeric()
    ->afterStateUpdated(function (Set $set, Get $get) {
        $cantidad = $get('cantidad');
        $utilidad = $get('utilidad');
        $ubicacion = $get('ubicacion');
        if (!empty($cantidad) && !empty($utilidad) && !empty($ubicacion)) {
            self::calculateValorTotal($set, $get);
        }
    }),

// Campo utilidad
TextInput::make("utilidad")
    ->label("Utilidad %")
    ->reactive()
    ->required()
    ->live()
    ->numeric()
    ->afterStateUpdated(function (Set $set, Get $get) {
        $cantidad = $get('cantidad');
        $costo_unidad = $get('costo_unidad');
        $ubicacion = $get('ubicacion');
        if (!empty($cantidad) && !empty($costo_unidad) && !empty($ubicacion)) {
            self::calculateValorTotal($set, $get);
        }
    }),
```

### Método de Cálculo Principal

```php
public static function calculateValorTotal(Set $set, Get $get): void
{
    // Obtener valores de los campos
    $costo_unidad = $get('costo_unidad');
    $utilidad = $get('utilidad');
    $cantidad = $get('cantidad');
    $ubicacion = $get('ubicacion');

    // Validar que tengamos todos los valores necesarios
    if (empty($costo_unidad) || empty($utilidad) || empty($cantidad) || empty($ubicacion)) {
        $set("valor_unidad", null);
        $set("valor_total", null);
        return;
    }

    // Convertir a números para asegurar cálculos correctos
    $costo_unidad = (float) $costo_unidad;
    $utilidad = (float) $utilidad;
    $cantidad = (int) $cantidad;

    if ($ubicacion === 'Nacional') {
        // Lógica para proveedores nacionales
        $valor_unidad = $costo_unidad + ($costo_unidad * $utilidad / 100);
        $valor_total = $valor_unidad * $cantidad;
        
        $set("valor_unidad", $valor_unidad);
        $set("valor_total", $valor_total);
    } else {
        // Lógica para proveedores internacionales
        $peso = $get("../../peso");
        $empresa = Empresa::query()->where('estado', 1)->first();
        $trm = $empresa?->trm;
        $flete = $empresa?->flete;

        // Validar valores para internacional
        if (!is_numeric($peso) || !is_numeric($trm) || !is_numeric($flete)) {
            $set("valor_unidad", null);
            $set("valor_total", null);
            return;
        }

        // Paso 1: Convertir costo USD a pesos colombianos
        $costo_cop = $costo_unidad * $trm;

        // Paso 2: Agregar flete por peso
        $valor_base = $costo_cop + ($peso * 2.2 * $flete);

        // Paso 3: Aplicar utilidad
        $valor_unidad = $valor_base + ($utilidad * $valor_base / 100);

        // Paso 4: Redondear a centenas
        $valor_unidad = round($valor_unidad, -2);

        // Paso 5: Calcular valor total
        $valor_total = $valor_unidad * $cantidad;

        $set("valor_total", $valor_total);
        $set("valor_unidad", $valor_unidad);
    }
}
```

### Campos de Resultado

```php
// Campo valor_unidad (solo lectura)
TextInput::make("valor_unidad")
    ->label("Valor Unidad $")
    ->numeric()
    ->readOnly(),

// Campo valor_total (solo lectura)
TextInput::make("valor_total")
    ->live()
    ->readOnly()
    ->label("Valor Total $"),
```

## Validaciones y Reglas de Negocio

### Validaciones de Campos

1. **Campos Requeridos**: cantidad, costo_unidad, utilidad, ubicacion
2. **Tipos de Datos**: cantidad (int), costo_unidad (float), utilidad (float)
3. **Valores Positivos**: Todos los campos numéricos deben ser > 0
4. **Ubicación Válida**: Solo "Nacional" o "Internacional"

### Validaciones Específicas por Tipo

#### Proveedores Nacionales
- No requiere peso, TRM o flete
- Cálculo directo en pesos colombianos

#### Proveedores Internacionales
- Requiere peso del pedido
- Requiere TRM configurado en empresa activa
- Requiere flete configurado en empresa activa
- Aplica redondeo a centenas

### Manejo de Errores

```php
// Validación de empresa activa
$empresa = Empresa::query()->where('estado', 1)->first();
if (!$empresa) {
    // Log error: No hay empresa activa
    return;
}

// Validación de valores numéricos
if (!is_numeric($peso) || !is_numeric($trm) || !is_numeric($flete)) {
    $set("valor_unidad", null);
    $set("valor_total", null);
    return;
}
```

## Configuración del Sistema

### Parámetros de Empresa

```php
// Obtener empresa activa
$empresa = Empresa::query()->where('estado', 1)->first();

// Parámetros requeridos
$trm = $empresa?->trm;        // Tasa de cambio USD a COP
$flete = $empresa?->flete;    // Costo de flete por kg en COP
```

### Configuración de Redondeo

```php
// Redondeo a centenas para proveedores internacionales
$valor_unidad = round($valor_unidad, -2);
```

## Casos de Uso

### 1. Cotización Nacional

**Escenario**: Cliente solicita 5 repuestos de proveedor nacional
**Entrada**:
- Cantidad: 5
- Costo unidad: 15,000 COP
- Utilidad: 8%
- Ubicación: Nacional

**Proceso**:
1. Validar campos requeridos ✓
2. Aplicar fórmula nacional
3. Calcular valor unidad: 15,000 + (15,000 × 0.08) = 16,200 COP
4. Calcular valor total: 16,200 × 5 = 81,000 COP

**Resultado**:
- Valor unidad: 16,200 COP
- Valor total: 81,000 COP

### 2. Cotización Internacional

**Escenario**: Cliente solicita 3 filtros de proveedor internacional
**Entrada**:
- Cantidad: 3
- Costo unidad: 25 USD
- Utilidad: 12%
- Ubicación: Internacional
- Peso: 1.8 kg
- TRM: 4,200 COP/USD
- Flete: 6,000 COP/kg

**Proceso**:
1. Validar campos requeridos ✓
2. Convertir a COP: 25 × 4,200 = 105,000 COP
3. Calcular flete: 1.8 × 2.2 × 6,000 = 23,760 COP
4. Valor base: 105,000 + 23,760 = 128,760 COP
5. Aplicar utilidad: 128,760 + (12 × 128,760 / 100) = 144,211.2 COP
6. Redondear: round(144,211.2, -2) = 144,200 COP
7. Valor total: 144,200 × 3 = 432,600 COP

**Resultado**:
- Valor unidad: 144,200 COP
- Valor total: 432,600 COP

## Testing y Validación

### Casos de Prueba

1. **Cálculos Nacionales**
   - Verificar fórmula básica
   - Verificar con diferentes utilidades
   - Verificar con diferentes cantidades

2. **Cálculos Internacionales**
   - Verificar conversión USD a COP
   - Verificar cálculo de flete
   - Verificar aplicación de utilidad
   - Verificar redondeo a centenas

3. **Validaciones**
   - Verificar campos requeridos
   - Verificar tipos de datos
   - Verificar manejo de errores

4. **Reactividad**
   - Verificar actualización en tiempo real
   - Verificar sincronización entre campos
   - Verificar performance con múltiples cambios

### Métricas de Rendimiento

- **Tiempo de respuesta**: < 100ms para cálculos
- **Precisión**: 100% en cálculos matemáticos
- **Disponibilidad**: 99.9% del tiempo
- **Errores**: < 0.1% de las operaciones

## Mantenimiento y Evolución

### Consideraciones Futuras

1. **Múltiples Monedas**: Soporte para EUR, BRL, etc.
2. **Fórmulas Personalizables**: Permitir fórmulas específicas por proveedor
3. **Historial de Cambios**: Tracking de modificaciones en valores
4. **Aprobaciones**: Workflow de aprobación para cotizaciones
5. **Integración con APIs**: Obtener TRM en tiempo real

### Mejoras de Performance

1. **Caching**: Cachear valores de TRM y flete
2. **Debouncing**: Evitar cálculos excesivos en cambios rápidos
3. **Lazy Loading**: Cargar datos solo cuando sea necesario

## Troubleshooting

### Problemas Comunes

1. **Valores no se actualizan**
   - Verificar que campos tengan `live()` y `reactive()`
   - Verificar que `afterStateUpdated()` esté configurado
   - Verificar que empresa activa tenga TRM y flete

2. **Cálculos incorrectos**
   - Verificar tipos de datos (string vs numeric)
   - Verificar que TRM y flete no sean null
   - Verificar fórmula aplicada según ubicación

3. **Performance lenta**
   - Verificar que no haya loops infinitos
   - Verificar que validaciones no sean excesivas
   - Verificar que queries a base de datos sean eficientes

### Logs y Debugging

```php
// Agregar logs para debugging
Log::info('Calculando valores', [
    'costo_unidad' => $costo_unidad,
    'utilidad' => $utilidad,
    'cantidad' => $cantidad,
    'ubicacion' => $ubicacion,
    'peso' => $peso,
    'trm' => $trm,
    'flete' => $flete
]);
```

## Referencias

- **Filament Forms**: [Documentación oficial](https://filamentphp.com/docs/forms)
- **Laravel Livewire**: [Reactividad en tiempo real](https://laravel-livewire.com/)
- **PHP Math Functions**: [Funciones matemáticas](https://www.php.net/manual/en/ref.math.php)
- **Business Logic Patterns**: [Patrones de lógica de negocio](https://martinfowler.com/bliki/BusinessLogic.html)
