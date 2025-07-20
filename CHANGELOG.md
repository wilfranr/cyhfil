# Changelog

Todas las modificaciones importantes en este proyecto serán documentadas en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es/1.0.0/), y este proyecto sigue [Semantic Versioning](https://semver.org/lang/es/).

## [Unreleased]

### Agregado
- [Funcionalidades nuevas en desarrollo aún no lanzadas]

---

## [1.0.0] - 2025-07-20

### Agregado
- Sistema de autenticación con roles (`superadmin`, `admin`, `vendedor`, `analistaPartes`, `logistica`).
- Paneles diferenciados por tipo de usuario usando FilamentPHP.
- Gestión de pedidos con multistep forms (wizard).
- Módulo de cotizaciones, incluyendo exportación en PDF.
- Asociación de pedidos con máquinas y referencias cruzadas.
- Gestión de clientes y proveedores desde un modelo `Tercero` unificado.
- Chat interno con registro de eventos (ej. creación de pedidos).
- Relación de referencias por máquina y control por departamento.
- Panel de administración para gestionar empresas con multitenancy.
- Soporte para subir logos (modo claro/oscuro) por empresa.
- Integración básica con almacenamiento en AWS S3.
- Configuración para despliegue en entorno de producción (AWS EC2 y RDS).