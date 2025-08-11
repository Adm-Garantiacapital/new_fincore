<template>
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-4">
            <div class="flex items-center gap-2">
                <i class="pi pi-calendar text-xl"></i>
                <span class="font-semibold text-lg">Información de las Declaraciones Mensuales</span>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <div class="space-y-6">
                <div 
                    v-for="(declaracionData, declaracionKey) in declaraciones"
                    :key="declaracionKey" 
                    class="border border-gray-200 rounded-lg overflow-hidden"
                >
                    <!-- Subtitle for each declaration -->
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <h4 class="text-gray-700 font-semibold flex items-center gap-2">
                            <i class="pi pi-file-o text-orange-500"></i>
                            {{ formatDeclaracionTitle(declaracionKey) }}
                        </h4>
                    </div>
                    
                    <!-- Table content -->
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <DataTable 
                                :value="formatDeclaracionMensual(declaracionData)" 
                                responsiveLayout="scroll" 
                                class="p-datatable-sm" 
                                stripedRows
                                :paginator="false"
                            >
                                <Column 
                                    v-for="(mes, index) in mesesArray" 
                                    :key="index" 
                                    :field="`mes${index}`" 
                                    :header="mes" 
                                    class="text-center min-w-24"
                                    headerClass="bg-gray-50 font-bold text-xs"
                                >
                                    <template #body="slotProps">
                                        <span class="font-mono text-sm">{{ formatNumber(slotProps.data[`mes${index}`]) }}</span>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

// Props
const { declaraciones } = defineProps<{
    declaraciones: {
        [key: string]: any[];
    }
}>()

// Constants
const mesesArray = [
    'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN',
    'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC'
]

// Methods
const formatDeclaracionMensual = (declaracionData: any[]) => {
    if (!Array.isArray(declaracionData)) return []

    const row = {}
    declaracionData.forEach((item, index) => {
        if (Array.isArray(item) && item.length >= 2) {
            row[`mes${index}`] = item[1] || '-'
        }
    })

    return [row]
}

const formatDeclaracionTitle = (key: string) => {
    const titleMap = {
        'EjercicioAnterior': 'Ejercicio Anterior',
        'EjercicioActual': 'Ejercicio Actual',
        'Declaraciones2023': 'Declaraciones 2023',
        'Declaraciones2024': 'Declaraciones 2024'
    }
    
    return titleMap[key] || key.replace(/([A-Z])/g, ' $1').trim()
}

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