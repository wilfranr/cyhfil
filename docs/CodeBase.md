# Documentación del código

## Kernel: `app/Console/Kernel.php`
**Namespace**: `App\Console`
**Clase**: `Kernel`
**Métodos**:
- `schedule`
- `commands`

## MessageSent: `app/Events/MessageSent.php`
**Namespace**: `App\Events`
**Clase**: `MessageSent`
**Métodos**:
- `__construct`
- `broadcastOn`
- `broadcastAs`
- `broadcastWith`

## PedidoCreado: `app/Events/PedidoCreado.php`
**Namespace**: `App\Events`
**Clase**: `PedidoCreado`
**Métodos**:
- `__construct`
- `broadcastOn`
- `broadcastAs`
- `broadcastWith`

## TestEvent: `app/Events/TestEvent.php`
**Namespace**: `App\Events`
**Clase**: `TestEvent`
**Métodos**:
- `__construct`
- `broadcastOn`

## Handler: `app/Exceptions/Handler.php`
**Namespace**: `App\Exceptions`
**Clase**: `Handler`
**Métodos**:
- `register`

## CreateOrdenTrabajo: `app/Filament/Logistica/Resources/OrdenTrabajoResource/Pages/CreateOrdenTrabajo.php`
**Namespace**: `App\Filament\Logistica\Resources\OrdenTrabajoResource\Pages`
**Clase**: `CreateOrdenTrabajo`

## EditOrdenTrabajo: `app/Filament/Logistica/Resources/OrdenTrabajoResource/Pages/EditOrdenTrabajo.php`
**Namespace**: `App\Filament\Logistica\Resources\OrdenTrabajoResource\Pages`
**Clase**: `EditOrdenTrabajo`
**Métodos**:
- `getHeaderActions`
- `getRedirectUrl`

## ListOrdenTrabajos: `app/Filament/Logistica/Resources/OrdenTrabajoResource/Pages/ListOrdenTrabajos.php`
**Namespace**: `App\Filament\Logistica\Resources\OrdenTrabajoResource\Pages`
**Clase**: `ListOrdenTrabajos`
**Métodos**:
- `getHeaderActions`

## ViewOrdenTrabajo: `app/Filament/Logistica/Resources/OrdenTrabajoResource/Pages/ViewOrdenTrabajo.php`
**Namespace**: `App\Filament\Logistica\Resources\OrdenTrabajoResource\Pages`
**Clase**: `ViewOrdenTrabajo`
**Métodos**:
- `getViewSchema`

## OrdenTrabajoResource: `app/Filament/Logistica/Resources/OrdenTrabajoResource.php`
**Namespace**: `App\Filament\Logistica\Resources`
**Clase**: `OrdenTrabajoResource`
**Métodos**:
- `canCreate`
- `getNavigationBadge`
- `form`
- `table`
- `getRelations`
- `getPages`

## CreatePedido: `app/Filament/Logistica/Resources/PedidoResource/Pages/CreatePedido.php`
**Namespace**: `App\Filament\Logistica\Resources\PedidoResource\Pages`
**Clase**: `CreatePedido`

## EditPedido: `app/Filament/Logistica/Resources/PedidoResource/Pages/EditPedido.php`
**Namespace**: `App\Filament\Logistica\Resources\PedidoResource\Pages`
**Clase**: `EditPedido`
**Métodos**:
- `getHeaderActions`

## ListPedidos: `app/Filament/Logistica/Resources/PedidoResource/Pages/ListPedidos.php`
**Namespace**: `App\Filament\Logistica\Resources\PedidoResource\Pages`
**Clase**: `ListPedidos`
**Métodos**:
- `getHeaderActions`

## PedidoResource: `app/Filament/Logistica/Resources/PedidoResource.php`
**Namespace**: `App\Filament\Logistica\Resources`
**Clase**: `PedidoResource`
**Métodos**:
- `form`
- `table`
- `getRelations`
- `getPages`

## CreateTercero: `app/Filament/Logistica/Resources/TerceroResource/Pages/CreateTercero.php`
**Namespace**: `App\Filament\Logistica\Resources\TerceroResource\Pages`
**Clase**: `CreateTercero`

## EditTercero: `app/Filament/Logistica/Resources/TerceroResource/Pages/EditTercero.php`
**Namespace**: `App\Filament\Logistica\Resources\TerceroResource\Pages`
**Clase**: `EditTercero`
**Métodos**:
- `getHeaderActions`
- `getRedirectUrl`

## ListTerceros: `app/Filament/Logistica/Resources/TerceroResource/Pages/ListTerceros.php`
**Namespace**: `App\Filament\Logistica\Resources\TerceroResource\Pages`
**Clase**: `ListTerceros`
**Métodos**:
- `getHeaderActions`

## ViewTercero: `app/Filament/Logistica/Resources/TerceroResource/Pages/ViewTercero.php`
**Namespace**: `App\Filament\Logistica\Resources\TerceroResource\Pages`
**Clase**: `ViewTercero`

## TerceroResource: `app/Filament/Logistica/Resources/TerceroResource.php`
**Namespace**: `App\Filament\Logistica\Resources`
**Clase**: `TerceroResource`
**Métodos**:
- `getGloballySearchableAttributes`
- `getGlobalSearchResultDetails`
- `form`
- `table`
- `getRelations`
- `getPages`

## CreateTransportadora: `app/Filament/Logistica/Resources/TransportadoraResource/Pages/CreateTransportadora.php`
**Namespace**: `App\Filament\Logistica\Resources\TransportadoraResource\Pages`
**Clase**: `CreateTransportadora`

## EditTransportadora: `app/Filament/Logistica/Resources/TransportadoraResource/Pages/EditTransportadora.php`
**Namespace**: `App\Filament\Logistica\Resources\TransportadoraResource\Pages`
**Clase**: `EditTransportadora`
**Métodos**:
- `getHeaderActions`

## ListTransportadoras: `app/Filament/Logistica/Resources/TransportadoraResource/Pages/ListTransportadoras.php`
**Namespace**: `App\Filament\Logistica\Resources\TransportadoraResource\Pages`
**Clase**: `ListTransportadoras`
**Métodos**:
- `getHeaderActions`

## TransportadoraResource: `app/Filament/Logistica/Resources/TransportadoraResource.php`
**Namespace**: `App\Filament\Logistica\Resources`
**Clase**: `TransportadoraResource`
**Métodos**:
- `form`
- `table`
- `getRelations`
- `getPages`

## TrmSettings: `app/Filament/Pages/TrmSettings.php`
**Namespace**: `App\Filament\Pages`
**Clase**: `TrmSettings`
**Métodos**:
- `mount`
- `getFormSchema`
- `submit`
- `getTrm`

## CreateArticulos: `app/Filament/Resources/ArticulosResource/Pages/CreateArticulos.php`
**Namespace**: `App\Filament\Resources\ArticulosResource\Pages`
**Clase**: `CreateArticulos`
**Métodos**:
- `getRedirectUrl`

## EditArticulos: `app/Filament/Resources/ArticulosResource/Pages/EditArticulos.php`
**Namespace**: `App\Filament\Resources\ArticulosResource\Pages`
**Clase**: `EditArticulos`
**Métodos**:
- `getHeaderActions`
- `getRedirectUrl`

## ListArticulos: `app/Filament/Resources/ArticulosResource/Pages/ListArticulos.php`
**Namespace**: `App\Filament\Resources\ArticulosResource\Pages`
**Clase**: `ListArticulos`
**Métodos**:
- `getHeaderActions`

