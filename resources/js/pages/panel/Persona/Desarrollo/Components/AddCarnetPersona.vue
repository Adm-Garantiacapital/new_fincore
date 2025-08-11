<template>
    <div class="mt-6">
        <!-- Indicador de progreso del formulario -->
        <Message severity="contrast">Complete el formulario para registrar una persona con carnet de extranjería
        </Message>
        <form @submit.prevent="submitForm" class="flex flex-col gap-6 py-5">
            <!-- Datos Personales -->
            <div class="flex flex-col gap-4">
                <h3 class="font-semibold text-lg border-b pb-1">
                    <i class="pi pi-user mr-2"></i>
                    Registro de Prospecto - Persona con carnet de extranjería
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Número de Carnet de Extranjería -->
                    <div class="flex flex-col gap-2">
                        <label for="documento" class="font-medium">
                            <i class="pi pi-id-card mr-1 text-sm"></i>
                            N° Carnet de Extranjería <span class="text-red-500">*</span>
                        </label>
                        <InputText id="documento" v-model="formData.documento"
                            placeholder="Ingrese número de carnet"
                            :class="['w-full', getFieldClass('documento')]" maxlength="12" />
                        <small v-if="errors.documento" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.documento[0] }}
                        </small>
                    </div>

                    <!-- Nombres -->
                    <div class="flex flex-col gap-2">
                        <label for="nombre" class="font-medium">
                            <i class="pi pi-user mr-1 text-sm"></i>
                            Nombres <span class="text-red-500">*</span>
                        </label>
                        <InputText id="nombre" v-model="formData.nombre" placeholder="Ingrese nombres"
                            :class="['w-full', getFieldClass('nombre')]" @input="capitalizeInput('nombre')" />
                        <small v-if="errors.nombre" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.nombre[0] }}
                        </small>
                    </div>

                    <!-- Apellido Paterno -->
                    <div class="flex flex-col gap-2">
                        <label for="apellido_paterno" class="font-medium">
                            <i class="pi pi-user mr-1 text-sm"></i>
                            Apellido Paterno <span class="text-red-500">*</span>
                        </label>
                        <InputText id="apellido_paterno" v-model="formData.apellido_paterno"
                            placeholder="Ingrese apellido paterno"
                            :class="['w-full', getFieldClass('apellido_paterno')]"
                            @input="capitalizeInput('apellido_paterno')" />
                        <small v-if="errors.apellido_paterno" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.apellido_paterno[0] }}
                        </small>
                    </div>

                    <!-- Apellido Materno -->
                    <div class="flex flex-col gap-2">
                        <label for="apellido_materno" class="font-medium">
                            <i class="pi pi-user mr-1 text-sm"></i>
                            Apellido Materno <span class="text-red-500">*</span>
                        </label>
                        <InputText id="apellido_materno" v-model="formData.apellido_materno"
                            placeholder="Ingrese apellido materno"
                            :class="['w-full', getFieldClass('apellido_materno')]"
                            @input="capitalizeInput('apellido_materno')" />
                        <small v-if="errors.apellido_materno" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.apellido_materno[0] }}
                        </small>
                    </div>

                    <!-- Nacionalidad -->
                    <div class="flex flex-col gap-2">
                        <label for="nacionalidad" class="font-medium">
                            <i class="pi pi-flag mr-1 text-sm"></i>
                            Nacionalidad <span class="text-red-500">*</span>
                        </label>
                        <InputText id="nacionalidad" v-model="formData.nacionalidad" placeholder="Ej: Venezolana"
                            :class="['w-full', getFieldClass('nacionalidad')]"
                            @input="capitalizeInput('nacionalidad')" />
                        <small v-if="errors.nacionalidad" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.nacionalidad[0] }}
                        </small>
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div class="flex flex-col gap-2">
                        <label for="fecha_nacimiento" class="font-medium">
                            <i class="pi pi-calendar mr-1 text-sm"></i>
                            Fecha de Nacimiento <span class="text-red-500">*</span>
                        </label>
                        <DatePicker id="fecha_nacimiento" v-model="formData.fecha_nacimiento" dateFormat="dd/mm/yy"
                            placeholder="Seleccione fecha de nacimiento"
                            :class="['w-full', getFieldClass('fecha_nacimiento')]" showIcon :maxDate="maxBirthDate"
                            :yearRange="yearRange" />
                        <small v-if="errors.fecha_nacimiento" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.fecha_nacimiento[0] }}
                        </small>
                        <small v-else-if="age && age < 18" class="text-orange-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            Edad: {{ age }} años (menor de edad)
                        </small>
                        <small v-else-if="age" class="text-green-600 flex items-center">
                            <i class="pi pi-check-circle mr-1"></i>
                            Edad: {{ age }} años
                        </small>
                    </div>

                    <!-- Sexo -->
                    <div class="flex flex-col gap-2">
                        <label for="sexo" class="font-medium">
                            <i class="pi pi-users mr-1 text-sm"></i>
                            Sexo <span class="text-red-500">*</span>
                        </label>
                        <Select id="sexo" v-model="formData.sexo" :options="sexoOptions" optionLabel="label"
                            optionValue="value" placeholder="Seleccione sexo"
                            :class="['w-full', getFieldClass('sexo')]" />
                        <small v-if="errors.sexo" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.sexo[0] }}
                        </small>
                    </div>

                    <!-- Estado Civil -->
                    <div class="flex flex-col gap-2">
                        <label for="estado_civil" class="font-medium">
                            <i class="pi pi-heart mr-1 text-sm"></i>
                            Estado Civil <span class="text-red-500">*</span>
                        </label>
                        <Select id="estado_civil" v-model="formData.estado_civil" :options="estadoCivilOptions"
                            optionLabel="label" optionValue="value" placeholder="Seleccione estado civil"
                            :class="['w-full', getFieldClass('estado_civil')]" />
                        <small v-if="errors.estado_civil" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.estado_civil[0] }}
                        </small>
                    </div>

                    <!-- Dirección -->
                    <div class="flex flex-col gap-2 md:col-span-2 lg:col-span-4">
                        <label for="direccion" class="font-medium">
                            <i class="pi pi-map-marker mr-1 text-sm"></i>
                            Dirección <span class="text-red-500">*</span>
                        </label>
                        <InputText id="direccion" v-model="formData.direccion"
                            placeholder="Ingrese dirección completa (Av./Jr./Calle, número, distrito)"
                            :class="['w-full', getFieldClass('direccion')]" />
                        <small v-if="errors.direccion" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.direccion[0] }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Información Financiera -->
            <div class="flex flex-col gap-4">
                <h3 class="font-semibold text-lg border-b pb-1 flex items-center gap-2">
                    <i class="pi pi-dollar"></i>
                    Información de Parámetros Sugeridos
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Monto Comisión -->
                    <div class="flex flex-col gap-1">
                        <label for="monto_comision" class="font-medium text-sm flex items-center gap-1">
                            <i class="pi pi-money-bill"></i> Comisión <span class="text-red-500">*</span>
                        </label>
                        <InputNumber id="monto_comision" v-model="formData.monto_comision" placeholder="Ej: 2500.00"
                            :minFractionDigits="2" :maxFractionDigits="2" :min="0"
                            :class="['w-full', getFieldClass('monto_comision')]" />
                        <small class="text-gray-500">Valor de la comisión por la operación.</small>
                        <small v-if="errors.monto_comision" class="text-red-500">{{ errors.monto_comision[0] }}</small>
                    </div>

                    <!-- Línea Cliente -->
                    <div class="flex flex-col gap-1">
                        <label for="linea_cliente" class="font-medium text-sm flex items-center gap-1">
                            <i class="pi pi-briefcase"></i> Línea Cliente <span class="text-red-500">*</span>
                        </label>
                        <InputNumber id="linea_cliente" v-model="formData.linea_cliente" placeholder="Ej: 50000.00"
                            :minFractionDigits="2" :maxFractionDigits="2" :min="0"
                            :class="['w-full', getFieldClass('linea_cliente')]" />
                        <small class="text-gray-500">Monto máximo aprobado para el cliente.</small>
                        <small v-if="errors.linea_cliente" class="text-red-500">{{ errors.linea_cliente[0] }}</small>
                    </div>

                    <!-- Adelanto -->
                    <div class="flex flex-col gap-1">
                        <label for="adelanto" class="font-medium text-sm flex items-center gap-1">
                            <i class="pi pi-wallet"></i> Adelanto <span class="text-red-500">*</span>
                        </label>
                        <InputNumber id="adelanto" v-model="formData.adelanto" placeholder="Ej: 1500.00"
                            :minFractionDigits="2" :maxFractionDigits="2" :min="0"
                            :class="['w-full', getFieldClass('adelanto')]" />
                        <small class="text-gray-500">Monto de adelanto otorgado.</small>
                        <small v-if="errors.adelanto" class="text-red-500">{{ errors.adelanto[0] }}</small>
                    </div>

                    <!-- Tasa (TEM) -->
                    <div class="flex flex-col gap-1">
                        <label for="tasa_tem" class="font-medium text-sm flex items-center gap-1">
                            <i class="pi pi-chart-line"></i> Tasa (TEM) % <span class="text-red-500">*</span>
                        </label>
                        <InputNumber id="tasa_tem" v-model="formData.tasa_tem" placeholder="Ej: 1.25"
                            :minFractionDigits="2" :maxFractionDigits="4" :max="100" :min="0"
                            :class="['w-full', getFieldClass('tasa_tem')]" />
                        <small class="text-gray-500">Tasa efectiva mensual aplicada.</small>
                        <small v-if="errors.tasa_tem" class="text-red-500">{{ errors.tasa_tem[0] }}</small>
                    </div>

                    <!-- Línea Adelanto -->
                    <div class="flex flex-col gap-1">
                        <label for="linea_adelanto" class="font-medium text-sm flex items-center gap-1">
                            <i class="pi pi-briefcase"></i> Línea Adelanto <span class="text-red-500">*</span>
                        </label>
                        <InputNumber id="linea_adelanto" v-model="formData.linea_adelanto" placeholder="Ej: 20000.00"
                            :minFractionDigits="2" :maxFractionDigits="2" :min="0"
                            :class="['w-full', getFieldClass('linea_adelanto')]" />
                        <small class="text-gray-500">Monto máximo aprobado para adelantos.</small>
                        <small v-if="errors.linea_adelanto" class="text-red-500">{{ errors.linea_adelanto[0] }}</small>
                    </div>

                    <!-- % Anticipo -->
                    <div class="flex flex-col gap-1">
                        <label for="porcentaje_anticipo" class="font-medium text-sm flex items-center gap-1">
                            <i class="pi pi-percentage"></i> % Adelanto <span class="text-red-500">*</span>
                        </label>
                        <InputNumber id="porcentaje_anticipo" v-model="formData.porcentaje_anticipo"
                            placeholder="Ej: 10.50" :minFractionDigits="2" :maxFractionDigits="4" :max="100" :min="0"
                            :class="['w-full', getFieldClass('porcentaje_anticipo')]" />
                        <small class="text-gray-500">Porcentaje del anticipo sobre el total.</small>
                        <small v-if="errors.porcentaje_anticipo" class="text-red-500">{{ errors.porcentaje_anticipo[0]
                            }}</small>
                    </div>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="flex flex-col gap-4">
                <h3 class="font-semibold text-lg border-b pb-1">
                    <i class="pi pi-phone mr-2"></i>
                    Información de Contacto
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Número Móvil -->
                    <div class="flex flex-col gap-2">
                        <label for="telefono" class="font-medium">
                            <i class="pi pi-mobile mr-1 text-sm"></i>
                            Número Móvil <span class="text-red-500">*</span>
                        </label>
                        <InputText id="telefono" v-model="formData.telefono" placeholder="Ej: 987654321" maxlength="9"
                            :class="['w-full', getFieldClass('telefono')]" @input="validatePhone" />
                        <small v-if="errors.telefono" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.telefono[0] }}
                        </small>
                        <small v-else-if="formData.telefono && formData.telefono.length === 9"
                            class="text-green-600 flex items-center">
                            <i class="pi pi-check-circle mr-1"></i>
                            Número válido
                        </small>
                    </div>

                    <!-- Sitio Web -->
                    <div class="flex flex-col gap-2">
                        <label for="pagina_web" class="font-medium">
                            <i class="pi pi-globe mr-1 text-sm"></i>
                            Sitio Web <span class="text-red-500">*</span>
                        </label>
                        <InputText id="pagina_web" v-model="formData.pagina_web" type="url"
                            placeholder="https://ejemplo.com" :class="['w-full', getFieldClass('pagina_web')]" />
                        <small v-if="errors.pagina_web" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.pagina_web[0] }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Documentos y Comentarios -->
            <div class="flex flex-col gap-4">
                <h3 class="font-semibold text-lg border-b pb-1">
                    <i class="pi pi-file mr-2"></i>
                    Documentos y Comentarios
                </h3>
                <div class="grid grid-cols-1 gap-4">

                    <!-- Carnet de Extranjería -->
                    <div class="flex flex-col gap-2">
                        <label for="carnet_documento" class="font-medium">
                            <i class="pi pi-id-card mr-1 text-sm"></i>
                            Carnet de Extranjería <span class="text-red-500">*</span>
                        </label>
                        <FileUpload ref="carnetUpload" name="carnet_documento" accept="image/*,application/pdf"
                            :maxFileSize="5000000" :class="['w-full', getFieldClass('carnet_documento')]" mode="basic"
                            chooseLabel="Seleccionar Carnet de Extranjería" @select="onCarnetSelect"
                            @remove="onCarnetRemove" :auto="false" :showUploadButton="false" :showCancelButton="false">

                            <template #empty>
                                <div class="flex items-center justify-center flex-col p-6">
                                    <i class="pi pi-id-card text-4xl mb-3 text-blue-500"></i>
                                    <p class="">Seleccione el carnet de extranjería</p>
                                    <p class="text-xs mt-1">PDF o imagen (máx. 5MB)</p>
                                </div>
                            </template>
                        </FileUpload>

                        <!-- Archivo del carnet seleccionado -->
                        <div v-if="carnetFile" class="mt-2">
                            <p class="text-sm text-gray-600 mb-2">Carnet seleccionado:</p>
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded border">
                                <div class="flex items-center">
                                    <i class="pi pi-id-card text-blue-500 mr-2"></i>
                                    <span class="text-sm">{{ carnetFile.name }}</span>
                                    <span class="ml-2 text-xs text-gray-500">({{ formatFileSize(carnetFile.size)
                                        }})</span>
                                </div>
                                <Button type="button" icon="pi pi-times" size="small" severity="danger" text
                                    @click="removeCarnet" title="Eliminar carnet" />
                            </div>
                        </div>

                        <small class="text-gray-500">
                            <i class="pi pi-info-circle mr-1"></i>
                            Suba una imagen clara o PDF del carnet de extranjería (ambos lados si es necesario).
                        </small>
                        <small v-if="errors.carnet_documento" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.carnet_documento[0] }}
                        </small>
                    </div>

                    <!-- Otros Documentos Adjuntos -->
                    <div class="flex flex-col gap-2">
                        <label for="archivos" class="font-medium">
                            <i class="pi pi-upload mr-1 text-sm"></i>
                            Otros Documentos Adjuntos
                        </label>
                        <FileUpload ref="fileUpload" name="archivos[]" :multiple="true"
                            accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx" :maxFileSize="5000000"
                            :class="['w-full', getFieldClass('archivos')]" mode="basic"
                            chooseLabel="Seleccionar Otros Archivos" @select="onFilesSelect" @remove="onFileRemove"
                            :auto="false" :showUploadButton="false" :showCancelButton="false">

                            <template #empty>
                                <div class="flex items-center justify-center flex-col p-6">
                                    <i class="pi pi-cloud-upload text-4xl mb-3"></i>
                                    <p class="">Seleccione archivos adicionales</p>
                                    <p class="text-xs mt-1">PDF, DOC, DOCX, XLS, XLSX, imágenes (máx. 5MB)</p>
                                </div>
                            </template>
                        </FileUpload>

                        <!-- Lista de archivos seleccionados -->
                        <div v-if="selectedFiles.length > 0" class="mt-2">
                            <p class="text-sm text-gray-600 mb-2">Otros archivos seleccionados:</p>
                            <div class="space-y-1">
                                <div v-for="(file, index) in selectedFiles" :key="index"
                                    class="flex items-center justify-between p-2 rounded text-sm">
                                    <div class="flex items-center">
                                        <i class="pi pi-file"></i>
                                        <span>{{ file.name }}</span>
                                        <span class="ml-2">({{ formatFileSize(file.size) }})</span>
                                    </div>
                                    <Button type="button" icon="pi pi-times" size="small" severity="danger" text
                                        @click="removeFile(index)" title="Eliminar archivo" />
                                </div>
                            </div>
                        </div>

                        <small class="text-gray-500">
                            <i class="pi pi-info-circle mr-1"></i>
                            Documentos adicionales como estados financieros, referencias, etc.
                        </small>
                        <small v-if="errors.archivos" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.archivos[0] }}
                        </small>
                    </div>

                    <!-- Observaciones y Comentarios -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Observaciones -->
                        <div class="flex flex-col gap-2">
                            <label for="observaciones" class="font-medium">
                                <i class="pi pi-eye mr-1 text-sm"></i>
                                Observaciones
                            </label>
                            <Textarea id="observacion" v-model="formData.observacion"
                                placeholder="Ingrese observaciones relevantes sobre el prospecto..." :rows="5"
                                :class="['w-full', getFieldClass('observacion')]" :maxlength="500" />
                            <small class="text-gray-500">
                                <i class="pi pi-info-circle mr-1"></i>
                                Información técnica o detalles importantes del proceso (máximo 500 caracteres).
                            </small>
                            <small v-if="formData.observacion" class="text-blue-600">
                                {{ formData.observacion.length }}/500 caracteres
                            </small>
                            <small v-if="errors.observacion" class="text-red-500 flex items-center">
                                <i class="pi pi-exclamation-triangle mr-1"></i>
                                {{ errors.observacion[0] }}
                            </small>
                        </div>

                        <!-- Comentarios -->
                        <div class="flex flex-col gap-2">
                            <label for="comentario" class="font-medium">
                                <i class="pi pi-comment mr-1 text-sm"></i>
                                Comentarios
                            </label>
                            <Textarea id="comentario" v-model="formData.comentario"
                                placeholder="Ingrese comentarios adicionales o notas generales..." :rows="5"
                                :class="['w-full', getFieldClass('comentario')]" :maxlength="800" />
                            <small class="text-gray-500">
                                <i class="pi pi-info-circle mr-1"></i>
                                Notas generales, impresiones o información adicional (máximo 800 caracteres).
                            </small>
                            <small v-if="formData.comentario" class="text-blue-600">
                                {{ formData.comentario.length }}/800 caracteres
                            </small>
                            <small v-if="errors.comentario" class="text-red-500 flex items-center">
                                <i class="pi pi-exclamation-triangle mr-1"></i>
                                {{ errors.comentario[0] }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-4 border-t">
                <Button type="submit" label="Guardar Prospecto" icon="pi pi-check" :loading="isSubmitting"
                    :disabled="isSubmitting || !isFormValid" severity="contrast" />
                <Button type="button" label="Limpiar Formulario" icon="pi pi-refresh" severity="secondary"
                    @click="clearForm" :disabled="isSubmitting" class="flex-1 sm:flex-initial" />
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useToast } from 'primevue/usetoast';
import axios from 'axios';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';
import Button from 'primevue/button';
import Message from 'primevue/message';
import Textarea from 'primevue/textarea';
import FileUpload from 'primevue/fileupload';

