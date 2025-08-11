<template>
    <Head title="Tipo de Persona" />
    <AppLayout>
        <div>
            <template v-if="isLoading">
                <Espera />
            </template>
            <template v-else>
                <div class="card">
                    <SelectTipo
                        @update-selection="handleSelectionUpdate"
                    />
                    <!-- Solo mostrar formularios cuando se haya seleccionado moneda -->
                    <template v-if="selectedTipoId && selectedMonedaId">
                        <AddDniPersona 
                            v-if="selectedTipoId === 1"
                            :selectedProductoId="selectedProductoId"
                            :selectedTipoId="selectedTipoId"
                            :selectedMonedaId="selectedMonedaId"
                        />
                        <AddRucPersona 
                            v-if="selectedTipoId === 2"
                            :selectedProductoId="selectedProductoId"
                            :selectedTipoId="selectedTipoId"
                            :selectedMonedaId="selectedMonedaId"
                        />
                        <AddCarnetPersona 
                            v-if="selectedTipoId === 3"
                            :selectedProductoId="selectedProductoId"
                            :selectedTipoId="selectedTipoId"
                            :selectedMonedaId="selectedMonedaId"
                        />
                    </template>
                </div>
            </template>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, provide } from 'vue';
import AppLayout from '@/layout/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import Espera from '@/components/Espera.vue';
import SelectTipo from './Desarrollo/SelectTipo.vue';
import AddDniPersona from './Desarrollo/Components/AddDniPersona.vue';
import AddRucPersona from './Desarrollo/Components/AddRucPersona.vue';
import AddCarnetPersona from './Desarrollo/Components/AddCarnetPersona.vue';

const isLoading = ref(true);

const selectedProductoId = ref(null);
const selectedTipoId = ref(null);
const selectedMonedaId = ref(null); // Agregar moneda
const camposPorGrupo = ref([]);

provide('selectedProductoId', selectedProductoId);
provide('selectedTipoId', selectedTipoId);
provide('selectedMonedaId', selectedMonedaId); // Proveer moneda
provide('camposPorGrupo', camposPorGrupo);

const handleSelectionUpdate = (data) => {
    selectedProductoId.value = data.selectedProductoId;
    selectedTipoId.value = data.selectedTipoId;
    selectedMonedaId.value = data.moneda; // Capturar moneda
    camposPorGrupo.value = data.camposPorGrupo;
};

onMounted(() => {
    setTimeout(() => {
        isLoading.value = false;
    }, 1000);
});
</script>