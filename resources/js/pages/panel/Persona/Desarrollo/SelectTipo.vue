<template>
    <div class="flex flex-col gap-4">
        <div class="flex flex-row gap-6">
            <!-- Producto -->
            <div class="flex flex-col gap-2 w-full">
                <label for="producto_id" class="font-bold">Producto <span class="text-red-500">*</span></label>
                <Select id="producto_id" v-model="selectedProductoId" :options="productos" optionLabel="label"
                    optionValue="id" placeholder="Seleccione un producto" class="w-full" />
            </div>

            <!-- Tipo de documento -->
            <div v-if="tiposDocumento.length > 0" class="flex flex-col gap-2 w-full">
                <label for="tipo_id" class="font-bold">Tipo de Documento <span class="text-red-500">*</span></label>
                <Select id="tipo_id" v-model="selectedTipoId" :options="tiposDocumento" optionLabel="label"
                    optionValue="id" placeholder="Seleccione un tipo de documento" class="w-full" />
            </div>

            <!-- Moneda (solo si hay tipo de documento seleccionado) -->
            <div v-if="selectedTipoId" class="flex flex-col gap-2 w-full">
                <label for="moneda" class="font-bold">Moneda <span class="text-red-500">*</span></label>
                <Select id="moneda" v-model="selectedMoneda" :options="monedas" optionLabel="label"
                    optionValue="value" placeholder="Seleccione una moneda" class="w-full" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, inject } from 'vue';
import axios from 'axios';
import Select from 'primevue/select';

const emit = defineEmits(['update-selection']);

const selectedProductoId = inject('selectedProductoId');
const selectedTipoId = inject('selectedTipoId');
const camposPorGrupo = inject('camposPorGrupo');

const selectedMoneda = ref(null);

const productos = ref([]);
const tiposDocumento = ref([]);
const monedas = ref([]);

onMounted(async () => {
    try {
        const [productosRes, monedasRes] = await Promise.all([
            axios.get('/productos'),
            axios.get('/moneda')
        ]);

        productos.value = (productosRes.data.data || []).map(p => ({
            id: p.id,
            label: `${p.nombre} - ${p.descripcion}`
        }));

        monedas.value = (monedasRes.data.data || []).map(m => ({
            label: m.nombre,
            value: m.id
        }));

    } catch (error) {
        console.error('Error al cargar datos iniciales:', error);
    }
});

watch(selectedProductoId, async (productoId) => {
    selectedTipoId.value = null;
    selectedMoneda.value = null;
    tiposDocumento.value = [];
    camposPorGrupo.value = [];

    if (!productoId) {
        emitUpdate();
        return;
    }

    try {
        const response = await axios.get(`/seleccion/productos/${productoId}/tipos-documento`);
        tiposDocumento.value = (response.data.data || []).map(t => ({
            id: t.id,
            label: t.nombre,
        }));
        emitUpdate();
    } catch (error) {
        console.error('Error al obtener tipos de documento:', error);
    }
});

watch([selectedTipoId, selectedMoneda], () => {
    emitUpdate();
});

const emitUpdate = () => {
    emit('update-selection', {
        selectedProductoId: selectedProductoId.value,
        selectedTipoId: selectedTipoId.value,
        moneda: selectedMoneda.value,
        camposPorGrupo: camposPorGrupo.value
    });
};
</script>