## ViewArticulos: `app/Filament/Resources/ArticulosResource/Pages/ViewArticulos.php`
**Namespace**: `App\Filament\Resources\ArticulosResource\Pages`
**Clase**: `ViewArticulos`
**Métodos**:
- `getTitle`
- `getActions`

## ArticuloReferenciasRelationManager: `app/Filament/Resources/ArticulosResource/RelationManagers/ArticuloReferenciasRelationManager.php`
**Namespace**: `App\Filament\Resources\ArticulosResource\RelationManagers`
**Clase**: `ArticuloReferenciasRelationManager`
**Métodos**:
- `canViewForRecord`
- `form`
- `table`

## MedidasRelationManager: `app/Filament/Resources/ArticulosResource/RelationManagers/MedidasRelationManager.php`
**Namespace**: `App\Filament\Resources\ArticulosResource\RelationManagers`
**Clase**: `MedidasRelationManager`
**Métodos**:
- `getEloquentQuery`
- `form`
- `table`

## ReferenciasRelationManager: `app/Filament/Resources/ArticulosResource/RelationManagers/ReferenciasRelationManager.php`
**Namespace**: `App\Filament\Resources\ArticulosResource\RelationManagers`
**Clase**: `ReferenciasRelationManager`
**Métodos**:
- `canViewForRecord`
- `form`
- `table`

## ArticulosResource: `app/Filament/Resources/ArticulosResource.php`
**Namespace**: `App\Filament\Resources`
**Clase**: `ArticulosResource`
**Métodos**:
- `getGlobalSearchResultDetails`
- `form`
- `table`
- `getEloquentQuery`
- `infolist`
- `getRecordSubNavigation`
- `getHeaderWidgets`
- `getRelations`
- `getPages`

## CreateCotizacion: `app/Filament/Resources/CotizacionResource/Pages/CreateCotizacion.php`
**Namespace**: `App\Filament\Resources\CotizacionResource\Pages`
**Clase**: `CreateCotizacion`

## EditCotizacion: `app/Filament/Resources/CotizacionResource/Pages/EditCotizacion.php`
**Namespace**: `App\Filament\Resources\CotizacionResource\Pages`
**Clase**: `EditCotizacion`
**Métodos**:
- `getHeaderActions`

## ListCotizacions: `app/Filament/Resources/CotizacionResource/Pages/ListCotizacions.php`
**Namespace**: `App\Filament\Resources\CotizacionResource\Pages`
**Clase**: `ListCotizacions`
**Métodos**:
- `getHeaderActions`

## CotizacionResource: `app/Filament/Resources/CotizacionResource.php`
**Namespace**: `App\Filament\Resources`
**Clase**: `CotizacionResource`
**Métodos**:
- `getNavigationBadge`
- `table`
- `getRelations`
- `getPages`

## CreateEmpresa: `app/Filament/Resources/EmpresaResource/Pages/CreateEmpresa.php`
**Namespace**: `App\Filament\Resources\EmpresaResource\Pages`
**Clase**: `CreateEmpresa`

## EditEmpresa: `app/Filament/Resources/EmpresaResource/Pages/EditEmpresa.php`
**Namespace**: `App\Filament\Resources\EmpresaResource\Pages`
**Clase**: `EditEmpresa`
**Métodos**:
- `getHeaderActions`
- `getRedirectUrl`

## ListEmpresas: `app/Filament/Resources/EmpresaResource/Pages/ListEmpresas.php`
**Namespace**: `App\Filament\Resources\EmpresaResource\Pages`
**Clase**: `ListEmpresas`
**Métodos**:
- `getHeaderActions`

## EmpresaResource: `app/Filament/Resources/EmpresaResource.php`
**Namespace**: `App\Filament\Resources`
**Clase**: `EmpresaResource`
**Métodos**:
- `form`
- `table`
- `getRelations`
- `getPages`

## CreateFabricante: `app/Filament/Resources/FabricanteResource/Pages/CreateFabricante.php`
**Namespace**: `App\Filament\Resources\FabricanteResource\Pages`
**Clase**: `CreateFabricante`

## EditFabricante: `app/Filament/Resources/FabricanteResource/Pages/EditFabricante.php`
**Namespace**: `App\Filament\Resources\FabricanteResource\Pages`
**Clase**: `EditFabricante`
**Métodos**:
- `getHeaderActions`

## ListFabricantes: `app/Filament/Resources/FabricanteResource/Pages/ListFabricantes.php`
**Namespace**: `App\Filament\Resources\FabricanteResource\Pages`
**Clase**: `ListFabricantes`
**Métodos**:
- `getHeaderActions`

## TercerosRelationManager: `app/Filament/Resources/FabricanteResource/RelationManagers/TercerosRelationManager.php`
**Namespace**: `App\Filament\Resources\FabricanteResource\RelationManagers`
**Clase**: `TercerosRelationManager`
**Métodos**:
- `form`
- `table`
- `getPages`

## FabricanteResource: `app/Filament/Resources/FabricanteResource.php`
**Namespace**: `App\Filament\Resources`
**Clase**: `FabricanteResource`
**Métodos**:
- `form`
- `table`
- `getRelations`
- `getPages`

## CreateListas: `app/Filament/Resources/ListasResource/Pages/CreateListas.php`
**Namespace**: `App\Filament\Resources\ListasResource\Pages`
**Clase**: `CreateListas`
**Métodos**:
- `getRedirectUrl`
- `afterSave`

## EditListas: `app/Filament/Resources/ListasResource/Pages/EditListas.php`
**Namespace**: `App\Filament\Resources\ListasResource\Pages`
**Clase**: `EditListas`
**Métodos**:
- `getHeaderActions`
- `getRedirectUrl`

## ListListas: `app/Filament/Resources/ListasResource/Pages/ListListas.php`
**Namespace**: `App\Filament\Resources\ListasResource\Pages`
**Clase**: `ListListas`
**Métodos**:
- `getHeaderActions`
- `importExcel`
- `getTabs`

## SistemasRelationManager: `app/Filament/Resources/ListasResource/RelationManagers/SistemasRelationManager.php`
**Namespace**: `App\Filament\Resources\ListasResource\RelationManagers`
**Clase**: `SistemasRelationManager`
**Métodos**:
- `form`
- `table`

## ListasResource: `app/Filament/Resources/ListasResource.php`
**Namespace**: `App\Filament\Resources`
**Clase**: `ListasResource`
**Métodos**:
- `form`
- `table`
- `getRelations`
- `getPages`

## CreateMaquinas: `app/Filament/Resources/MaquinasResource/Pages/CreateMaquinas.php`
**Namespace**: `App\Filament\Resources\MaquinasResource\Pages`
**Clase**: `CreateMaquinas`
**Métodos**:
- `getRedirectUrl`

## EditMaquinas: `app/Filament/Resources/MaquinasResource/Pages/EditMaquinas.php`
**Namespace**: `App\Filament\Resources\MaquinasResource\Pages`
**Clase**: `EditMaquinas`
**Métodos**:
- `getHeaderActions`
- `getRedirectUrl`

## ListMaquinas: `app/Filament/Resources/MaquinasResource/Pages/ListMaquinas.php`
**Namespace**: `App\Filament\Resources\MaquinasResource\Pages`
**Clase**: `ListMaquinas`
**Métodos**:
- `getHeaderActions`

## ViewMaquinas: `app/Filament/Resources/MaquinasResource/Pages/ViewMaquinas.php`
**Namespace**: `App\Filament\Resources\MaquinasResource\Pages`
**Clase**: `ViewMaquinas`
**Métodos**:
- `getTitle`
- `getActions`

