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
**Métodos**:
- `getGloballySearchableAttributes`
- `getGlobalSearchResultDetails`
- `form`
- `table`
- `infolist`
- `getRelations`
- `getPages`

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
**Métodos**:
- `getHeaderActions`

## OrdenCompraResource: `app/Filament/Resources/OrdenCompraResource.php`
**Namespace**: `App\Filament\Resources`
**Clase**: `OrdenCompraResource`
**Métodos**:
- `getNavigationBadge`
- `form`
- `table`
- `getRelations`
- `getPages`

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
**Métodos**:
- `tercero`
- `pedido`
- `cotizacion`
- `referencia`
- `pedidoReferencia`

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
- `pedido`
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