// Props
const props = defineProps({
    selectedProductoId: {
        type: Number,
        required: true
    },
    selectedTipoId: {
        type: Number,
        required: true
    },
    selectedMonedaId: {
        type: Number,
        required: true
    }
});

const toast = useToast();
const fileUpload = ref();
const carnetUpload = ref();

const formData = ref({
    documento: '',
    nombre: '',
    apellido_paterno: '',
    apellido_materno: '',
    nacionalidad: '',
    direccion: '',
    fecha_nacimiento: null,
    sexo: '',
    estado_civil: '',
    pagina_web: '',
    telefono: '',
    linea_cliente: null,
    porcentaje_anticipo: null,
    monto_comision: null,
    tasa_tem: null,
    adelanto: null,
    linea_adelanto: null,
    observacion: '',
    comentario: ''
});

const selectedFiles = ref([]);
const carnetFile = ref(null);
const isSubmitting = ref(false);
const errors = ref({});

// Computed properties para mejor UX
const maxBirthDate = computed(() => {
    const today = new Date();
    today.setFullYear(today.getFullYear() - 18); // Mínimo 18 años
    return today;
});

const yearRange = computed(() => {
    const currentYear = new Date().getFullYear();
    return `${currentYear - 100}:${currentYear - 18}`;
});

