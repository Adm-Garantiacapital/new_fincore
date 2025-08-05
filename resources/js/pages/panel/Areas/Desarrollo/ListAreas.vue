<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3'; // 👈 Inertia router
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import axios from 'axios';
import Tag from 'primevue/tag';
import { debounce } from 'lodash';
import DeleteAreas from './DeleteAreas.vue';
import UpdateAreas from './UpdateAreas.vue';

const dt = ref();
const areas = ref([]);
const selectedAreas = ref();
const loading = ref(false);
const globalFilterValue = ref('');
const deleteDialog = ref(false);
const area = ref({});
const selectedAreaId = ref(null);
const updateDialog = ref(false);
const contadorAreas = ref(0);

const rowsPerPage = ref(10);
const currentPage = ref(1);

const props = defineProps({
    refresh: {
        type: Number,
        required: true
    }
});

watch(() => props.refresh, () => {
    loadAreas();
});

function editArea(area) {
    selectedAreaId.value = area.id;
    updateDialog.value = true;
}

function confirmDelete(areaData) {
    area.value = areaData;
    deleteDialog.value = true;
}

function handleAreaUpdated() {
    loadAreas();
}

function handleAreaDeleted() {
    loadAreas();
}

// 👇 Función para redirigir al hacer clic en la casita
function accionCasita(area) {
    router.visit(`/panel/sub/areas/${area.id}`);
}

const loadAreas = async () => {
    loading.value = true;
    try {
        const params = {
            search: globalFilterValue.value,
        };
        const response = await axios.get('/areas', { params });
        areas.value = response.data.data;
        contadorAreas.value = response.data.total;
        currentPage.value = 1;
    } catch (error) {
        console.error('Error al cargar áreas:', error);
    } finally {
        loading.value = false;
    }
};

const filteredAreas = computed(() => {
    const search = globalFilterValue.value.toLowerCase();
    return areas.value.filter((area) =>
        area.nombre.toLowerCase().includes(search) ||
        (area.estado && area.estado.toLowerCase().includes(search))
    );
});

const paginatedAreas = computed(() => {
    const start = (currentPage.value - 1) * rowsPerPage.value;
    return filteredAreas.value.slice(start, start + rowsPerPage.value);
});

const onGlobalSearch = debounce(() => {
    currentPage.value = 1;
}, 500);

const onPage = (event) => {
    currentPage.value = event.page + 1;
    rowsPerPage.value = event.rows;
};

onMounted(() => {
    loadAreas();
});
</script>
<template>
    <DataTable ref="dt" v-model:selection="selectedAreas" :value="paginatedAreas" dataKey="id" :paginator="true"
        :rows="rowsPerPage" :totalRecords="filteredAreas.length" :first="(currentPage - 1) * rowsPerPage"
        :loading="loading" @page="onPage" :rowsPerPageOptions="[5, 10, 20]" scrollable scrollHeight="574px"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} áreas" class="p-datatable-sm">

        <template #header>
            <div class="flex flex-wrap gap-2 items-center justify-between">
                <h4 class="m-0">
                    Áreas Generales
                    <Tag severity="contrast" :value="contadorAreas" />
                </h4>
                <div class="flex flex-wrap gap-2">
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="globalFilterValue" @input="onGlobalSearch" placeholder="Buscar..." />
                    </IconField>
                    <Button icon="pi pi-refresh" outlined rounded aria-label="Refresh" @click="loadAreas" />
                </div>
            </div>
        </template>

        <Column selectionMode="multiple" style="width: 1rem" :exportable="false" />
        <Column field="nombre" header="Nombre" sortable style="min-width: 13rem" />
        <Column field="estado" header="Estado" sortable style="min-width: 10rem" />
        <Column field="creacion" header="Creación" sortable style="min-width: 13rem" />
        <Column field="actualizacion" header="Actualización" sortable style="min-width: 13rem" />

        <Column>
            <template #body="slotProps">
                <Button icon="pi pi-home" outlined rounded class="mr-2" severity="contrast"
                    @click="accionCasita(slotProps.data)" />
                <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editArea(slotProps.data)" />
                <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDelete(slotProps.data)" />
            </template>
        </Column>
    </DataTable>

    <DeleteAreas v-model:visible="deleteDialog" :tipoCliente="area" @deleted="handleAreaDeleted" />
    <UpdateAreas v-model:visible="updateDialog" :tipoClienteId="selectedAreaId" @updated="handleAreaUpdated" />
</template>