## PedidosRelationManager: `app/Filament/Resources/MaquinasResource/RelationManagers/PedidosRelationManager.php`
**Namespace**: `App\Filament\Resources\MaquinasResource\RelationManagers`
**Clase**: `PedidosRelationManager`
**Métodos**:
- `form`
- `table`

## ReferenciasVendidasRelationManager: `app/Filament/Resources/MaquinasResource/RelationManagers/ReferenciasVendidasRelationManager.php`
**Namespace**: `App\Filament\Resources\MaquinasResource\RelationManagers`
**Clase**: `ReferenciasVendidasRelationManager`
**Métodos**:
- `getEloquentQuery`
- `form`
- `table`

## TercerosRelationManager: `app/Filament/Resources/MaquinasResource/RelationManagers/TercerosRelationManager.php`
**Namespace**: `App\Filament\Resources\MaquinasResource\RelationManagers`
**Clase**: `TercerosRelationManager`
**Métodos**:
- `form`
- `table`

## MaquinasResource: `app/Filament/Resources/MaquinasResource.php`
**Namespace**: `App\Filament\Resources`
**Clase**: `MaquinasResource`

**Funcionalidades**:
- **Gestión de máquinas** con tipos, fabricantes y propietarios
- **Formularios de creación/edición** con campos dinámicos
- **Soporte para imágenes** (foto principal y foto ID)
- **Relaciones** con pedidos y referencias vendidas

**Métodos**:
- `getGloballySearchableAttributes()` - Atributos para búsqueda global
- `getGlobalSearchResultDetails()` - Detalles de resultados de búsqueda
- `form()` - Formulario de creación/edición
- `table()` - Tabla de listado con columnas configurables
- `infolist()` - Vista detallada de máquina
- `getRelations()` - Relaciones del recurso
- `getPages()` - Páginas del recurso

**Campos del Formulario**:
- **Tipo**: Select con opción de crear/editar tipos de máquina
- **Fabricante**: Select con opción de crear/editar fabricantes
- **Modelo**: Texto requerido
- **Serie**: Texto único
- **Arreglo**: Texto opcional
- **Propietario**: Select de terceros (excluyendo proveedores)
- **Fotos**: Foto principal y foto ID con editor de imágenes

**Bug Fix Aplicado** (v2024.08.18):
- **Problema**: Error "Return value must be of type array, null returned" en `getEditOptionActionFormData()`
- **Causa**: Los métodos `editOptionForm` estaban definidos como funciones anónimas que podían devolver `null`
- **Solución**: Convertidos a arrays directos para garantizar que siempre devuelvan arrays válidos
- **Archivos afectados**: Campos `tipo` y `fabricante_id` en el formulario

## CreateOrdenCompra: `app/Filament/Resources/OrdenCompraResource/Pages/CreateOrdenCompra.php`
**Namespace**: `App\Filament\Resources\OrdenCompraResource\Pages`
**Clase**: `CreateOrdenCompra`

## EditOrdenCompra: `app/Filament/Resources/OrdenCompraResource/Pages/EditOrdenCompra.php`
**Namespace**: `App\Filament\Resources\OrdenCompraResource\Pages`
**Clase**: `EditOrdenCompra`
**Métodos**:
- `getHeaderActions`
- `getRedirectUrl`

## ListOrdenCompras: `app/Filament/Resources/OrdenCompraResource/Pages/ListOrdenCompras.php`
**Namespace**: `App\Filament\Resources\OrdenCompraResource\Pages`
**Clase**: `ListOrdenCompras`
**Funcionalidades**:
- **Vista personalizada** con agrupación visual por proveedor
- **Header personalizado** con título y descripción
- **Agrupación automática** de órdenes por proveedor y cliente
- **Totales calculados** por proveedor y cliente

**Métodos**:
- `getHeaderActions()` - Acciones del header (Crear)
- `getHeader()` - Vista personalizada con agrupación
- `getTableQuery()` - Consulta con ordenamiento por proveedor y cliente

**Características de la Vista**:
- Agrupación visual por proveedor con badges de conteo
- Sub-agrupación por cliente con totales
- Tarjetas de órdenes con información detallada
- Sección de referencias a comprar en cada orden
- Diseño responsivo y compatible con temas claro/oscuro

## OrdenCompraResource: `app/Filament/Resources/OrdenCompraResource.php`
**Namespace**: `App\Filament\Resources`
**Clase**: `OrdenCompraResource`
**Funcionalidades**:
- **Agrupación automática** por proveedor y cliente
- **Vista personalizada** con diseño agrupado
- **Filtros avanzados** por proveedor, cliente y estado
- **Gestión de referencias** con cantidades y valores

**Métodos**:
- `getNavigationBadge()` - Badge con órdenes en proceso
- `form()` - Formulario de edición con sección de referencias
- `table()` - Tabla con agrupación y ordenamiento
- `getRelations()` - Relaciones del recurso
- `getPages()` - Páginas del recurso

**Características de la Tabla**:
- Agrupación por proveedor (`proveedor_id`)
- Ordenamiento por cliente (`tercero_id`)
- Columnas: ID, Proveedor, Cliente, Estado, Pedido, Fechas, Valor Total
- Filtros: Proveedor, Cliente, Estado
- Compatibilidad con temas claro/oscuro de Filament

## CreateOrdenTrabajo: `app/Filament/Resources/OrdenTrabajoResource/Pages/CreateOrdenTrabajo.php`
**Namespace**: `App\Filament\Resources\OrdenTrabajoResource\Pages`
**Clase**: `CreateOrdenTrabajo`

## EditOrdenTrabajo: `app/Filament/Resources/OrdenTrabajoResource/Pages/EditOrdenTrabajo.php`
**Namespace**: `App\Filament\Resources\OrdenTrabajoResource\Pages`
**Clase**: `EditOrdenTrabajo`
**Métodos**:
- `getHeaderActions`
- `getRedirectUrl`

## ListOrdenTrabajos: `app/Filament/Resources/OrdenTrabajoResource/Pages/ListOrdenTrabajos.php`
**Namespace**: `App\Filament\Resources\OrdenTrabajoResource\Pages`
**Clase**: `ListOrdenTrabajos`

## ViewOrdenTrabajo: `app/Filament/Resources/OrdenTrabajoResource/Pages/ViewOrdenTrabajo.php`
**Namespace**: `App\Filament\Resources\OrdenTrabajoResource\Pages`
**Clase**: `ViewOrdenTrabajo`
**Métodos**:
- `getViewSchema`

## ReferenciasRelationManager: `app/Filament/Resources/OrdenTrabajoResource/RelationManagers/ReferenciasRelationManager.php`
**Namespace**: `App\Filament\Resources\OrdenTrabajoResource\RelationManagers`
**Clase**: `ReferenciasRelationManager`
**Métodos**:
- `form`
- `table`

## OrdenTrabajoResource: `app/Filament/Resources/OrdenTrabajoResource.php`
**Namespace**: `App\Filament\Resources`
**Clase**: `OrdenTrabajoResource`
**Métodos**:
- `getNavigationBadge`
- `form`
- `table`
- `getRelations`
- `getPages`

## CreatePedidos: `app/Filament/Resources/PedidosResource/Pages/CreatePedidos.php`
**Namespace**: `App\Filament\Resources\PedidosResource\Pages`
**Clase**: `CreatePedidos`
**Métodos**:
- `getRedirectUrl`
- `afterCreate`
- `getCreatedNotification`
- `getPanelBasedOnRole`