const age = computed(() => {
    if (!formData.value.fecha_nacimiento) return null;
    const today = new Date();
    const birthDate = new Date(formData.value.fecha_nacimiento);
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
});

const isFormValid = computed(() => {
    return formData.value.documento.trim() &&
        formData.value.nombre.trim() &&
        formData.value.apellido_paterno.trim() &&
        formData.value.apellido_materno.trim() &&
        formData.value.nacionalidad.trim() &&
        formData.value.direccion.trim() &&
        formData.value.fecha_nacimiento &&
        formData.value.sexo &&
        formData.value.estado_civil &&
        formData.value.telefono.length === 9 &&
        formData.value.pagina_web &&
        carnetFile.value && // Imagen del carnet obligatoria
        // Validación de campos financieros
        formData.value.linea_cliente !== null &&
        formData.value.porcentaje_anticipo !== null &&
        formData.value.monto_comision !== null &&
        formData.value.tasa_tem !== null &&
        formData.value.adelanto !== null &&
        formData.value.linea_adelanto !== null &&
        // Validación de que se haya seleccionado moneda
        props.selectedMonedaId !== null;
});
// Opciones para dropdowns
const sexoOptions = [
    { label: 'Masculino', value: 'M' },
    { label: 'Femenino', value: 'F' }
];

