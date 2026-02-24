# SIS_COTIZACION

Sistema web para la gestión, análisis y validación de cotizaciones, desarrollado con un stack moderno basado en Laravel.  
Diseñado para separar responsabilidades entre el área comercial (**Ventas**) y el área técnica-financiera (**Costeos**).

---

# Descripción Técnica

`sis_cotizacion` es una aplicación web construida sobre una arquitectura MVC utilizando Laravel 11 como framework principal.  

El sistema permite:

- Gestión completa de cotizaciones.
- Flujo de trabajo entre Ventas y Costeos.
- Control de acceso basado en roles.
- Persistencia de información en base de datos relacional.
- Compilación moderna de assets con Vite.

---

# Stack Tecnológico

| Tecnología | Versión |
|------------|----------|
| Laravel | 11 |
| PHP | 8.2 |
| Node.js | LTS |
| Vite | Integrado con Laravel |
| MySQL | 8+ recomendado |
| Composer | Última versión estable |
| npm | Última versión estable |

---

# Roles del Sistema

## Ventas
- Crear cotizaciones.
- Consultar estatus.
- Enviar cotizaciones a Costeos.

## Costeos
- Calcular costos.
- Validar cotizaciones.
- Autorizar o ajustar valores.

El control de permisos se gestiona mediante el sistema de autenticación de Laravel y middleware de autorización.

---

# Instalación

## Clonar el repositorio

```bash
git clone <url-repositorio>
cd sis_cotizacion
```
## Instalar dependencias PHP
```bash
composer install
```
## Instalar dependencias Node
```bash
npm install
```
## Configurar entorno
```bash
cp .env.example .env
```
## Generar clave de aplicación
```bash
php artisan key:generate
```
## Ejecutar migraciones
```bash
php artisan migrate
```
## Compilar assets
```bash
npm run build
```
## Levantar servidor
```bash
php artisan serve
```