## EditPedidos: `app/Filament/Resources/PedidosResource/Pages/EditPedidos.php`
**Namespace**: `App\Filament\Resources\PedidosResource\Pages`
**Clase**: `EditPedidos`
**Métodos**:
- `beforeSave`
- `getHeaderActions`
- `getAnalistaActions`
- `getLogisticaActions`
- `getVendedorActions`
- `getDefaultActions`
- `getGuardarCambiosAction`
- `getEnviarACosteoAction`
- `getGenerarCotizacionAction`
- `getAprobarCotizacionAction`
- `getGenerarNuevaCotizacionAction`
- `getRechazarCotizacionAction`
- `getWhatsappClienteAction`
- `sendNotification`
- `getRedirectUrl`

## ListPedidos: `app/Filament/Resources/PedidosResource/Pages/ListPedidos.php`
**Namespace**: `App\Filament\Resources\PedidosResource\Pages`
**Clase**: `ListPedidos`
**Métodos**:
- `getHeaderActions`
- `getHeaderWidgets`
- `getTabs`

## ReferenciasRelationManager: `app/Filament/Resources/PedidosResource/RelationManagers/ReferenciasRelationManager.php`
**Namespace**: `App\Filament\Resources\PedidosResource\RelationManagers`
**Clase**: `ReferenciasRelationManager`
**Métodos**:
- `form`
- `table`

## StatsOverview: `app/Filament/Resources/PedidosResource/Widgets/StatsOverview.php`
**Namespace**: `App\Filament\Resources\PedidosResource\Widgets`
**Clase**: `StatsOverview`
**Métodos**:
- `getStats`
- `getBarChartDataForUser`
- `getBarChartData`

## PedidosResource: `app/Filament/Resources/PedidosResource.php`
**Namespace**: `App\Filament\Resources`
**Clase**: `PedidosResource`
**Métodos**:
- `getNavigationBadge`
- `getEloquentQuery`
- `form`
- `table`
- `getRelations`
- `getPages`

## CreateReferencia: `app/Filament/Resources/ReferenciaResource/Pages/CreateReferencia.php`
**Namespace**: `App\Filament\Resources\ReferenciaResource\Pages`
**Clase**: `CreateReferencia`

## EditReferencia: `app/Filament/Resources/ReferenciaResource/Pages/EditReferencia.php`
**Namespace**: `App\Filament\Resources\ReferenciaResource\Pages`
**Clase**: `EditReferencia`
**Métodos**:
- `getHeaderActions`

## ListReferencias: `app/Filament/Resources/ReferenciaResource/Pages/ListReferencias.php`
**Namespace**: `App\Filament\Resources\ReferenciaResource\Pages`
**Clase**: `ListReferencias`
**Métodos**:
- `getHeaderActions`

## ReferenciaResource: `app/Filament/Resources/ReferenciaResource.php`
**Namespace**: `App\Filament\Resources`
**Clase**: `ReferenciaResource`
**Métodos**:
- `getEloquentQuery`
- `form`
- `table`
- `getPages`

## CreateRole: `app/Filament/Resources/Shield/RoleResource/Pages/CreateRole.php`
**Namespace**: `App\Filament\Resources\Shield\RoleResource\Pages`
**Clase**: `CreateRole`
**Métodos**:
- `mutateFormDataBeforeCreate`
- `afterCreate`

## EditRole: `app/Filament/Resources/Shield/RoleResource/Pages/EditRole.php`
**Namespace**: `App\Filament\Resources\Shield\RoleResource\Pages`
**Clase**: `EditRole`
**Métodos**:
- `getActions`
- `mutateFormDataBeforeSave`
- `afterSave`

## ListRoles: `app/Filament/Resources/Shield/RoleResource/Pages/ListRoles.php`
**Namespace**: `App\Filament\Resources\Shield\RoleResource\Pages`
**Clase**: `ListRoles`
**Métodos**:
- `getActions`

## ViewRole: `app/Filament/Resources/Shield/RoleResource/Pages/ViewRole.php`
**Namespace**: `App\Filament\Resources\Shield\RoleResource\Pages`
**Clase**: `ViewRole`
**Métodos**:
- `getActions`

## RoleResource: `app/Filament/Resources/Shield/RoleResource.php`
**Namespace**: `App\Filament\Resources\Shield`
**Clase**: `RoleResource`
**Métodos**:
- `getPermissionPrefixes`
- `form`
- `table`
- `getRelations`
- `getPages`
- `getCluster`
- `getModel`
- `getModelLabel`
- `getPluralModelLabel`
- `shouldRegisterNavigation`
- `getNavigationLabel`
- `getNavigationIcon`
- `getNavigationSort`
- `getSlug`
- `getNavigationBadge`
- `isScopedToTenant`
- `canGloballySearch`
- `getResourceEntitiesSchema`
- `getResourceTabBadgeCount`
- `getResourcePermissionOptions`
- `setPermissionStateForRecordPermissions`
- `getPageOptions`
- `getWidgetOptions`
- `getCustomPermissionOptions`
- `getTabFormComponentForResources`
- `getCheckBoxListComponentForResource`
- `getTabFormComponentForPage`
- `getTabFormComponentForWidget`
- `getTabFormComponentForCustomPermissions`
- `getTabFormComponentForSimpleResourcePermissionsView`
- `getCheckboxListFormComponent`
- `shield`

## CreateSistemas: `app/Filament/Resources/SistemasResource/Pages/CreateSistemas.php`
**Namespace**: `App\Filament\Resources\SistemasResource\Pages`
**Clase**: `CreateSistemas`
**Métodos**:
- `getRedirectUrl`

## EditSistemas: `app/Filament/Resources/SistemasResource/Pages/EditSistemas.php`
**Namespace**: `App\Filament\Resources\SistemasResource\Pages`
**Clase**: `EditSistemas`
**Métodos**:
- `getHeaderActions`
- `getRedirectUrl`

## ListSistemas: `app/Filament/Resources/SistemasResource/Pages/ListSistemas.php`
**Namespace**: `App\Filament\Resources\SistemasResource\Pages`
**Clase**: `ListSistemas`
**Métodos**:
- `getHeaderActions`

## ViewSistemas: `app/Filament/Resources/SistemasResource/Pages/ViewSistemas.php`
**Namespace**: `App\Filament\Resources\SistemasResource\Pages`
**Clase**: `ViewSistemas`
**Métodos**:
- `getTitle`
- `getActions`

## ListasRelationManager: `app/Filament/Resources/SistemasResource/RelationManagers/ListasRelationManager.php`
**Namespace**: `App\Filament\Resources\SistemasResource\RelationManagers`
**Clase**: `ListasRelationManager`
**Métodos**:
- `form`
- `table`

## TercerosRelationManager: `app/Filament/Resources/SistemasResource/RelationManagers/TercerosRelationManager.php`
**Namespace**: `App\Filament\Resources\SistemasResource\RelationManagers`
**Clase**: `TercerosRelationManager`
**Métodos**:
- `form`
- `table`

## SistemasResource: `app/Filament/Resources/SistemasResource.php`
**Namespace**: `App\Filament\Resources`
**Clase**: `SistemasResource`
**Métodos**:
- `form`
- `table`
- `infolist`
- `getRelations`
- `getPages`

## CreateTerceros: `app/Filament/Resources/TercerosResource/Pages/CreateTerceros.php`
**Namespace**: `App\Filament\Resources\TercerosResource\Pages`
**Clase**: `CreateTerceros`
**Métodos**:
- `getRedirectUrl`

## EditTerceros: `app/Filament/Resources/TercerosResource/Pages/EditTerceros.php`
**Namespace**: `App\Filament\Resources\TercerosResource\Pages`
**Clase**: `EditTerceros`
**Métodos**:
- `getHeaderActions`
- `getRedirectUrl`

