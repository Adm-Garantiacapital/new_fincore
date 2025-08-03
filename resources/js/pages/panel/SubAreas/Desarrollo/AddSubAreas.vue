<script setup>
import { ref, onMounted } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import Toolbar from 'primevue/toolbar'
import Dialog from 'primevue/dialog'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import { useToast } from 'primevue/usetoast'
import axios from 'axios'
import Select from 'primevue/select'

const { area } = defineProps({
    area: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['agregado'])

const toast = useToast()
const subAreaDialog = ref(false)
const submitted = ref(false)
const estados = ref([])

const form = useForm({
    nombre: '',
    estado_id: null,
    area_id: null
})

function openNew() {
    form.reset()
    form.clearErrors()
    form.area_id = area.id
    subAreaDialog.value = true
    submitted.value = false
}

function hideDialog() {
    subAreaDialog.value = false
    form.reset()
    form.clearErrors()
    submitted.value = false
}

function volver() {
    router.visit('/panel/area')
}

async function guardarSubArea() {
    submitted.value = true
    form.processing = true

    try {
        await axios.post('/subareas', {
            nombre: form.nombre,
            estado_id: form.estado_id,
            area_id: form.area_id
        })

        toast.add({
            severity: 'success',
            summary: 'Éxito',
            detail: 'Subárea registrada correctamente',
            life: 3000
        })

        hideDialog()
        emit('agregado')
    } catch (error) {
        if (error.response && error.response.status === 422) {
            form.errors = error.response.data.errors || {}
        }

        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'No se pudo registrar la subárea',
            life: 3000
        })
    } finally {
        form.processing = false
    }
}

async function cargarEstados() {
    try {
        const response = await axios.get('/estados')
        estados.value = response.data.data || response.data
    } catch (error) {
        console.error('Error al cargar estados:', error.response || error)
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'No se pudieron cargar los estados',
            life: 3000
        })
    }
}

onMounted(() => {
    cargarEstados()
})

defineExpose({ openNew })
</script>

<template>
    <Toolbar class="mb-6">
        <template #start>
            <p class="text-xl font-semibold">
                Lista de Subáreas de: {{ area.nombre }}
            </p>
        </template>

        <template #end>
            <Button label="Nuevo" icon="pi pi-plus" severity="contrast" class="mr-2" @click="openNew" />
            <Button label="Volver" icon="pi pi-arrow-left" severity="secondary" class="mr-2" @click="volver" />
        </template>
    </Toolbar>

    <Dialog v-model:visible="subAreaDialog" :style="{ width: '600px' }" header="Registro de Subárea" :modal="true">
        <div class="flex flex-col gap-6">
            <div class="grid grid-cols-12 gap-4">
                <!-- Nombre -->
                <div class="col-span-12">
                    <label for="nombre" class="block font-bold mb-2">Nombre <span class="text-red-500">*</span></label>
                    <InputText id="nombre" v-model.trim="form.nombre" maxlength="255" class="w-full"
                        :class="{ 'p-invalid': submitted && form.errors.nombre }" />
                    <small v-if="submitted && form.errors.nombre" class="text-red-500">{{ form.errors.nombre }}</small>
                </div>
                <!-- Estado -->
                <div class="col-span-12">
                    <label for="estado_id" class="block font-bold mb-2">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <Select v-model="form.estado_id" :options="estados" optionLabel="nombre" optionValue="id"
                        placeholder="Seleccione un estado" class="w-full"
                        :class="{ 'p-invalid': submitted && form.errors.estado_id }" />
                    <small v-if="submitted && form.errors.estado_id" class="text-red-500">
                        {{ form.errors.estado_id }}
                    </small>
                </div>
            </div>
        </div>

        <template #footer>
            <Button label="Cancelar" icon="pi pi-times" text @click="hideDialog" />
            <Button label="Guardar" icon="pi pi-check" :loading="form.processing"
                :disabled="!form.nombre || !form.estado_id || form.processing" @click="guardarSubArea" />
        </template>
    </Dialog>
</template>
