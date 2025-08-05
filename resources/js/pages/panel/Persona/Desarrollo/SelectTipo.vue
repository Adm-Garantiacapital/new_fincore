<template>
    <div class="flex flex-col gap-2">
        <label for="producto_id" class="font-bold">Producto <span class="text-red-500">*</span></label>
        <Select id="producto_id" v-model="selectedProductoId" :options="productos" optionLabel="label"
            optionValue="id" placeholder="Seleccione un producto" class="w-full" />
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import Select from 'primevue/select';

const selectedProductoId = ref(null);
const productos = ref([]);

const emit = defineEmits(['update:modelValue', 'selected']);

watch(selectedProductoId, (newId) => {
    emit('update:modelValue', newId);
    const producto = productos.value.find(p => p.id === newId);
    emit('selected', producto);
});

async function cargarProductos() {
    try {
        const response = await axios.get('/productos');
        productos.value = (response.data.data || []).map(p => ({
            id: p.id,
            label: `${p.nombre} - ${p.descripcion}`
        }));
    } catch (error) {
        console.error('Error al cargar productos:', error);
    }
}

onMounted(() => {
    cargarProductos();
});
</script>
