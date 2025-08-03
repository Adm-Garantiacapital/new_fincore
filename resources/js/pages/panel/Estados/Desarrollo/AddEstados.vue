<template>
  <Toolbar class="mb-6">
    <template #start>
      <Button label="Nuevo" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
    </template>
  </Toolbar>

  <Dialog v-model:visible="estadoDialog" :style="{ width: '600px' }" header="Registro de Estado" :modal="true"
    :closable="true" :closeOnEscape="true">
    <div class="flex flex-col gap-6">
      <div class="grid grid-cols-12 gap-4">
        <!-- Nombre -->
        <div class="col-span-12">
          <label for="nombre" class="block font-bold mb-2">
            Nombre <span class="text-red-500">*</span>
          </label>
          <InputText id="nombre" v-model.trim="estado.nombre" maxlength="255" fluid
            :class="{ 'p-invalid': submitted && (!estado.nombre || serverErrors.nombre) }" />
          <small v-if="submitted && !estado.nombre" class="text-red-500">El nombre es obligatorio.</small>
          <small v-else-if="submitted && estado.nombre.length > 255" class="text-red-500">
            El nombre no debe superar los 255 caracteres.
          </small>
          <small v-else-if="serverErrors.nombre" class="text-red-500">
            {{ serverErrors.nombre[0] }}
          </small>
        </div>

        <!-- Descripción -->
        <div class="col-span-12">
          <label for="descripcion" class="block font-bold mb-2">Descripción</label>
          <Textarea id="descripcion" v-model.trim="estado.descripcion" rows="4" autoResize fluid
            :class="{ 'p-invalid': submitted && serverErrors.descripcion }" />
          <small v-if="serverErrors.descripcion" class="text-red-500">
            {{ serverErrors.descripcion[0] }}
          </small>
        </div>
      </div>
    </div>

    <template #footer>
      <Button label="Cancelar" icon="pi pi-times" text @click="hideDialog" />
      <Button label="Guardar" icon="pi pi-check" :loading="loading"
        :disabled="!estado.nombre || estado.nombre.length > 255" @click="guardarEstado" />
    </template>
  </Dialog>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import Toolbar from 'primevue/toolbar';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import { useToast } from 'primevue/usetoast';
import { defineEmits } from 'vue';

const toast = useToast();
const emit = defineEmits(['agregado']);

const estadoDialog = ref(false);
const submitted = ref(false);
const loading = ref(false);
const serverErrors = ref({});

const estado = ref({
  nombre: '',
  descripcion: '',
});

function resetEstado() {
  estado.value = {
    nombre: '',
    descripcion: '',
  };
  serverErrors.value = {};
  submitted.value = false;
  loading.value = false;
}

function openNew() {
  resetEstado();
  estadoDialog.value = true;
}

function hideDialog() {
  estadoDialog.value = false;
  resetEstado();
}

async function guardarEstado() {
  submitted.value = true;
  serverErrors.value = {};
  loading.value = true;

  try {
    await axios.post('/estados', estado.value);
    toast.add({
      severity: 'success',
      summary: 'Éxito',
      detail: 'Estado registrado correctamente',
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
        detail: 'No se pudo registrar el estado',
        life: 3000,
      });
    }
  } finally {
    loading.value = false;
  }
}
</script>
