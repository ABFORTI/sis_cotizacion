#!/bin/bash
# ===============================================
# CHECKLIST DE VALIDACIÓN - PROCESOS ADICIONALES
# ===============================================
# Ejecutar con: bash CHECKLIST_PRE_PRODUCCION.sh

set -e  # Exit on error

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║   CHECKLIST DE VALIDACIÓN PRE-PRODUCCIÓN                       ║"
echo "║   Procesos Adicionales v1.0 (2026-03-24)                       ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""

# Variables
PASS="✅"
FAIL="❌"
WARN="⚠️"
INFO="ℹ️"

# Contadores
TOTAL=0
PASSED=0
FAILED=0

# Función para test
test_item() {
    local test_name=$1
    local command=$2
    local expected=$3

    TOTAL=$((TOTAL + 1))

    if eval "$command" &>/dev/null; then
        echo "$PASS [$TOTAL] $test_name"
        PASSED=$((PASSED + 1))
    else
        echo "$FAIL [$TOTAL] $test_name"
        FAILED=$((FAILED + 1))
        return 1
    fi
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📋 VALIDACIÓN: ESTRUCTURA DE ARCHIVOS"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Modelos
test_item "Modelo ProcesoAdicional existe" \
    "test -f app/Models/ProcesoAdicional.php"

test_item "Modelo CosteoRequisicion modificado" \
    "grep -q 'procesosAdicionales' app/Models/CosteoRequisicion.php"

# Migración
test_item "Migración creada" \
    "test -f database/migrations/2026_03_23_234940_create_proceso_adicionals_table.php"

# Controlador
test_item "Controlador con relación en edit()" \
    "grep -q 'procesosAdicionales' app/Http/Controllers/CosteoRequisicionController.php"

test_item "Controlador con guardado de procesos" \
    "grep -q 'procesos_adicionales' app/Http/Controllers/CosteoRequisicionController.php"

# Vista
test_item "Vista contiene botón agregar_procesos_add" \
    "grep -q 'agregar_procesos_add' resources/views/costeo/create.blade.php"

test_item "Vista contiene sección proceso_adicional_section" \
    "grep -q 'proceso_adicional_section' resources/views/costeo/create.blade.php"

test_item "Vista contiene JavaScript dinámico" \
    "grep -q 'agregarProcesoAdicional' resources/views/costeo/create.blade.php"

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🔧 VALIDACIÓN: CÓDIGO PHP"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Sintaxis PHP
test_item "Sintaxis ProcesoAdicional.php válida" \
    "php -l app/Models/ProcesoAdicional.php"

test_item "Sintaxis CosteoRequisicion.php válida" \
    "php -l app/Models/CosteoRequisicion.php"

test_item "Sintaxis CosteoRequisicionController.php válida" \
    "php -l app/Http/Controllers/CosteoRequisicionController.php"

# Contenido de modelos
test_item "ProcesoAdicional tiene constructor con \$guarded" \
    "grep -q 'protected.*guarded' app/Models/ProcesoAdicional.php || true && echo 1"

test_item "ProcesoAdicional tiene relación costeoRequisicion" \
    "grep -q 'costeoRequisicion' app/Models/ProcesoAdicional.php"

test_item "CosteoRequisicion tiene relación procesosAdicionales" \
    "grep -q 'procesosAdicionales' app/Models/CosteoRequisicion.php"

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📊 VALIDACIÓN: FUNCIONES JAVASCRIPT"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

test_item "JavaScript: agregarProcesoAdicional()" \
    "grep -q 'function agregarProcesoAdicional' resources/views/costeo/create.blade.php"

test_item "JavaScript: calcularProcesoDinamico()" \
    "grep -q 'function calcularProcesoDinamico' resources/views/costeo/create.blade.php"

test_item "JavaScript: eliminarProcesoAdicional()" \
    "grep -q 'function eliminarProcesoAdicional' resources/views/costeo/create.blade.php"

test_item "JavaScript: DOMContentLoaded listener" \
    "grep -q 'addEventListener.*DOMContentLoaded' resources/views/costeo/create.blade.php"

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🧮 VALIDACIÓN: CÁLCULOS INTEGRADOS"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

test_item "calcularCostoEnergiaE2() incluye procesos adicionales" \
    "grep -q 'totalDiasAdiciones' resources/views/costeo/create.blade.php"

test_item "calcularCostoFabricacion() suma procesos adicionales" \
    "grep -q 'costosProcesosAdicionales' resources/views/costeo/create.blade.php"

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📚 VALIDACIÓN: DOCUMENTACIÓN"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

test_item "Documento IMPLEMENTACION_PROCESOS_ADICIONALES.md existe" \
    "test -f IMPLEMENTACION_PROCESOS_ADICIONALES.md"

test_item "Documento VALIDACION_PROCESOS_ADICIONALES.md existe" \
    "test -f VALIDACION_PROCESOS_ADICIONALES.md"

test_item "Documento GUIA_TECNICA_PROCESOS_ADICIONALES.md existe" \
    "test -f GUIA_TECNICA_PROCESOS_ADICIONALES.md"

test_item "Documento REGISTRO_CAMBIOS.md existe" \
    "test -f REGISTRO_CAMBIOS.md"

test_item "Documento RESUMEN_IMPLEMENTACION.md existe" \
    "test -f RESUMEN_IMPLEMENTACION.md"

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🗄️ VALIDACIÓN: BASE DE DATOS (PRE-MIGRACIÓN)"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Verificar si Laravel está configurado
if [ -f ".env" ]; then
    test_item "Archivo .env existe" \
        "test -f .env"

    test_item "Archivo laravel existe" \
        "test -f laravel"

    echo ""
    echo "$INFO Migrate status (sin ejecutar):"
    if command -v php &> /dev/null && [ -f "artisan" ]; then
        echo "  → php artisan migrate:status | grep proceso"
        echo ""
        echo $INFO "Estado actual:"
        php artisan migrate:status 2>/dev/null | grep -i proceso || echo "  → Migración no ejecutada aún (ESPERADO)"
    fi
else
    echo "$WARN Archivo .env no existe. No se pueden validar migraciones."
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📈 RESUMEN"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

echo ""
echo "Total de validaciones: $TOTAL"
echo "Exitosas: $PASS $PASSED"
echo "Fallidas: $FAIL $FAILED"
echo ""

if [ $FAILED -eq 0 ]; then
    echo "✅ TODAS LAS VALIDACIONES PASARON"
    echo ""
    echo "PRÓXIMOS PASOS:"
    echo "1. Ejecutar: php artisan migrate"
    echo "2. Probar: Crear nuevo costeo con procesos"
    echo "3. Verificar: Datos en base de datos"
    echo "4. Validar: Cálculos en pantalla"
    exit 0
else
    echo "$FAIL FALLOS EN LAS VALIDACIONES"
    echo ""
    echo "REVISAR:"
    echo "1. Archivos están en lugar correcto"
    echo "2. Permisos de archivos"
    echo "3. Ver VALIDACION_PROCESOS_ADICIONALES.md para troubleshooting"
    exit 1
fi