const estadoCivilOptions = [
    { label: 'Soltero/a', value: 'Soltero' },
    { label: 'Casado/a', value: 'Casado' },
    { label: 'Divorciado/a', value: 'Divorciado' },
    { label: 'Viudo/a', value: 'Viudo' },
    { label: 'Conviviente', value: 'Conviviente' }
];

// Funciones para manejo de archivos
const onFilesSelect = (event) => {
    const files = Array.from(event.files);
    selectedFiles.value = [...selectedFiles.value, ...files];
    console.log('Archivos adicionales seleccionados:', selectedFiles.value);
};

const onFileRemove = (event) => {
    const removedFile = event.file;
    selectedFiles.value = selectedFiles.value.filter(file => file !== removedFile);
    console.log('Archivo removido, archivos restantes:', selectedFiles.value);
};

const removeFile = (index) => {
    selectedFiles.value.splice(index, 1);
    // Actualizar el componente FileUpload
    if (fileUpload.value) {
        fileUpload.value.files = [...selectedFiles.value];
    }
};

// Funciones para manejo del carnet de extranjería
const onCarnetSelect = (event) => {
    if (event.files && event.files.length > 0) {
        carnetFile.value = event.files[0];
        console.log('Carnet de extranjería seleccionado:', carnetFile.value);

        // Limpiar error si había uno
        if (errors.value.carnet_documento) {
            delete errors.value.carnet_documento;
        }
    }
};