## ListTerceros: `app/Filament/Resources/TercerosResource/Pages/ListTerceros.php`
**Namespace**: `App\Filament\Resources\TercerosResource\Pages`
**Clase**: `ListTerceros`
**Métodos**:
- `getHeaderActions`
- `getTabs`
- `getFooterWidgets`

## ViewTerceros: `app/Filament/Resources/TercerosResource/Pages/ViewTerceros.php`
**Namespace**: `App\Filament\Resources\TercerosResource\Pages`
**Clase**: `ViewTerceros`
**Métodos**:
- `getTitle`
- `getActions`

## ContactosRelationManager: `app/Filament/Resources/TercerosResource/RelationManagers/ContactosRelationManager.php`
**Namespace**: `App\Filament\Resources\TercerosResource\RelationManagers`
**Clase**: `ContactosRelationManager`
**Métodos**:
- `form`
- `table`

## DireccionesRelationManager: `app/Filament/Resources/TercerosResource/RelationManagers/DireccionesRelationManager.php`
**Namespace**: `App\Filament\Resources\TercerosResource\RelationManagers`
**Clase**: `DireccionesRelationManager`
**Métodos**:
- `form`
- `table`

## MaquinasRelationManager: `app/Filament/Resources/TercerosResource/RelationManagers/MaquinasRelationManager.php`
**Namespace**: `App\Filament\Resources\TercerosResource\RelationManagers`
**Clase**: `MaquinasRelationManager`
**Métodos**:
- `form`
- `table`

## ClientesChart: `app/Filament/Resources/TercerosResource/Widgets/ClientesChart.php`
**Namespace**: `App\Filament\Resources\TercerosResource\Widgets`
**Clase**: `ClientesChart`
**Métodos**:
- `getType`
- `getData`

## TercerosResource: `app/Filament/Resources/TercerosResource.php`
**Namespace**: `App\Filament\Resources`
**Clase**: `TercerosResource`
**Métodos**:
- `getGloballySearchableAttributes`
- `getGlobalSearchResultDetails`
- `form`
- `table`
- `getRelations`
- `getPages`

## CreateUsers: `app/Filament/Resources/UsersResource/Pages/CreateUsers.php`
**Namespace**: `App\Filament\Resources\UsersResource\Pages`
**Clase**: `CreateUsers`
**Métodos**:
- `getRedirectUrl`

## EditUsers: `app/Filament/Resources/UsersResource/Pages/EditUsers.php`
**Namespace**: `App\Filament\Resources\UsersResource\Pages`
**Clase**: `EditUsers`
**Métodos**:
- `getHeaderActions`
- `getRedirectUrl`

## ListUsers: `app/Filament/Resources/UsersResource/Pages/ListUsers.php`
**Namespace**: `App\Filament\Resources\UsersResource\Pages`
**Clase**: `ListUsers`
**Métodos**:
- `getHeaderActions`

## use: `app/Filament/Resources/UsersResource.php`
**Namespace**: `App\Filament\Resources`
**Clase**: `use`
**Métodos**:
- `getNavigationBadge`
- `form`
- `table`
- `getRelations`
- `getPages`
- `mutateFormDataBeforeSave`

## PedidosChart: `app/Filament/Widgets/PedidosChart.php`
**Namespace**: `App\Filament\Widgets`
**Clase**: `PedidosChart`
**Métodos**:
- `getType`
- `getData`

## UltimosPedidos: `app/Filament/Widgets/UltimosPedidos.php`
**Namespace**: `App\Filament\Widgets`
**Clase**: `UltimosPedidos`
**Métodos**:
- `table`

## ChatController: `app/Http/Controllers/ChatController.php`
**Namespace**: `App\Http\Controllers`
**Clase**: `ChatController`
**Métodos**:
- `__construct`
- `sendMessage`
- `logEvent`
- `fetchMessages`

## Controller: `app/Http/Controllers/Controller.php`
**Namespace**: `App\Http\Controllers`
**Clase**: `Controller`

## Cotizacion: `app/Http/Controllers/Cotizacion.php`
**Namespace**: `App\Http\Controllers`
**Clase**: `Cotizacion`
**Métodos**:
- `generate`

## OrdenCompraController: `app/Http/Controllers/OrdenCompraController.php`
**Namespace**: `App\Http\Controllers`
**Clase**: `OrdenCompraController`
**Métodos**:
- `generate`

## OrdenTrabajoController: `app/Http/Controllers/OrdenTrabajoController.php`
**Namespace**: `App\Http\Controllers`
**Clase**: `OrdenTrabajoController`
**Métodos**:
- `generarPDF`

## Kernel: `app/Http/Kernel.php`
**Namespace**: `App\Http`
**Clase**: `Kernel`

## Authenticate: `app/Http/Middleware/Authenticate.php`
**Namespace**: `App\Http\Middleware`
**Clase**: `Authenticate`
**Métodos**:
- `redirectTo`

## CheckFilamentAuthentication: `app/Http/Middleware/CheckFilamentAuthentication.php`
**Namespace**: `App\Http\Middleware`
**Clase**: `CheckFilamentAuthentication`
**Métodos**:
- `handle`

## EncryptCookies: `app/Http/Middleware/EncryptCookies.php`
**Namespace**: `App\Http\Middleware`
**Clase**: `EncryptCookies`

## PreventRequestsDuringMaintenance: `app/Http/Middleware/PreventRequestsDuringMaintenance.php`
**Namespace**: `App\Http\Middleware`
**Clase**: `PreventRequestsDuringMaintenance`

## RedirectIfAuthenticated: `app/Http/Middleware/RedirectIfAuthenticated.php`
**Namespace**: `App\Http\Middleware`
**Clase**: `RedirectIfAuthenticated`
**Métodos**:
- `handle`

## RedirectUnauthorizedPanelAccess: `app/Http/Middleware/RedirectUnauthorizedPanelAccess.php`
**Namespace**: `App\Http\Middleware`
**Clase**: `RedirectUnauthorizedPanelAccess`
**Métodos**:
- `handle`

## TrimStrings: `app/Http/Middleware/TrimStrings.php`
**Namespace**: `App\Http\Middleware`
**Clase**: `TrimStrings`

## TrustHosts: `app/Http/Middleware/TrustHosts.php`
**Namespace**: `App\Http\Middleware`
**Clase**: `TrustHosts`
**Métodos**:
- `hosts`

## TrustProxies: `app/Http/Middleware/TrustProxies.php`
**Namespace**: `App\Http\Middleware`
**Clase**: `TrustProxies`

## ValidateSignature: `app/Http/Middleware/ValidateSignature.php`
**Namespace**: `App\Http\Middleware`
**Clase**: `ValidateSignature`

## VerifyCsrfToken: `app/Http/Middleware/VerifyCsrfToken.php`
**Namespace**: `App\Http\Middleware`
**Clase**: `VerifyCsrfToken`

## ListasImport: `app/Imports/ListasImport.php`
**Namespace**: `App\Imports`
**Clase**: `ListasImport`
**Métodos**:
- `model`

## ChatButton: `app/Livewire/ChatButton.php`
**Namespace**: `App\Livewire`
**Clase**: `ChatButton`
**Métodos**:
- `openChat`
- `render`

## ChatModal: `app/Livewire/ChatModal.php`
**Namespace**: `App\Livewire`
**Clase**: `ChatModal`
**Métodos**:
- `render`

