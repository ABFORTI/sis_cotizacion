<td class="border border-gray-300 p-2">
    <div class="grid gap-2" id="grid-estaticida">

        <select class="w-full rounded-md p-1 border-blue-600 bg-blue-500 font-medium text-white"
            name="aplicacion_estaticida"
            onchange="toggleEstaticidaInputs()">
            <option value="no">No</option>
            <option value="si">Sí</option>
        </select>

        <!-- Personas (editable por el usuario) -->
        <input type="number" step="1" min="0"
            name="no_personas_estaticida"
            id="aux-personas-estaticida"
            placeholder="No. de personas"
            class="w-full border border-gray-300 rounded-md p-1 hidden"
            oninput="calcularEstaticida()">

        <!-- Piezas por hora (solo informativo) -->
        <input type="number" step="0.01"
            name="piezas_por_hora_estaticida"
            id="aux-piezas-estaticida"
            placeholder="Piezas x hora"
            class="w-full border border-gray-300 rounded-md p-1 hidden"
            readonly>

    </div>
</td>


<script>
function calcularEstaticida() {

    /* === CONSTANTES === */
    const costo_litro_dolar = 7.00;
    const tipo_cambio = 19.00;
    const costo_diario_persona = 450;
    const horas_turno = 11;

    /* === VALORES DEL SISTEMA === */
    const piezas_turno =
        parseFloat(document.querySelector('input[name="total_piezas_turno_suaje"]')?.value) || 0;

    /* === VALORES DEL USUARIO === */
    const personas =
        parseInt(document.querySelector('input[name="no_personas_estaticida"]')?.value) || 0;

    if (piezas_turno === 0) return;

    /* === CÁLCULOS === */

    // Conversión a pesos
    const conversion_pesos = costo_litro_dolar * tipo_cambio;

    // Piezas por hora
    const piezas_por_hora = piezas_turno / horas_turno;

    // Costo de estaticida por pieza
    const estaticidad_pieza = conversion_pesos / piezas_por_hora;

    // Mano de obra
    const total_MO = personas * costo_diario_persona;
    const MO_por_pieza = total_MO / piezas_turno;

    // Costo total
    const estaticida_total = estaticidad_pieza + MO_por_pieza;

    /* === OUTPUT FRONT === */
    document.querySelector('input[name="piezas_por_hora_estaticida"]').value =
        piezas_por_hora.toFixed(2);

    document.querySelector('input[name="costo_estaticida_total"]').value =
        estaticida_total.toFixed(2);

    document.querySelector('input[name="resumen_costo_estaticidad"]').value =
        estaticida_total.toFixed(2);

    calcularResumenCostos();
}
</script>
