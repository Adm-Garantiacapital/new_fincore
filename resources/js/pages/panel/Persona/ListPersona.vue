<template>
    <Head title="Persona" />
    <AppLayout>
        <div>
            <template v-if="isLoading">
                <Espera />
            </template>
            <template v-else>
                <div class="card">
                    <FilterPersona @filter-change="handleFilterChange" />
                    <ListPersonas :appliedFilters="currentFilters" ref="listPersonasRef" />
                </div>
            </template>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import AppLayout from '@/layout/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import Espera from '@/components/Espera.vue'
import FilterPersona from './DesarrolloList/FilterPersona.vue'
import ListPersonas from './DesarrolloList/ListPersonas.vue'

// Interfaces
interface FilterProps {
    productoId?: number | null
    tipoDocumentoId?: number | null
    fechaInicio?: Date | null
    fechaFin?: Date | null
}

// Refs
const isLoading = ref(true)
const currentFilters = ref<FilterProps>({})
const listPersonasRef = ref()

const handleFilterChange = (filters: FilterProps) => {
    currentFilters.value = { ...filters }
    console.log('Filtros aplicados:', filters)
    if (listPersonasRef.value && listPersonasRef.value.loadData) {

    }
}

onMounted(() => {
    setTimeout(() => {
        isLoading.value = false
    }, 1000)
})
</script>