<template>
    <Toolbar class="mb-6">
        <template #start>
            <Button label="Nueva Área" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
        </template>
    </Toolbar>

    <Dialog v-model:visible="areaDialog" :style="{ width: '600px' }" header="Registro de Área" :modal="true">
        <div class="flex flex-col gap-6">
            <div class="grid grid-cols-12 gap-4">
                <!-- Nombre -->
                <div class="col-span-12">
                    <label for="nombre" class="block font-bold mb-2">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <InputText id="nombre" v-model.trim="area.nombre" maxlength="255" fluid
                        :class="{ 'p-invalid': submitted && (!area.nombre || serverErrors.nombre) }" />
                    <small v-if="submitted && !area.nombre" class="text-red-500">El nombre es obligatorio.</small>
                    <small v-else-if="serverErrors.nombre" class="text-red-500">{{ serverErrors.nombre[0] }}</small>
                </div>
                <!-- Estado -->
                <div class="col-span-12">
                    <label for="estado_id" class="block font-bold mb-2">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <Select v-model="area.estado_id" :options="estados" optionLabel="nombre" optionValue="id"
                        placeholder="Seleccione un estado" class="w-full"
                        :class="{ 'p-invalid': submitted && (!area.estado_id || serverErrors.estado_id) }" />
                    <small v-if="submitted && !area.estado_id" class="text-red-500">Debe seleccionar un estado.</small>
                    <small v-else-if="serverErrors.estado_id" class="text-red-500">{{ serverErrors.estado_id[0]
                        }}</small>
                </div>
            </div>
        </div>

        <template #footer>
            <Button label="Cancelar" icon="pi pi-times" text @click="hideDialog" />
            <Button label="Guardar" icon="pi pi-check" :loading="loading"
                :disabled="!area.nombre || !area.estado_id || area.nombre.length > 255" @click="guardarArea" />
        </template>
    </Dialog>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Toolbar from 'primevue/toolbar';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import { useToast } from 'primevue/usetoast';
import { defineEmits } from 'vue';
import Select from 'primevue/select';

const toast = useToast();
const emit = defineEmits(['agregado']);

const areaDialog = ref(false);
const submitted = ref(false);
const loading = ref(false);
const serverErrors = ref({});

const area = ref({
    nombre: '',
    descripcion: '',
    estado_id: null,
});

const estados = ref([]);

function resetArea() {
    area.value = {
        nombre: '',
        descripcion: '',
        estado_id: null,
    };
    serverErrors.value = {};
    submitted.value = false;
    loading.value = false;
}

function openNew() {
    resetArea();
    areaDialog.value = true;
    cargarEstados();
}

function hideDialog() {
    areaDialog.value = false;
    resetArea();
}

async function cargarEstados() {
    try {
        const response = await axios.get('/estados');
        estados.value = response.data.data || response.data;
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'No se pudieron cargar los estados',
            life: 3000,
        });
    }
}

async function guardarArea() {
    submitted.value = true;
    serverErrors.value = {};
    loading.value = true;

    try {
        await axios.post('/areas', area.value);
        toast.add({
            severity: 'success',
            summary: 'Éxito',
            detail: 'Área registrada correctamente',
            life: 3000,
        });
        hideDialog();
        emit('agregado');
    } catch (error) {
        if (error.response && error.response.status === 422) {
            serverErrors.value = error.response.data.errors || {};
        } else {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'No se pudo registrar el área',
                life: 3000,
            });
        }
    } finally {
        loading.value = false;
    }
}
</script>