## VerArticulo: `app/Livewire/VerArticulo.php`
**Namespace**: `App\Livewire`
**Clase**: `VerArticulo`
**Métodos**:
- `render`

## Archivo: `app/Livewire/chat-modal-slot.blade.php`

## Articulo: `app/Models/Articulo.php`
**Namespace**: `App\Models`
**Clase**: `Articulo`
**Métodos**:
- `articuloReferencia`
- `medidas`
- `pedidos`
- `articuloJuegos`
- `listas`
- `referencias`

## ArticuloJuego: `app/Models/ArticuloJuego.php`
**Namespace**: `App\Models`
**Clase**: `ArticuloJuego`
**Métodos**:
- `articulo`
- `referencia`

## ArticuloReferencia: `app/Models/ArticuloReferencia.php`
**Namespace**: `App\Models`
**Clase**: `ArticuloReferencia`
**Métodos**:
- `referencia`
- `articulo`

## ChFavorite: `app/Models/ChFavorite.php`
**Namespace**: `App\Models`
**Clase**: `ChFavorite`

## ChMessage: `app/Models/ChMessage.php`
**Namespace**: `App\Models`
**Clase**: `ChMessage`

## ChatMessage: `app/Models/ChatMessage.php`
**Namespace**: `App\Models`
**Clase**: `ChatMessage`
**Métodos**:
- `user`

## City: `app/Models/City.php`
**Namespace**: `App\Models`
**Clase**: `City`
**Métodos**:
- `state`
- `country`

## Contacto: `app/Models/Contacto.php`
**Namespace**: `App\Models`
**Clase**: `Contacto`
**Métodos**:
- `tercero`
- `country`
- `booted`

## Cotizacion: `app/Models/Cotizacion.php`
**Namespace**: `App\Models`
**Clase**: `Cotizacion`
**Métodos**:
- `tercero`
- `pedido`

## CotizacionReferenciaProveedor: `app/Models/CotizacionReferenciaProveedor.php`
**Namespace**: `App\Models`
**Clase**: `CotizacionReferenciaProveedor`
**Métodos**:
- `cotizacion`
- `pedidoReferenciaProveedor`

## Country: `app/Models/Country.php`
**Namespace**: `App\Models`
**Clase**: `Country`
**Métodos**:
- `states`
- `cities`

## Direccion: `app/Models/Direccion.php`
**Namespace**: `App\Models`
**Clase**: `Direccion`
**Métodos**:
- `tercero`
- `country`
- `city`
- `state`
- `booted`

## Empresa: `app/Models/Empresa.php`
**Namespace**: `App\Models`
**Clase**: `Empresa`
**Métodos**:
- `country`
- `city`
- `states`
- `boot`

## Fabricante: `app/Models/Fabricante.php`
**Namespace**: `App\Models`
**Clase**: `Fabricante`
**Métodos**:
- `referencias`
- `maquinas`
- `terceros`

## Juego: `app/Models/Juego.php`
**Namespace**: `App\Models`
**Clase**: `Juego`
**Métodos**:
- `articuloJuegos`

## Lista: `app/Models/Lista.php`
**Namespace**: `App\Models`
**Clase**: `Lista`
**Métodos**:
- `sistemas`
- `getNombreAttribute`
- `getRecordTitleAttribute`

## Maquina: `app/Models/Maquina.php`
**Namespace**: `App\Models`
**Clase**: `Maquina`
**Métodos**:
- `terceros`
- `getMaquinaAttribute`
- `pedidos`
- `fabricantes`
- `listas`
- `referenciasVendidas`

## Medida: `app/Models/Medida.php`
**Namespace**: `App\Models`
**Clase**: `Medida`
**Métodos**:
- `articulo`

## OrdenCompra: `app/Models/OrdenCompra.php`
**Namespace**: `App\Models`
**Clase**: `OrdenCompra`
**Relaciones**:
- `tercero()` - Relación con el cliente (belongsTo)
- `proveedor()` - Relación con el proveedor (belongsTo)
- `pedido()` - Relación con el pedido (belongsTo)
- `cotizacion()` - Relación con la cotización (belongsTo)
- `referencias()` - Relación many-to-many con referencias (belongsToMany)
- `pedidoReferencia()` - Relación con pedido referencia (belongsTo)

**Métodos**:
- `addReferencia($referenciaId, $cantidad, $valorUnitario, $valorTotal)` - Agregar referencia a la orden
- `getTotalReferencias()` - Obtener total de todas las referencias

**Características**:
- Gestión de órdenes de compra con agrupación por proveedor y cliente
- Sistema de referencias con cantidades y valores unitarios
- Estados visuales con colores (En proceso, Entregado, Cancelado)

## OrdenCompraReferencia: `app/Models/OrdenCompraReferencia.php`
**Namespace**: `App\Models`
**Clase**: `OrdenCompraReferencia`
**Tipo**: Modelo Pivot para relación many-to-many
**Tabla**: `orden_compra_referencia`

**Relaciones**:
- `ordenCompra()` - Relación con OrdenCompra (belongsTo)
- `referencia()` - Relación con Referencia (belongsTo)

**Atributos**:
- `cantidad` - Cantidad de unidades a comprar
- `valor_unitario` - Precio por unidad
- `valor_total` - Valor total de la referencia

**Características**:
- Modelo pivot para la relación entre órdenes de compra y referencias
- Gestión de cantidades y valores unitarios por referencia
- Cálculo automático de valores totales

## OrdenTrabajo: `app/Models/OrdenTrabajo.php`
**Namespace**: `App\Models`
**Clase**: `OrdenTrabajo`
**Métodos**:
- `tercero`
- `pedido`
- `cotizacion`
- `transportadora`
- `referencias`
- `direccion`

## OrdenTrabajoReferencia: `app/Models/OrdenTrabajoReferencia.php`
**Namespace**: `App\Models`
**Clase**: `OrdenTrabajoReferencia`
**Métodos**:
- `ordenTrabajo`
- `pedidoReferencia`
- `referencia`

## Pedido: `app/Models/Pedido.php`
**Namespace**: `App\Models`
**Clase**: `Pedido`
**Métodos**:
- `user`
- `tercero`
- `maquina`
- `referencias`
- `articulos`
- `referenciasProveedor`

## PedidoArticulo: `app/Models/PedidoArticulo.php`
**Namespace**: `App\Models`
**Clase**: `PedidoArticulo`

## PedidoReferencia: `app/Models/PedidoReferencia.php`
**Namespace**: `App\Models`
**Clase**: `PedidoReferencia`
**Métodos**:
- `pedido`
- `referencia`
- `proveedores`

## PedidoReferenciaProveedor: `app/Models/PedidoReferenciaProveedor.php`
**Namespace**: `App\Models`
**Clase**: `PedidoReferenciaProveedor`
**Métodos**:
- `pedidoReferencia`
- `referencia`
- `tercero`

## Referencia: `app/Models/Referencia.php`
**Namespace**: `App\Models`
**Clase**: `Referencia`
**Métodos**:
- `articuloReferencia`
- `articulos`
- `marca`
- `pedidos`
- `articuloJuegos`

## Sistema: `app/Models/Sistema.php`
**Namespace**: `App\Models`
**Clase**: `Sistema`
**Métodos**:
- `terceros`
- `listas`

## State: `app/Models/State.php`
**Namespace**: `App\Models`
**Clase**: `State`
**Métodos**:
- `country`
- `cities`

## TRM: `app/Models/TRM.php`
**Namespace**: `App\Models`
**Clase**: `TRM`

