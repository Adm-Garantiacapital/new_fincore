<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Tag from 'primevue/tag';
import axios from 'axios';
import { debounce } from 'lodash';
import DeleteEstados from './DeleteEstados.vue';
import UpdateEstados from './UpdateEstados.vue';

const dt = ref();
const tiposCliente = ref([]);
const selectedTiposCliente = ref();
const loading = ref(false);
const globalFilterValue = ref('');
const deleteDialog = ref(false);
const tipoCliente = ref({});
const selectedTipoClienteId = ref(null);
const selectedEstado = ref(null);
const updateDialog = ref(false);
const contadorEstados = ref(0);

const rowsPerPage = ref(10);
const currentPage = ref(1);

const props = defineProps({
    refresh: {
        type: Number,
        required: true
    }
});

watch(() => props.refresh, () => {
    loadTiposCliente();
});

function editTipoCliente(cliente) {
    selectedTipoClienteId.value = cliente.id;
    updateDialog.value = true;
}

function confirmDelete(cliente) {
    tipoCliente.value = cliente;
    deleteDialog.value = true;
}

function handleTipoClienteUpdated() {
    loadTiposCliente();
}

function handleTipoClienteDeleted() {
    loadTiposCliente();
}

const loadTiposCliente = async () => {
    loading.value = true;
    try {
        const params = {
            estado: selectedEstado.value?.value || '',
            search: globalFilterValue.value,
        };
        const response = await axios.get('/estados', { params });
        tiposCliente.value = response.data.data;
        contadorEstados.value = response.data.total;
        currentPage.value = 1;
    } catch (error) {
        console.error('Error al cargar tipos de cliente:', error);
    } finally {
        loading.value = false;
    }
};

const filteredEstados = computed(() => {
    const search = globalFilterValue.value.toLowerCase();
    return tiposCliente.value.filter((estado) =>
        estado.nombre.toLowerCase().includes(search) ||
        (estado.descripcion && estado.descripcion.toLowerCase().includes(search))
    );
});

const paginatedEstados = computed(() => {
    const start = (currentPage.value - 1) * rowsPerPage.value;
    return filteredEstados.value.slice(start, start + rowsPerPage.value);
});

const onGlobalSearch = debounce(() => {
    currentPage.value = 1;
}, 500);

const onPage = (event) => {
    currentPage.value = event.page + 1;
    rowsPerPage.value = event.rows;
};

onMounted(() => {
    loadTiposCliente();
});
</script>

<template>
    <DataTable ref="dt" v-model:selection="selectedTiposCliente" :value="paginatedEstados" dataKey="id"
        :paginator="true" :rows="rowsPerPage" :totalRecords="filteredEstados.length"
        :first="(currentPage - 1) * rowsPerPage" :loading="loading" @page="onPage" :rowsPerPageOptions="[5, 10, 20]"
        scrollable scrollHeight="574px"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} estados" class="p-datatable-sm">
        <template #header>
            <div class="flex flex-wrap gap-2 items-center justify-between">
                <h4 class="m-0">
                    Estados Generales
                    <Tag severity="contrast" :value="contadorEstados" />
                </h4>
                <div class="flex flex-wrap gap-2">
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="globalFilterValue" @input="onGlobalSearch" placeholder="Buscar..." />
                    </IconField>
                    <Button icon="pi pi-refresh" outlined rounded aria-label="Refresh" @click="loadTiposCliente" />
                </div>
            </div>
        </template>
        <Column selectionMode="multiple" style="width: 1rem" :exportable="false" />
        <Column field="nombre" header="Nombre" sortable style="min-width: 13rem" />
        <Column field="descripcion" header="Descripción" sortable style="min-width: 13rem" />
        <Column field="creacion" header="Creación" sortable style="min-width: 13rem" />
        <Column field="actualizacion" header="Actualización" sortable style="min-width: 13rem" />
        <Column :exportable="false" style="min-width: 8rem">
            <template #body="slotProps">
                <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editTipoCliente(slotProps.data)" />
                <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDelete(slotProps.data)" />
            </template>
        </Column>
    </DataTable>
    <DeleteEstados v-model:visible="deleteDialog" :tipoCliente="tipoCliente" @deleted="handleTipoClienteDeleted" />
    <UpdateEstados v-model:visible="updateDialog" :tipoClienteId="selectedTipoClienteId"
        @updated="handleTipoClienteUpdated" />
</template>
