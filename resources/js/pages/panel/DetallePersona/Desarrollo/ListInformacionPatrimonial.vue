<template>
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white p-4">
            <div class="flex items-center gap-2">
                <i class="pi pi-users text-xl"></i>
                <span class="font-semibold text-lg">Información de Participación Patrimonial</span>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <div class="overflow-x-auto">
                <DataTable 
                    :value="participacionPatrimonialArray" 
                    responsiveLayout="scroll" 
                    class="p-datatable-sm" 
                    stripedRows
                    :paginator="false"
                >
                    <Column 
                        field="tipoSocio" 
                        header="Tipo de Socio" 
                        class="min-w-32"
                        headerClass="bg-gray-50 font-bold"
                    />
                    <Column 
                        field="nombreRazon" 
                        header="Apellidos, nombres o razón social" 
                        class="min-w-64"
                        headerClass="bg-gray-50 font-bold"
                    >
                        <template #body="slotProps">
                            <span class="font-medium">{{ slotProps.data.nombreRazon }}</span>
                        </template>
                    </Column>
                    <Column 
                        field="tipoDocumento" 
                        header="Tipo de Documento" 
                        class="min-w-32"
                        headerClass="bg-gray-50 font-bold"
                    />
                    <Column 
                        field="numeroDocumento" 
                        header="Número de Documento" 
                        class="min-w-36"
                        headerClass="bg-gray-50 font-bold"
                    >
                        <template #body="slotProps">
                            <span class="font-mono">{{ slotProps.data.numeroDocumento }}</span>
                        </template>
                    </Column>
                    <Column 
                        field="participacion" 
                        header="% Participación" 
                        class="text-center min-w-32"
                        headerClass="bg-gray-50 font-bold"
                    >
                        <template #body="slotProps">
                            <div class="flex items-center justify-center">
                                <Tag 
                                    :value="slotProps.data.participacion + '%'" 
                                    :severity="getParticipacionSeverity(slotProps.data.participacion)"
                                    class="font-semibold"
                                />
                            </div>
                        </template>
                    </Column>
                    <Column 
                        field="fechaConstitucion" 
                        header="Fecha de Constitución" 
                        class="min-w-36"
                        headerClass="bg-gray-50 font-bold"
                    >
                        <template #body="slotProps">
                            <span class="text-sm">{{ formatDate(slotProps.data.fechaConstitucion) }}</span>
                        </template>
                    </Column>
                </DataTable>
            </div>
            
            <!-- Summary if there are multiple participants -->
            <div v-if="participacionPatrimonialArray.length > 1" class="mt-4 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-2 text-gray-700">
                    <i class="pi pi-info-circle"></i>
                    <span class="font-medium">Resumen:</span>
                    <span>{{ participacionPatrimonialArray.length }} participante(s) registrado(s)</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Tag from 'primevue/tag'

// Props
const { participacion } = defineProps<{
    participacion: {
        [key: string]: string[];
    }
}>()

// Computed
const participacionPatrimonialArray = computed(() => {
    const result = []

    Object.keys(participacion).forEach(key => {
        if (key.startsWith('Persona')) {
            const persona = participacion[key]
            if (persona && persona.length >= 6) {
                result.push({
                    tipoSocio: persona[0] || '',
                    nombreRazon: persona[1] || '',
                    tipoDocumento: persona[2] || '',
                    numeroDocumento: persona[3] || '',
                    participacion: persona[4] || '',
                    fechaConstitucion: persona[5] || ''
                })
            }
        }
    })

    return result
})

// Methods
const getParticipacionSeverity = (participacion: string) => {
    if (!participacion) return 'secondary'
    
    const num = parseFloat(participacion)
    if (num >= 50) return 'success'
    if (num >= 25) return 'warning'
    return 'info'
}

const formatDate = (date: string) => {
    if (!date) return '-'
    
    // Si viene en formato DD/MM/YYYY, convertir a formato más legible
    if (date.includes('/')) {
        const parts = date.split('/')
        if (parts.length === 3) {
            const day = parts[0].padStart(2, '0')
            const month = parts[1].padStart(2, '0')
            const year = parts[2]
            return `${day}/${month}/${year}`
        }
    }
    
    return date
}
</script>