## Tercero: `app/Models/Tercero.php`
**Namespace**: `App\Models`
**Clase**: `Tercero`
**Métodos**:
- `country`
- `city`
- `states`
- `maquinas`
- `contactos`
- `direcciones`
- `pedidos`
- `sistemas`
- `fabricantes`
- `getProveedoresPorMarca`

## TerceroFabricante: `app/Models/TerceroFabricante.php`
**Namespace**: `App\Models`
**Clase**: `TerceroFabricante`
**Métodos**:
- `tercero`
- `fabricante`

## TerceroSistema: `app/Models/TerceroSistema.php`
**Namespace**: `App\Models`
**Clase**: `TerceroSistema`
**Métodos**:
- `tercero`
- `sistema`

## Transportadora: `app/Models/Transportadora.php`
**Namespace**: `App\Models`
**Clase**: `Transportadora`
**Métodos**:
- `city`
- `state`
- `country`

## User: `app/Models/User.php`
**Namespace**: `App\Models`
**Clase**: `User`
**Métodos**:
- `canAccessPanel`

## NuevaNotificacion: `app/Notifications/NuevaNotificacion.php`
**Namespace**: `App\Notifications`
**Clase**: `NuevaNotificacion`
**Métodos**:
- `__construct`
- `via`
- `toFilament`
- `toArray`

## PedidoCreadoNotification: `app/Notifications/PedidoCreadoNotification.php`
**Namespace**: `App\Notifications`
**Clase**: `PedidoCreadoNotification`
**Métodos**:
- `__construct`
- `via`
- `toArray`

## PedidoObserver: `app/Observers/PedidoObserver.php`
**Namespace**: `App\Observers`
**Clase**: `PedidoObserver`
**Métodos**:
- `created`
- `updated`

## ArticuloPolicy: `app/Policies/ArticuloPolicy.php`
**Namespace**: `App\Policies`
**Clase**: `ArticuloPolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `deleteAny`
- `forceDelete`
- `forceDeleteAny`
- `restore`
- `restoreAny`
- `replicate`
- `reorder`

## CotizacionPolicy: `app/Policies/CotizacionPolicy.php`
**Namespace**: `App\Policies`
**Clase**: `CotizacionPolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `restore`
- `forceDelete`

## EmpresaPolicy: `app/Policies/EmpresaPolicy.php`
**Namespace**: `App\Policies`
**Clase**: `EmpresaPolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `deleteAny`
- `forceDelete`
- `forceDeleteAny`
- `restore`
- `restoreAny`
- `replicate`
- `reorder`

## FabricantePolicy: `app/Policies/FabricantePolicy.php`
**Namespace**: `App\Policies`
**Clase**: `FabricantePolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `deleteAny`
- `forceDelete`
- `forceDeleteAny`
- `restore`
- `restoreAny`
- `replicate`
- `reorder`

## ListaPolicy: `app/Policies/ListaPolicy.php`
**Namespace**: `App\Policies`
**Clase**: `ListaPolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `deleteAny`
- `forceDelete`
- `forceDeleteAny`
- `restore`
- `restoreAny`
- `replicate`
- `reorder`

## MaquinaPolicy: `app/Policies/MaquinaPolicy.php`
**Namespace**: `App\Policies`
**Clase**: `MaquinaPolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `deleteAny`
- `forceDelete`
- `forceDeleteAny`
- `restore`
- `restoreAny`
- `replicate`
- `reorder`

## OrdenCompraPolicy: `app/Policies/OrdenCompraPolicy.php`
**Namespace**: `App\Policies`
**Clase**: `OrdenCompraPolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `restore`
- `forceDelete`

## OrdenTrabajoPolicy: `app/Policies/OrdenTrabajoPolicy.php`
**Namespace**: `App\Policies`
**Clase**: `OrdenTrabajoPolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `restore`
- `forceDelete`

## PedidoPolicy: `app/Policies/PedidoPolicy.php`
**Namespace**: `App\Policies`
**Clase**: `PedidoPolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `deleteAny`
- `forceDelete`
- `forceDeleteAny`
- `restore`
- `restoreAny`
- `replicate`
- `reorder`

## PermissionPolicy: `app/Policies/PermissionPolicy.php`
**Namespace**: `App\Policies`
**Clase**: `PermissionPolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `deleteAny`
- `forceDelete`
- `forceDeleteAny`
- `restore`
- `restoreAny`
- `replicate`
- `reorder`

## ReferenciaPolicy: `app/Policies/ReferenciaPolicy.php`
**Namespace**: `App\Policies`
**Clase**: `ReferenciaPolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `deleteAny`
- `forceDelete`
- `forceDeleteAny`
- `restore`
- `restoreAny`
- `replicate`
- `reorder`

## RolePolicy: `app/Policies/RolePolicy.php`
**Namespace**: `App\Policies`
**Clase**: `RolePolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `deleteAny`
- `forceDelete`
- `forceDeleteAny`
- `restore`
- `restoreAny`
- `replicate`
- `reorder`

## SistemaPolicy: `app/Policies/SistemaPolicy.php`
**Namespace**: `App\Policies`
**Clase**: `SistemaPolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `deleteAny`
- `forceDelete`
- `forceDeleteAny`
- `restore`
- `restoreAny`
- `replicate`
- `reorder`

## TerceroPolicy: `app/Policies/TerceroPolicy.php`
**Namespace**: `App\Policies`
**Clase**: `TerceroPolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `deleteAny`
- `forceDelete`
- `forceDeleteAny`
- `restore`
- `restoreAny`
- `replicate`
- `reorder`

## UserPolicy: `app/Policies/UserPolicy.php`
**Namespace**: `App\Policies`
**Clase**: `UserPolicy`
**Métodos**:
- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `deleteAny`
- `forceDelete`
- `forceDeleteAny`
- `restore`
- `restoreAny`
- `replicate`
- `reorder`

## AppServiceProvider: `app/Providers/AppServiceProvider.php`
**Namespace**: `App\Providers`
**Clase**: `AppServiceProvider`
**Métodos**:
- `register`
- `boot`

## AuthServiceProvider: `app/Providers/AuthServiceProvider.php`
**Namespace**: `App\Providers`
**Clase**: `AuthServiceProvider`
**Métodos**:
- `boot`

## BroadcastServiceProvider: `app/Providers/BroadcastServiceProvider.php`
**Namespace**: `App\Providers`
**Clase**: `BroadcastServiceProvider`
**Métodos**:
- `boot`

## EventServiceProvider: `app/Providers/EventServiceProvider.php`
**Namespace**: `App\Providers`
**Clase**: `EventServiceProvider`
**Métodos**:
- `boot`
- `shouldDiscoverEvents`

## DashboardPanelProvider: `app/Providers/Filament/DashboardPanelProvider.php`
**Namespace**: `App\Providers\Filament`
**Clase**: `DashboardPanelProvider`
**Métodos**:
- `panel`

## HomePanelProvider: `app/Providers/Filament/HomePanelProvider.php`
**Namespace**: `App\Providers\Filament`
**Clase**: `HomePanelProvider`
**Métodos**:
- `panel`

## LogisticaPanelProvider: `app/Providers/Filament/LogisticaPanelProvider.php`
**Namespace**: `App\Providers\Filament`
**Clase**: `LogisticaPanelProvider`
**Métodos**:
- `panel`

## PartesPanelProvider: `app/Providers/Filament/PartesPanelProvider.php`
**Namespace**: `App\Providers\Filament`
**Clase**: `PartesPanelProvider`
**Métodos**:
- `panel`

## VentasPanelProvider: `app/Providers/Filament/VentasPanelProvider.php`
**Namespace**: `App\Providers\Filament`
**Clase**: `VentasPanelProvider`
**Métodos**:
- `panel`

