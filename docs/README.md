# Documentación del Sistema CYH

## 📚 Descripción General

Esta documentación cubre todos los aspectos del sistema CYH, desde la arquitectura de base de datos hasta las funcionalidades específicas implementadas en Filament.

## 🏗️ Estructura de la Documentación

### 📁 [MODELS/](./MODELS/)
Documentación detallada de todos los modelos Eloquent del sistema.

- **[Empresa.md](./MODELS/Empresa.md)** - Modelo principal del sistema
- **[User.md](./MODELS/User.md)** - Modelo de usuarios del sistema
- **[Pedido.md](./MODELS/Pedido.md)** - Modelo de pedidos principales
- **[Referencia.md](./MODELS/Referencia.md)** - Modelo de referencias de artículos
- **[Tercero.md](./MODELS/Tercero.md)** - Modelo de terceros/proveedores

### 📁 [FEATURES/](./FEATURES/)
Documentación de funcionalidades específicas del sistema.

- **[calculo-automatico.md](./FEATURES/calculo-automatico.md)** - Sistema de cálculo automático
- **gestión-pedidos.md** - Gestión de pedidos (pendiente)
- **cotizaciones.md** - Sistema de cotizaciones (pendiente)

### 📁 [DEVELOPMENT/](./DEVELOPMENT/)
Guías para desarrolladores.

- **[guidelines.md](./DEVELOPMENT/guidelines.md)** - Estándares de código y mejores prácticas
- **api-documentation.md** - Documentación de APIs (pendiente)
- **testing.md** - Guías de testing (pendiente)

### 📁 [DEPLOYMENT/](./DEPLOYMENT/)
Guías de despliegue y configuración.

- **deployment.md** - Guía de despliegue (pendiente)
- **environment.md** - Configuración de variables de entorno (pendiente)

### 📋 [RESUMEN_EJECUTIVO.md](./RESUMEN_EJECUTIVO.md)
Resumen ejecutivo completo del proyecto Sistema CYH.

## 🚀 Funcionalidades Principales

### ✅ Implementadas

1. **Cálculo Automático de Valores**
   - Proveedores nacionales e internacionales
   - Reactividad en tiempo real
   - Validación robusta de campos

2. **Gestión de Empresas**
   - Control de empresa activa
   - Configuración de TRM y flete
   - Gestión de identidad corporativa

3. **Agrupación de Órdenes de Compra** ⭐ **NUEVO**
   - Agrupación automática por proveedor y cliente
   - Vista personalizada con diseño agrupado
   - Gestión de referencias con cantidades y valores
   - Compatibilidad total con temas claro/oscuro de Filament
   - Totales calculados automáticamente por grupo

### 🚧 En Desarrollo

1. **Documentación de Modelos**
   - Modelo Empresa (completado)
   - Modelos restantes (en progreso)

2. **Documentación de Funcionalidades**
   - Sistema de cálculo (completado)
   - Otras funcionalidades (pendiente)

## 🛠️ Tecnologías Utilizadas

- **Backend**: Laravel 10.x
- **Frontend**: Filament 3.x
- **Base de Datos**: MySQL 8.0+
- **PHP**: 8.1+
- **Documentación**: Markdown + PHPDoc

## 📋 Estado del Proyecto

### 🟢 Completado
- Sistema de cálculo automático
- Documentación del modelo Empresa
- Documentación del modelo User
- Documentación del modelo Pedido
- Documentación del modelo Referencia
- Documentación del modelo Tercero
- Funcionalidad de proveedores internacionales
- **Agrupación de Órdenes de Compra (Issue #24)** ⭐ **COMPLETADO**

### 🟡 En Progreso
- Documentación de funcionalidades específicas
- Guías de desarrollo y despliegue

### 🔴 Pendiente
- Documentación de recursos Filament
- Guías de desarrollo
- Documentación de APIs
- Guías de despliegue

## 🎯 Próximos Pasos

1. **Completar documentación de modelos**
   - User, Pedido, Referencia, Tercero
   - Relaciones entre modelos

2. **Documentar funcionalidades**
   - Gestión de pedidos
   - Sistema de cotizaciones
   - Gestión de proveedores

3. **Crear guías de desarrollo**
   - Estándares de código
   - Patrones de diseño
   - Testing

4. **Documentar recursos Filament**
   - PedidosResource
   - ReferenciasResource
   - TercerosResource

## 📖 Cómo Contribuir

### Para Desarrolladores

1. **Documentar código nuevo**
   - Usar PHPDoc para clases y métodos
   - Comentar lógica compleja de negocio
   - Explicar el propósito, no solo la implementación

2. **Actualizar documentación existente**
   - Mantener sincronizada con el código
   - Agregar ejemplos de uso
   - Documentar casos edge y soluciones

### Para Técnicos

1. **Revisar documentación**
   - Verificar precisión técnica
   - Sugerir mejoras de claridad
   - Identificar información faltante

2. **Mantener estándares**
   - Formato consistente
   - Estructura organizada
   - Enlaces funcionales

## 🔍 Búsqueda y Navegación

### Búsqueda por Funcionalidad
- **Cálculos**: Ver [calculo-automatico.md](./FEATURES/calculo-automatico.md)
- **Empresas**: Ver [Empresa.md](./MODELS/Empresa.md)
- **Pedidos**: Ver [gestión-pedidos.md](./FEATURES/gestión-pedidos.md) (pendiente)

### Búsqueda por Modelo
- **Empresa**: Ver [Empresa.md](./MODELS/Empresa.md)
- **User**: Ver [User.md](./MODELS/User.md) (pendiente)
- **Pedido**: Ver [Pedido.md](./MODELS/Pedido.md) (pendiente)

### Búsqueda por Problema
- **Errores de cálculo**: Ver [calculo-automatico.md](./FEATURES/calculo-automatico.md)
- **Configuración de empresa**: Ver [Empresa.md](./MODELS/Empresa.md)
- **Problemas de TRM/flete**: Ver [Empresa.md](./MODELS/Empresa.md)

## 📞 Contacto y Soporte

### Equipo de Desarrollo
- **Tech Lead**: [Nombre del Tech Lead]
- **Desarrolladores**: [Lista de desarrolladores]

### Canales de Comunicación
- **Issues**: GitHub Issues del proyecto
- **Discusiones**: GitHub Discussions
- **Chat**: [Canal de chat del equipo]

## 📝 Historial de Cambios

### Versión 1.0.0 (2025-08-18)
- ✅ Implementación de cálculo automático
- ✅ Documentación del modelo Empresa
- ✅ Estructura de documentación inicial

### Próximas Versiones
- **v1.1.0**: Documentación completa de modelos
- **v1.2.0**: Documentación de funcionalidades
- **v1.3.0**: Guías de desarrollo y despliegue

---

**Última actualización**: 2025-08-18  
**Versión de la documentación**: 1.0.0  
**Mantenido por**: Equipo de Desarrollo CYH
