<template>
    <Toolbar class="mb-6">
        <template #start>
            <Button label="Nuevo" icon="pi pi-plus" severity="contrast" class="mr-2" @click="goToTipo" />
            <Button label="Eliminar" icon="pi pi-trash" severity="danger" variant="outlined"
                @click="confirmDeleteSelected" :disabled="!selectedProducts || !selectedProducts.length" />
        </template>

        <template #center>
            <div class="flex items-center gap-2">
                <DatePicker v-model="fechaInicio" placeholder="Fecha Inicio" class="w-96" />
                <DatePicker v-model="fechaFin" placeholder="Fecha Fin" class="w-96" />
            </div>
        </template>

        <template #end>
            <div class="flex items-center gap-2">
                <Select v-model="selectedProductId" :options="productos" optionLabel="nombre" optionValue="id"
                    placeholder="Seleccionar Producto" class="w-48" @change="handleProductChange" />
                <Select v-if="tiposDocumento.length > 0" v-model="selectedTipoDocumentoId" :options="tiposDocumento"
                    optionLabel="nombre" optionValue="id" placeholder="Tipo de Documento" class="w-48"
                    @change="handleTipoDocumentoChange" />
            </div>
        </template>
    </Toolbar>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import Button from 'primevue/button'
import Toolbar from 'primevue/toolbar'
import Select from 'primevue/select'
import DatePicker from 'primevue/datepicker'

const emit = defineEmits(['filter-change'])

const selectedProducts = ref()
const selectedProductId = ref<number | null>(null)
const productos = ref<{ id: number | null, nombre: string }[]>([])
const fechaInicio = ref(null)
const fechaFin = ref(null)

const selectedTipoDocumentoId = ref<number | null>(null)
const tiposDocumento = ref<{ id: number, nombre: string }[]>([])

onMounted(async () => {
    try {
        const response = await axios.get('/productos')
        const data = response.data.data
        productos.value = [
            { id: null, nombre: 'Todos' },
            ...data.map((producto: any) => ({
                id: producto.id,
                nombre: producto.nombre
            }))
        ]
        selectedProductId.value = null
    } catch (error) {
        console.error('Error al cargar productos:', error)
    }
})

const handleProductChange = async () => {
    selectedTipoDocumentoId.value = null
    tiposDocumento.value = []

    if (!selectedProductId.value) {
        emitFilterChange()
        return
    }

    try {
        const response = await axios.get(`/seleccion/productos/${selectedProductId.value}/tipos-documento`)
        tiposDocumento.value = [
            { id: null, nombre: 'Todos los tipos' },
            ...(response.data.data || []).map((tipo: any) => ({
                id: tipo.id,
                nombre: tipo.nombre
            }))
        ]

        emitFilterChange()
    } catch (error) {
        console.error('Error al cargar tipos de documento:', error)
    }
}

const handleTipoDocumentoChange = () => {
    emitFilterChange()
}

const emitFilterChange = () => {
    const filters = {
        productoId: selectedProductId.value,
        tipoDocumentoId: selectedTipoDocumentoId.value,
        fechaInicio: fechaInicio.value,
        fechaFin: fechaFin.value
    }

    emit('filter-change', filters)
}

watch([fechaInicio, fechaFin], () => {
    emitFilterChange()
})

function confirmDeleteSelected() {
    console.log('Eliminar seleccionados:', selectedProducts.value)
}

function goToTipo() {
    router.visit('/panel/tipo')
}
</script>