## FilamentServiceProvider: `app/Providers/FilamentServiceProvider.php`
**Namespace**: `App\Providers`
**Clase**: `FilamentServiceProvider`
**Métodos**:
- `register`
- `getTrm`
- `boot`

## RouteServiceProvider: `app/Providers/RouteServiceProvider.php`
**Namespace**: `App\Providers`
**Clase**: `RouteServiceProvider`
**Métodos**:
- `boot`

## ChatService: `app/Services/ChatService.php`
**Namespace**: `App\Services`
**Clase**: `ChatService`
**Métodos**:
- `logEvent`
- `sendMessage`

## DisplayImage: `app/View/Components/DisplayImage.php`
**Namespace**: `App\View\Components`
**Clase**: `DisplayImage`
**Métodos**:
- `__construct`
- `render`

## TestDisplay: `app/View/Components/TestDisplay.php`
**Namespace**: `App\View\Components`
**Clase**: `TestDisplay`
**Métodos**:
- `__construct`
- `render`

## TrmDisplay: `app/View/Components/TrmDisplay.php`
**Namespace**: `App\View\Components`
**Clase**: `TrmDisplay`
**Métodos**:
- `__construct`
- `getTrm`
- `render`

---

# 🚀 FUNCIONALIDADES IMPLEMENTADAS

## 📋 Issue #24: Agrupación de Órdenes de Compra por Proveedor y Cliente

### 🎯 **Descripción de la Tarea**
Modificar la vista de órdenes de compra para que las órdenes se agrupen y ordenen primero por proveedor y luego por cliente.

### ✅ **Criterios de Aceptación Cumplidos**
- ✅ La vista agrupa primero todas las órdenes de compra por proveedor
- ✅ Dentro de cada proveedor, las órdenes se ordenan por cliente
- ✅ Se mantiene la funcionalidad de búsqueda y filtrado
- ✅ Facilita el seguimiento y gestión masiva de órdenes por proveedor y cliente

### 🛠 **Implementación Técnica**

#### **Modelo OrdenCompra**
- **Nueva relación:** `proveedor()` para acceder al proveedor de la orden
- **Métodos:** `addReferencia()` y `getTotalReferencias()`
- **Relaciones:** Many-to-many con referencias vía tabla pivot

#### **Recurso OrdenCompraResource**
- **Agrupación automática** por `proveedor_id` y `tercero_id`
- **Filtros avanzados** por proveedor, cliente y estado
- **Columnas mejoradas** con información de proveedor y cliente
- **Compatibilidad total** con temas claro/oscuro de Filament

#### **Página ListOrdenCompras**
- **Vista personalizada** con agrupación visual
- **Header personalizado** con título y descripción
- **Agrupación automática** de datos por proveedor
- **Totales calculados** por proveedor y cliente

#### **Vista Personalizada**
- **Template:** `list-orden-compras-header.blade.php`
- **Diseño responsivo** con grid adaptativo
- **Sección de referencias** con cantidades y valores
- **Estados visuales** con colores e iconos
- **Compatibilidad temas** claro/oscuro

### 🎨 **Características de Diseño**

#### **Agrupación Visual**
```
┌─ Proveedor: GECOLSA (2 órdenes) ────────── Total: $250,000 ┐
├─ Cliente: OPERACIONES MINERAS SAS ─────── Total: $250,000 ─┤
│  ┌─ OC-1 ──────────────────────────────────────────────┐  │
│  │ • Pedido: #1                                        │  │
│  │ • Entrega: 25/08/2025                               │  │
│  │ • Valor: $100,000                                   │  │
│  │                                                     │  │
│  │ 📦 Referencias a Comprar:                           │  │
│  │   ┌─ RE506680: 5 uds - $20,000 ─────────────────┐  │  │
│  │   └─ Total: $100,000 ────────────────────────────┘  │  │
│  └─────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

#### **Compatibilidad con Temas**
- **Modo Claro:** Colores claros con texto oscuro
- **Modo Oscuro:** Colores oscuros con texto claro
- **Colores primarios:** Uso de sistema de colores de Filament
- **Contraste optimizado:** Máxima legibilidad en ambos temas

### 📊 **Estructura de Datos**

#### **Tabla orden_compra_referencia**
```sql
CREATE TABLE orden_compra_referencia (
    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    orden_compra_id bigint(20) UNSIGNED NOT NULL,
    referencia_id bigint(20) UNSIGNED NOT NULL,
    cantidad int(11) NOT NULL,
    valor_unitario decimal(10,2) NOT NULL,
    valor_total decimal(10,2) NOT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (orden_compra_id) REFERENCES orden_compras(id),
    FOREIGN KEY (referencia_id) REFERENCES referencias(id)
);
```

#### **Relaciones del Modelo**
```php
// OrdenCompra
public function proveedor() {
    return $this->belongsTo(Tercero::class, 'proveedor_id');
}

public function referencias() {
    return $this->belongsToMany(Referencia::class, 'orden_compra_referencia')
        ->using(OrdenCompraReferencia::class)
        ->withPivot('cantidad', 'valor_unitario', 'valor_total');
}
```

### 🧪 **Testing y Validación**

#### **Datos de Prueba Creados**
- **3 órdenes de compra** con referencias asociadas
- **2 proveedores diferentes** para probar agrupación
- **Referencias reales** con cantidades y valores
- **Totales calculados** automáticamente

#### **Funcionalidades Validadas**
- ✅ Agrupación por proveedor funcionando
- ✅ Sub-agrupación por cliente funcionando
- ✅ Referencias mostrando correctamente
- ✅ Totales calculados automáticamente
- ✅ Diseño responsivo funcionando
- ✅ Compatibilidad con temas verificada

### 📁 **Archivos Modificados/Creados**

#### **Modelos**
- `app/Models/OrdenCompra.php` - Relación proveedor y métodos
- `app/Models/OrdenCompraReferencia.php` - Nuevo modelo pivot

#### **Recursos Filament**
- `app/Filament/Resources/OrdenCompraResource.php` - Tabla mejorada
- `app/Filament/Resources/OrdenCompraResource/Pages/ListOrdenCompras.php` - Vista personalizada

#### **Vistas**
- `resources/views/filament/resources/orden-compra-resource/pages/list-orden-compras-header.blade.php` - Template de agrupación

#### **Migraciones**
- `database/migrations/2025_07_26_074225_create_orden_compras_table.php` - Tabla principal
- `database/migrations/2025_07_26_074230_create_orden_compra_referencia_table.php` - Tabla pivot

### 🚀 **Beneficios de la Implementación**

1. **Gestión Eficiente:** Agrupación visual clara por proveedor y cliente
2. **Seguimiento Simplificado:** Totales automáticos por grupo
3. **Referencias Detalladas:** Información completa de cada orden
4. **Diseño Moderno:** Interfaz atractiva y funcional
5. **Compatibilidad Total:** Funciona perfectamente en ambos temas
6. **Responsividad:** Adaptable a diferentes tamaños de pantalla

### 🔮 **Próximos Pasos Sugeridos**

1. **Testing en Producción:** Validar con datos reales
2. **Métricas de Uso:** Monitorear adopción de la funcionalidad
3. **Feedback de Usuarios:** Recopilar sugerencias de mejora
4. **Optimizaciones:** Aplicar mejoras basadas en uso real

---

**Fecha de Implementación:** 18 de Agosto, 2025  
**Commit:** `89ee644`  
**Estado:** ✅ **COMPLETADO Y FUNCIONANDO**
