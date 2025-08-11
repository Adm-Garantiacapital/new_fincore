<template>
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4">
            <div class="flex items-center gap-2">
                <i class="pi pi-chart-line text-xl"></i>
                <span class="font-semibold text-lg">Información Financiera</span>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <div class="overflow-x-auto">
                <DataTable 
                    :value="informacionFinancieraArray" 
                    responsiveLayout="scroll" 
                    class="p-datatable-sm" 
                    stripedRows
                    :paginator="false"
                >
                    <Column 
                        field="concepto" 
                        header="Concepto" 
                        class="font-semibold min-w-80"
                        headerClass="font-bold"
                        :frozen="true"
                    />
                    <Column 
                        v-for="(year, index) in informacion.Anos" 
                        :key="index"
                        :field="`year${index}`" 
                        :header="year" 
                        class="text-right min-w-32"
                        headerClass="font-bold text-center"
                    >
                        <template #body="slotProps">
                            <span class="font-mono text-sm">{{ formatNumber(slotProps.data[`year${index}`]) }}</span>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

// Props
const { informacion } = defineProps<{
    informacion: {
        Anos?: string[];
        IngresosNetosPeriodo?: string[];
        OtrosIngresosDeclarados?: string[];
        TotalActivosNetos?: string[];
        CuentasPorCobrarComercialesTerceros?: string[];
        CuentasPorCobrarComercialesRelacionados?: string[];
        CuentasPorCobrarDiversasTerceros?: string[];
        CuentasPorCobrarDiversasRelacionados?: string[];
        CuentasPorCobrarAccionistasSociosDirectores?: string[];
        ProvisionCuentasCuentasCobranzaDudosa?: string[];
        TotalCuentasPorPagar?: string[];
        TotalPasivo?: string[];
        TotalPatrimonio?: string[];
        CapitalSocial?: string[];
        ResultadoBruto?: string[];
        ResultadoAntesParticipacionesImpuestos?: string[];
        ImportePagado?: string[];
        [key: string]: any;
    }
}>()

// Computed
const informacionFinancieraArray = computed(() => {
    const conceptosCompletos = [
        { key: 'IngresosNetosPeriodo', label: 'Ingresos Netos del Periodo' },
        { key: 'OtrosIngresosDeclarados', label: 'Otros Ingresos Declarados' },
        { key: 'TotalActivosNetos', label: 'Total Activos Netos' },
        { key: 'CuentasPorCobrarComercialesTerceros', label: 'Cuentas Por Cobrar Comerciales - Terceros' },
        { key: 'CuentasPorCobrarComercialesRelacionados', label: 'Cuentas Por Cobrar Comerciales - Relacionados' },
        { key: 'CuentasPorCobrarDiversasTerceros', label: 'Cuentas Por Cobrar Diversas - Terceros' },
        { key: 'CuentasPorCobrarDiversasRelacionados', label: 'Cuentas Por Cobrar Diversas - Relacionados' },
        { key: 'CuentasPorCobrarAccionistasSociosDirectores', label: 'Cuentas Por Cobrar a Accionistas, Socios, Directores' },
        { key: 'ProvisionCuentasCuentasCobranzaDudosa', label: 'Provisión por Cuentas de Cuentas de Cobranza Dudosa' },
        { key: 'TotalCuentasPorPagar', label: 'Total Cuentas Por Pagar' },
        { key: 'TotalPasivo', label: 'Total Pasivo' },
        { key: 'TotalPatrimonio', label: 'Total Patrimonio' },
        { key: 'CapitalSocial', label: 'Capital Social' },
        { key: 'ResultadoBruto', label: 'Resultado Bruto' },
        { key: 'ResultadoAntesParticipacionesImpuestos', label: 'Resultado Antes de Participaciones e Impuestos' },
        { key: 'ImportePagado', label: 'Importe Pagado' }
    ]

    return conceptosCompletos.map(concepto => {
        const row = {
            concepto: concepto.label
        }

        // Agregar los datos de cada año
        informacion.Anos?.forEach((year, index) => {
            row[`year${index}`] = informacion[concepto.key]?.[index] || '-'
        })

        return row
    })
})

// Methods
const formatNumber = (value: string | number) => {
    if (!value || value === '-') return '-'
    
    // Si es un número, formatearlo
    if (typeof value === 'number') {
        return new Intl.NumberFormat('es-PE', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(value)
    }
    
    // Si es string, intentar convertir y formatear
    const numValue = parseFloat(value.toString().replace(/,/g, ''))
    if (!isNaN(numValue)) {
        return new Intl.NumberFormat('es-PE', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(numValue)
    }
    
    return value
}
</script>