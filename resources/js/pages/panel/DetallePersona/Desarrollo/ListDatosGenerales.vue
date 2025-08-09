<template>
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-4">
            <div class="flex items-center gap-2">
                <i class="pi pi-building text-xl"></i>
                <span class="font-semibold text-lg">Datos Generales</span>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <DataTable 
                :value="datosGeneralesArray" 
                responsiveLayout="scroll" 
                class="p-datatable-sm" 
                stripedRows
                :paginator="false"
            >
                <Column 
                    field="campo" 
                    header="Campo" 
                    class="font-semibold w-2/5"
                    headerClass="bg-gray-50"
                />
                <Column 
                    field="valor" 
                    header="Valor" 
                    headerClass="bg-gray-50"
                >
                    <template #body="slotProps">
                        <span class="font-medium">{{ slotProps.data.valor || '-' }}</span>
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

// Props
const { datos } = defineProps<{
    datos: {
        NombreComercial?: string;
        FechaInscripcion?: string;
        FechaInicioActividades?: string;
        EstadoContribuyente?: string;
        CondicionContribuyente?: string;
        DomicilioFiscal?: string;
        ActividadComercioExterior?: string;
        ActividadEconomica?: string;
    }
}>()

// Computed
const datosGeneralesArray = computed(() => {
    return [
        { campo: 'Nombre Comercial', valor: datos.NombreComercial },
        { campo: 'Fecha de Inscripción', valor: datos.FechaInscripcion },
        { campo: 'Fecha de Inicio de Actividades', valor: datos.FechaInicioActividades },
        { campo: 'Estado del Contribuyente', valor: datos.EstadoContribuyente },
        { campo: 'Condición del Contribuyente', valor: datos.CondicionContribuyente },
        { campo: 'Domicilio Fiscal', valor: datos.DomicilioFiscal },
        { campo: 'Actividad de Comercio Exterior', valor: datos.ActividadComercioExterior },
        { campo: 'Actividad Económica', valor: datos.ActividadEconomica }
    ]
})
</script>