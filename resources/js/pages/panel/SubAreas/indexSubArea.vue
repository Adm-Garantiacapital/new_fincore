<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layout/AppLayout.vue';
import Espera from '@/components/Espera.vue';
import AddSubAreas from './Desarrollo/AddSubAreas.vue';
import ListSubAreas from './Desarrollo/ListSubAreas.vue';

const props = defineProps({
    area: Object,
});

const isLoading = ref(true);
const refreshKey = ref(0);

function refrescarListado() {
    refreshKey.value++;
}

onMounted(() => {
    setTimeout(() => {
        isLoading.value = false;
    }, 1000);
});
</script>

<template>
    <Head title="Sub Áreas" />
    <AppLayout>
        <div>
            <template v-if="isLoading">
                <Espera />
            </template>
            <template v-else>
                <div class="card">
                    <AddSubAreas :area="area.data" @agregado="refrescarListado" />
                    <ListSubAreas :area-id="area.data.id" :refresh="refreshKey" />
                </div>
            </template>
        </div>
    </AppLayout>
</template>