const onCarnetRemove = (event) => {
    carnetFile.value = null;
    console.log('Carnet de extranjería removido');
};

const removeCarnet = () => {
    carnetFile.value = null;
    // Limpiar el componente FileUpload del carnet
    if (carnetUpload.value) {
        carnetUpload.value.clear();
    }
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

// Métodos de validación en tiempo real
const validatePhone = () => {
    const phone = formData.value.telefono.replace(/\D/g, '');
    formData.value.telefono = phone;
    if (errors.value.telefono && phone.length === 9) {
        delete errors.value.telefono;
    }
};

const capitalizeInput = (field) => {
    const value = formData.value[field];
    formData.value[field] = value.replace(/\b\w/g, l => l.toUpperCase());
    if (errors.value[field] && value.trim()) {
        delete errors.value[field];
    }
};

const clearForm = () => {
    formData.value = {
        documento: '',
        nombre: '',
        apellido_paterno: '',
        apellido_materno: '',
        nacionalidad: '',
        direccion: '',
        fecha_nacimiento: null,
        sexo: '',
        estado_civil: '',
        pagina_web: '',
        telefono: '',
        linea_cliente: null,
        porcentaje_anticipo: null,
        monto_comision: null,
        tasa_tem: null,
        adelanto: null,
        linea_adelanto: null,
        observacion: '',
        comentario: ''
    };
    selectedFiles.value = [];
    carnetFile.value = null;
    errors.value = {};

    // Limpiar los componentes FileUpload
    if (fileUpload.value) {
        fileUpload.value.clear();
    }
    if (carnetUpload.value) {
        carnetUpload.value.clear();
    }

    toast.add({
        severity: 'info',
        summary: 'Formulario limpiado',
        detail: 'Todos los campos han sido restablecidos.',
        life: 3000
    });
};

const getFieldClass = (fieldName) => {
    return errors.value[fieldName] ? 'p-invalid' : '';
};

const formatDateForBackend = (date) => {
    if (!date) return null;
    if (date instanceof Date) {
        return date.toISOString().split('T')[0];
    }
    return date;
};

const prepareDataForBackend = () => {
    const formDataForBackend = new FormData();

    // ===== DATOS DE PERSONA (StorePersonaRequests) =====
    formDataForBackend.append('nombre', formData.value.nombre);
    formDataForBackend.append('apellido_paterno', formData.value.apellido_paterno);
    formDataForBackend.append('apellido_materno', formData.value.apellido_materno);
    formDataForBackend.append('direccion', formData.value.direccion);
    formDataForBackend.append('sexo', formData.value.sexo);
    formDataForBackend.append('estado_civil', formData.value.estado_civil);
    formDataForBackend.append('pagina_web', formData.value.pagina_web);
    formDataForBackend.append('telefono', formData.value.telefono);
    
    if (formData.value.fecha_nacimiento) {
        formDataForBackend.append('fecha_nacimiento', formatDateForBackend(formData.value.fecha_nacimiento));
    }

    // ===== DATOS DE CARNET DE EXTRANJERÍA (StoreCarnetExtrangeriaRequests) =====
    formDataForBackend.append('documento', formData.value.documento);
    formDataForBackend.append('nacionalidad', formData.value.nacionalidad);
    
    // El carnet documento va como 'imagenes' según tu controlador
    if (carnetFile.value) {
        formDataForBackend.append('imagenes', carnetFile.value);
    }

    // ===== DATOS DE CONDICIÓN FINANCIERA (StorePersonaCondicionFinancieraRequests) =====
    formDataForBackend.append('linea_cliente', formData.value.linea_cliente);
    formDataForBackend.append('porcentaje_anticipo', formData.value.porcentaje_anticipo);
    formDataForBackend.append('monto_comision', formData.value.monto_comision);
    formDataForBackend.append('tasa_tem', formData.value.tasa_tem);
    formDataForBackend.append('adelanto', formData.value.adelanto);
    formDataForBackend.append('linea_adelanto', formData.value.linea_adelanto);

    // ===== ARCHIVOS ADICIONALES (StorePersonaArchivo) =====
    selectedFiles.value.forEach((file, index) => {
        formDataForBackend.append(`archivos[${index}]`, file);
    });

    // ===== METADATOS REQUERIDOS =====
    formDataForBackend.append('tipo_documento_id', props.selectedTipoId);
    formDataForBackend.append('producto_id', props.selectedProductoId);
    formDataForBackend.append('moneda_id', props.selectedMonedaId);

    // ===== OBSERVACIONES Y COMENTARIOS =====
    if (formData.value.observacion) {
        formDataForBackend.append('observacion', formData.value.observacion);
    }
    if (formData.value.comentario) {
        formDataForBackend.append('comentario', formData.value.comentario);
    }

    console.log('🔍 CarnetExtranjeria - Datos preparados para envío:');
    console.log('📄 Datos del formulario:', {
        documento: formData.value.documento,
        nombre: formData.value.nombre,
        nacionalidad: formData.value.nacionalidad,
        imagenes: carnetFile.value?.name, // Cambiado de carnet_documento a imagenes
        archivos_adicionales: selectedFiles.value.map(f => ({ name: f.name, size: f.size }))
    });
    console.log('🔍 CarnetExtranjeria - Props recibidas:', {
        selectedProductoId: props.selectedProductoId,
        selectedTipoId: props.selectedTipoId,
        selectedMonedaId: props.selectedMonedaId
    });

    return formDataForBackend;
};

const submitForm = async () => {
    if (isSubmitting.value) return;

    errors.value = {};
    isSubmitting.value = true;

    try {
        const dataToSend = prepareDataForBackend();

        // Cambia esta ruta por la correcta según tu backend
        const response = await axios.post('/persona/carnet/extranjeria', dataToSend, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Accept': 'application/json'
            }
        });

        if (response.data.success) {
            toast.add({
                severity: 'success',
                summary: '¡Éxito!',
                detail: response.data.message || 'Prospecto con carnet de extranjería registrado correctamente.',
                life: 5000
            });

            clearForm();
        } else {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: response.data.message || 'Ocurrió un error inesperado.',
                life: 5000
            });
        }
    } catch (error) {
        console.error('Error al enviar formulario:', error);

        if (error.response?.status === 422) {
            errors.value = error.response.data.errors || {};
            toast.add({
                severity: 'warn',
                summary: 'Errores de validación',
                detail: 'Por favor, corrija los errores en el formulario.',
                life: 5000
            });
        } else if (error.response?.status === 500) {
            toast.add({
                severity: 'error',
                summary: 'Error del servidor',
                detail: error.response.data.message || 'Error interno del servidor.',
                life: 5000
            });
        } else {
            toast.add({
                severity: 'error',
                summary: 'Error de conexión',
                detail: 'No se pudo conectar con el servidor. Verifique su conexión.',
                life: 5000
            });
        }
    } finally {
        isSubmitting.value = false;
    }
};

// Limpiar errores cuando el usuario corrige los campos
watch(() => formData.value, (newVal) => {
    Object.keys(newVal).forEach(key => {
        if (newVal[key] && errors.value[key]) {
            delete errors.value[key];
        }
    });
}, { deep: true });
</script>

<style scoped>
:deep(.p-invalid) {
    border-color: #ef4444 !important;
}

:deep(.p-invalid:focus) {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 1px #ef4444 !important;
}

:deep(.p-button) {
    transition: all 0.3s ease;
}

:deep(.p-button:hover) {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

:deep(.p-inputtext:focus),
:deep(.p-inputnumber-input:focus),
:deep(.p-select:focus) {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
    border-color: #3b82f6;
}

:deep(.p-fileupload) {
    border: 2px dashed #d1d5db;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

:deep(.p-fileupload:hover) {
    border-color: #3b82f6;
    background-color: #f8fafc;
}

:deep(.p-fileupload-basic) {
    padding: 1rem;
}

:deep(.p-fileupload-basic .p-button) {
    width: 100%;
    justify-content: center;
}

/* Estilo especial para el carnet de extranjería */
.bg-blue-50 {
    background-color: #eff6ff;
}
</style>