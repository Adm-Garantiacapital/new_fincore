<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import axios from 'axios';
import Tag from 'primevue/tag';
import { debounce } from 'lodash';
import DeleteSubAreas from './DeleteSubAreas.vue';
import UpdateSubAreas from './UpdateSubAreas.vue';

const props = defineProps({
    refresh: {
        type: Number,
        required: true,
    },
    areaId: {
        type: Number,
        required: true,
    },
});

const dt = ref();
const subAreas = ref([]);
const selectedSubAreas = ref();
const loading = ref(false);
const globalFilterValue = ref('');
const deleteDialog = ref(false);
const subArea = ref({});
const selectedSubAreaId = ref(null);
const updateDialog = ref(false);
const contadorSubAreas = ref(0);

const rowsPerPage = ref(10);
const currentPage = ref(1);

watch(() => props.refresh, () => {
    loadSubAreas();
});

function editSubArea(data) {
    selectedSubAreaId.value = data.id;
    updateDialog.value = true;
}

function confirmDelete(data) {
    subArea.value = data;
    deleteDialog.value = true;
}

function handleSubAreaUpdated() {
    loadSubAreas();
}

function handleSubAreaDeleted() {
    loadSubAreas();
}

const loadSubAreas = async () => {
    loading.value = true;
    try {
        const response = await axios.get(`/subareas/${props.areaId}/list`);
        subAreas.value = response.data.data;
        contadorSubAreas.value = response.data.total;
        currentPage.value = 1;
    } catch (error) {
        console.error('Error al cargar subáreas:', error);
    } finally {
        loading.value = false;
    }
};

const filteredSubAreas = computed(() => {
    const search = globalFilterValue.value.toLowerCase();
    return subAreas.value.filter((sa) =>
        sa.nombre.toLowerCase().includes(search) ||
        (sa.estado && sa.estado.toLowerCase().includes(search))
    );
});

const paginatedSubAreas = computed(() => {
    const start = (currentPage.value - 1) * rowsPerPage.value;
    return filteredSubAreas.value.slice(start, start + rowsPerPage.value);
});

const onGlobalSearch = debounce(() => {
    currentPage.value = 1;
}, 500);

const onPage = (event) => {
    currentPage.value = event.page + 1;
    rowsPerPage.value = event.rows;
};

onMounted(() => {
    loadSubAreas();
});
</script>

<template>
    <DataTable ref="dt" v-model:selection="selectedSubAreas" :value="paginatedSubAreas" dataKey="id" :paginator="true"
        :rows="rowsPerPage" :totalRecords="filteredSubAreas.length" :first="(currentPage - 1) * rowsPerPage"
        :loading="loading" @page="onPage" :rowsPerPageOptions="[5, 10, 20]" scrollable scrollHeight="574px"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} subáreas" class="p-datatable-sm">

        <template #header>
            <div class="flex flex-wrap gap-2 items-center justify-between">
                <h4 class="m-0">
                    Sub Áreas
                    <Tag severity="contrast" :value="contadorSubAreas" />
                </h4>
                <div class="flex flex-wrap gap-2">
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="globalFilterValue" @input="onGlobalSearch" placeholder="Buscar..." />
                    </IconField>
                    <Button icon="pi pi-refresh" outlined rounded aria-label="Refresh" @click="loadSubAreas" />
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
                <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editSubArea(slotProps.data)" />
                <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDelete(slotProps.data)" />
            </template>
        </Column>
    </DataTable>

    <DeleteSubAreas v-model:visible="deleteDialog" :tipoCliente="subArea" @deleted="handleSubAreaDeleted" />
    <UpdateSubAreas v-model:visible="updateDialog" :tipoClienteId="selectedSubAreaId" @updated="handleSubAreaUpdated" />
</template>
