<template>
    <Head title="Detalle" />
    <AppLayout>
        <div>
            <template v-if="isLoading">
                <Espera />
            </template>
            <template v-else>
                <div class="card">
                    <AddSubida :detalle-persona="detallePersona" />
                </div>
            </template>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from '@/layout/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import Espera from '@/components/Espera.vue';
import AddSubida from './Desarrollo/AddSubida.vue';

const { detallePersona } = defineProps<{
    detallePersona: {
        data: {
            id: number;
            producto: string;
            estado: string;
            cliente?: {
                id: number;
                dni: string;
                nombre: string;
                apellidos: string;
                fecha_nacimiento: string;
                telefono: string;
                direccion: string;
            } | null;
            proveedor?: {
                id: number;
                ruc: string;
                pagina_web?: string;
                razon_social: string;
                nombre_comercial: string;
                fecha_inicio_actividad: string;
                actividad_economica: string;
                direccion: string;
                ventas_aproximadas?: number;
            } | null;
        };
    };
}>();

const isLoading = ref(true);

onMounted(() => {
    setTimeout(() => {
        isLoading.value = false;
    }, 1000);
});
</script>
