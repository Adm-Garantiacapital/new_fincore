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
import DeleteProductos from './DeleteProductos.vue';
import UpdateProductos from './UpdateProductos.vue';

const dt = ref();
const productos = ref([]);
const selectedProductos = ref();
const loading = ref(false);
const globalFilterValue = ref('');
const deleteDialog = ref(false);
const producto = ref({});
const selectedProductoId = ref(null);
const updateDialog = ref(false);
const contadorProductos = ref(0);

const rowsPerPage = ref(10);
const currentPage = ref(1);

const props = defineProps({
    refresh: {
        type: Number,
        required: true
    }
});

watch(() => props.refresh, () => {
    loadProductos();
});

function editProducto(prod) {
    selectedProductoId.value = prod.id;
    updateDialog.value = true;
}

function confirmDelete(prod) {
    producto.value = prod;
    deleteDialog.value = true;
}

function handleProductoUpdated() {
    loadProductos();
}

function handleProductoDeleted() {
    loadProductos();
}

const loadProductos = async () => {
    loading.value = true;
    try {
        const params = {
            search: globalFilterValue.value,
        };
        const response = await axios.get('/productos', { params });
        productos.value = response.data.data;
        contadorProductos.value = response.data.total;
        currentPage.value = 1;
    } catch (error) {
        console.error('Error al cargar productos:', error);
    } finally {
        loading.value = false;
    }
};

const filteredProductos = computed(() => {
    const search = globalFilterValue.value.toLowerCase();
    return productos.value.filter((producto) =>
        producto.nombre.toLowerCase().includes(search) ||
        (producto.descripcion && producto.descripcion.toLowerCase().includes(search)) ||
        (producto.estado && producto.estado.toLowerCase().includes(search))
    );
});

const paginatedProductos = computed(() => {
    const start = (currentPage.value - 1) * rowsPerPage.value;
    return filteredProductos.value.slice(start, start + rowsPerPage.value);
});

const onGlobalSearch = debounce(() => {
    currentPage.value = 1;
}, 500);

const onPage = (event) => {
    currentPage.value = event.page + 1;
    rowsPerPage.value = event.rows;
};

onMounted(() => {
    loadProductos();
});
</script>

<template>
    <DataTable ref="dt" v-model:selection="selectedProductos" :value="paginatedProductos" dataKey="id"
        :paginator="true" :rows="rowsPerPage" :totalRecords="filteredProductos.length"
        :first="(currentPage - 1) * rowsPerPage" :loading="loading" @page="onPage" :rowsPerPageOptions="[5, 10, 20]"
        scrollable scrollHeight="574px"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} productos" class="p-datatable-sm">

        <template #header>
            <div class="flex flex-wrap gap-2 items-center justify-between">
                <h4 class="m-0">
                    Productos
                    <Tag severity="contrast" :value="contadorProductos" />
                </h4>
                <div class="flex flex-wrap gap-2">
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="globalFilterValue" @input="onGlobalSearch" placeholder="Buscar..." />
                    </IconField>
                    <Button icon="pi pi-refresh" outlined rounded aria-label="Refresh" @click="loadProductos" />
                </div>
            </div>
        </template>

        <Column selectionMode="multiple" style="width: 1rem" :exportable="false" />
        <Column field="nombre" header="Nombre" sortable style="min-width: 13rem" />
        <Column field="descripcion" header="Descripción" sortable style="min-width: 13rem" />
        <Column field="estado" header="Estado" sortable style="min-width: 8rem" />
        <Column field="creacion" header="Creación" sortable style="min-width: 13rem" />
        <Column field="actualizacion" header="Actualización" sortable style="min-width: 13rem" />
        <Column :exportable="false" style="min-width: 8rem">
            <template #body="slotProps">
                <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editProducto(slotProps.data)" />
                <Button icon="pi pi-trash" outlined rounded severity="danger"
                    @click="confirmDelete(slotProps.data)" />
            </template>
        </Column>
    </DataTable>

    <DeleteProductos v-model:visible="deleteDialog" :producto="producto" @deleted="handleProductoDeleted" />
    <UpdateProductos v-model:visible="updateDialog" :productoId="selectedProductoId" @updated="handleProductoUpdated" />
</template>
