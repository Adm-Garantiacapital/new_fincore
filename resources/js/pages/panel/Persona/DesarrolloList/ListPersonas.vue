<template>
    <DataTable ref="dt" v-model:selection="selectedProducts" :value="filteredProducts" dataKey="id" :paginator="true"
        :rows="10" :filters="filters"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
        :rowsPerPageOptions="[5, 10, 25]"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products" class="p-datatable-sm"
        :loading="isLoading">

        <template #header>
            <div class="flex flex-wrap gap-2 items-center justify-between">
                <h4 class="m-0">Personas</h4>
                <div class="flex items-center gap-2">
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Buscar..." />
                    </IconField>
                </div>
            </div>
        </template>

        <Column selectionMode="multiple" style="width: 3rem" :exportable="false"></Column>
        <Column field="cliente.dni" header="Dni" sortable style="min-width: 6rem"></Column>
        <Column field="cliente.nombre" header="Nombre" sortable style="min-width: 15rem"></Column>
        <Column field="cliente.apellidos" header="Apellidos" sortable style="min-width: 15rem"></Column>
        <Column field="cliente.telefono" header="Telefono" sortable style="min-width: 9rem"></Column>
        <Column field="cliente.direccion" header="Direccion" sortable style="min-width: 12rem"></Column>
        <Column field="estado" header="Estado" sortable style="min-width: 8rem"></Column>
        <Column field="creacion" header="Creacion" sortable style="min-width: 12rem"></Column>
        <Column field="actualizacion" header="Actualizacion" sortable style="min-width: 12rem"></Column>
    </DataTable>
</template>

<script setup lang="ts">
import { ref, onMounted, watch, computed } from 'vue'
import { FilterMatchMode } from '@primevue/core/api'
import axios from 'axios'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'
import InputText from 'primevue/inputtext'

// Props
interface FilterProps {
    productoId?: number | null
    tipoDocumentoId?: number | null
    fechaInicio?: Date | null
    fechaFin?: Date | null
}

const props = defineProps<{
    appliedFilters?: FilterProps
}>()

// Refs
const dt = ref()
const products = ref<any[]>([])
const selectedProducts = ref()
const isLoading = ref(false)
const tipoDocumentoFilter = ref<number | null>(null)

const filters = ref({
    'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})

const filteredProducts = computed(() => {
    let filtered = [...products.value]

    if (props.appliedFilters) {
        const { productoId, tipoDocumentoId, fechaInicio, fechaFin } = props.appliedFilters

        if (productoId) {
            filtered = filtered.filter(product => product.productoId === productoId)
        }

        if (tipoDocumentoId) {
            filtered = filtered.filter(product => product.tipoDocumentoId === tipoDocumentoId)
        }

        if (fechaInicio) {
            filtered = filtered.filter(product =>
                new Date(product.fechaCreacion) >= new Date(fechaInicio)
            )
        }

        if (fechaFin) {
            filtered = filtered.filter(product =>
                new Date(product.fechaCreacion) <= new Date(fechaFin)
            )
        }
    }

    if (tipoDocumentoFilter.value) {
        filtered = filtered.filter(product => product.tipoDocumentoId === tipoDocumentoFilter.value)
    }

    return filtered
})

const loadData = async () => {
    isLoading.value = true
    try {
        // Cargar personas
        const personasResponse = await axios.get('/persona')
        products.value = personasResponse.data.data || []

        
    } catch (error) {
        console.error('Error al cargar datos:', error)
    } finally {
        isLoading.value = false
    }
}

watch(() => props.appliedFilters, () => {
}, { deep: true })

onMounted(() => {
    loadData()
})

defineExpose({
    selectedProducts,
    loadData
})
</script>