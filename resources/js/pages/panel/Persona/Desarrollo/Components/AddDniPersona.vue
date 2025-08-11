<template>
    <div class="mt-6">
        <!-- Indicador de progreso del formulario -->
        <Message severity="contrast">Complete el formulario para registrar una persona natural</Message>
        <form @submit.prevent="submitForm" class="flex flex-col gap-6 py-5">
            <!-- Datos Personales -->
            <div class="flex flex-col gap-4">
                <h3 class="font-semibold text-lg border-b pb-1 ">
                    <i class="pi pi-user mr-2"></i>
                    Registro de Prospecto - Persona Natural
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                    <!-- DNI -->
                    <div class="flex flex-col gap-2">
                        <label for="dni" class="font-medium">
                            <i class="pi pi-id-card mr-1 text-sm"></i>
                            DNI <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <InputText id="dni" v-model="formData.dni" placeholder="Ingrese DNI (8 dígitos)"
                                maxlength="8" :class="['flex-1', getFieldClass('dni')]" @input="validateDni"
                                @blur="consultarDni" :disabled="isConsultingDni" />
                            <Button type="button" icon="pi pi-search" severity="secondary" @click="consultarDni"
                                :loading="isConsultingDni" :disabled="formData.dni.length !== 8" size="small"
                                title="Consultar DNI" />
                        </div>
                        <small v-if="errors.dni" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.dni[0] }}
                        </small>
                        <small v-else-if="dniConsultado && !apiError" class="text-green-600 flex items-center">
                            <i class="pi pi-check-circle mr-1"></i>
                            Datos obtenidos de RENIEC
                        </small>
                        <small v-else-if="apiError" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ apiError }}
                        </small>
                        <small v-else-if="formData.dni && formData.dni.length === 8"
                            class="text-orange-500 flex items-center">
                            <i class="pi pi-info-circle mr-1"></i>
                            Presione buscar o salga del campo para consultar
                        </small>
                    </div>

                    <!-- Nombres -->
                    <div class="flex flex-col gap-2">
                        <label for="nombre" class="font-medium ">
                            <i class="pi pi-user mr-1 text-sm"></i>
                            Nombres <span class="text-red-500">*</span>
                        </label>
                        <InputText id="nombre" v-model="formData.nombre" placeholder="Ingrese nombres"
                            :class="['w-full', getFieldClass('nombre')]" @input="capitalizeInput('nombre')"
                            :disabled="isFieldDisabled('nombre')" />
                        <small v-if="errors.nombre" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.nombre[0] }}
                        </small>
                    </div>

                    <!-- Apellido Paterno -->
                    <div class="flex flex-col gap-2">
                        <label for="apellido_paterno" class="font-medium ">
                            <i class="pi pi-user mr-1 text-sm"></i>
                            Apellido Paterno <span class="text-red-500">*</span>
                        </label>
                        <InputText id="apellido_paterno" v-model="formData.apellido_paterno"
                            placeholder="Ingrese apellido paterno"
                            :class="['w-full', getFieldClass('apellido_paterno')]"
                            @input="capitalizeInput('apellido_paterno')"
                            :disabled="isFieldDisabled('apellido_paterno')" />
                        <small v-if="errors.apellido_paterno" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.apellido_paterno[0] }}
                        </small>
                    </div>

                    <!-- Apellido Materno -->
                    <div class="flex flex-col gap-2">
                        <label for="apellido_materno" class="font-medium ">
                            <i class="pi pi-user mr-1 text-sm"></i>
                            Apellido Materno <span class="text-red-500">*</span>
                        </label>
                        <InputText id="apellido_materno" v-model="formData.apellido_materno"
                            placeholder="Ingrese apellido materno"
                            :class="['w-full', getFieldClass('apellido_materno')]"
                            @input="capitalizeInput('apellido_materno')"
                            :disabled="isFieldDisabled('apellido_materno')" />
                        <small v-if="errors.apellido_materno" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.apellido_materno[0] }}
                        </small>
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div class="flex flex-col gap-2">
                        <label for="fecha_nacimiento" class="font-medium ">
                            <i class="pi pi-calendar mr-1 text-sm"></i>
                            Fecha de Nacimiento <span class="text-red-500">*</span>
                        </label>
                        <DatePicker id="fecha_nacimiento" v-model="formData.fecha_nacimiento" dateFormat="dd/mm/yy"
                            placeholder="Seleccione fecha de nacimiento"
                            :class="['w-full', getFieldClass('fecha_nacimiento')]" showIcon :maxDate="maxBirthDate"
                            :yearRange="yearRange" :disabled="isFieldDisabled('fecha_nacimiento')" />
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
                        <label for="sexo" class="font-medium ">
                            <i class="pi pi-users mr-1 text-sm"></i>
                            Sexo <span class="text-red-500">*</span>
                        </label>
                        <Select id="sexo" v-model="formData.sexo" :options="sexoOptions" optionLabel="label"
                            optionValue="value" placeholder="Seleccione sexo" :class="['w-full', getFieldClass('sexo')]"
                            :disabled="isFieldDisabled('sexo')" />
                        <small v-if="errors.sexo" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.sexo[0] }}
                        </small>
                    </div>

                    <!-- Estado Civil -->
                    <div class="flex flex-col gap-2">
                        <label for="estado_civil" class="font-medium ">
                            <i class="pi pi-heart mr-1 text-sm"></i>
                            Estado Civil <span class="text-red-500">*</span>
                        </label>
                        <Select id="estado_civil" v-model="formData.estado_civil" :options="estadoCivilOptions"
                            optionLabel="label" optionValue="value" placeholder="Seleccione estado civil"
                            :class="['w-full', getFieldClass('estado_civil')]"
                            :disabled="isFieldDisabled('estado_civil')" />
                        <small v-if="errors.estado_civil" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.estado_civil[0] }}
                        </small>
                    </div>

                    <!-- Dirección -->
                    <div class="flex flex-col gap-2 md:col-span-2 lg:col-span-4">
                        <label for="direccion" class="font-medium ">
                            <i class="pi pi-map-marker mr-1 text-sm"></i>
                            Dirección <span class="text-red-500">*</span>
                            <span v-if="direccionFromApi" class="text-xs text-blue-600 ml-2">
                                (Datos de RENIEC - puede actualizar si es necesario)
                            </span>
                        </label>
                        <div class="flex gap-2" v-if="direccionFromApi && !editandoDireccion">
                            <InputText :value="formData.direccion" readonly
                                :class="['flex-1', getFieldClass('direccion')]"
                                placeholder="Dirección obtenida de RENIEC" />
                            <Button type="button" icon="pi pi-pencil" severity="secondary"
                                @click="editandoDireccion = true" size="small" title="Editar dirección" />
                        </div>
                        <InputText v-else id="direccion" v-model="formData.direccion"
                            placeholder="Ingrese dirección completa (Av./Jr./Calle, número, distrito)"
                            :class="['w-full', getFieldClass('direccion')]" :disabled="isFieldDisabled('direccion')" />
                        <small v-if="errors.direccion" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.direccion[0] }}
                        </small>
                    </div>
                </div>
            </div>
            <!-- Información Financiera -->
            <div class="flex flex-col gap-4">
                <h3 class="font-semibold text-lg border-b pb-1  flex items-center gap-2">
                    <i class="pi pi-dollar"></i>
                    Información de Parametros Sugeridos
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Monto Comisión -->
                    <div class="flex flex-col gap-1">
                        <label for="monto_comision" class="font-medium  text-sm flex items-center gap-1">
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
                        <label for="linea_cliente" class="font-medium  text-sm flex items-center gap-1">
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
                        <label for="adelanto" class="font-medium  text-sm flex items-center gap-1">
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
                        <label for="tasa_tem" class="font-medium  text-sm flex items-center gap-1">
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
                        <label for="linea_adelanto" class="font-medium  text-sm flex items-center gap-1">
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
                        <label for="porcentaje_anticipo" class="font-medium  text-sm flex items-center gap-1">
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
                <h3 class="font-semibold text-lg border-b pb-1 ">
                    <i class="pi pi-phone mr-2"></i>
                    Información de Contacto
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Número Móvil -->
                    <div class="flex flex-col gap-2">
                        <label for="telefono" class="font-medium ">
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
                        <label for="pagina_web" class="font-medium ">
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
                <h3 class="font-semibold text-lg border-b pb-1 ">
                    <i class="pi pi-file mr-2"></i>
                    Documentos y Comentarios
                </h3>
                <div class="grid grid-cols-1 gap-4">
                    
                    <!-- Subida de Archivos -->
                    <div class="flex flex-col gap-2">
                        <label for="archivos" class="font-medium">
                            <i class="pi pi-upload mr-1 text-sm"></i>
                            Documentos Adjuntos
                        </label>
                        <FileUpload 
                            ref="fileUpload"
                            name="archivos[]" 
                            :multiple="true" 
                            accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx" 
                            :maxFileSize="5000000"
                            :class="['w-full', getFieldClass('archivos')]"
                            mode="basic"
                            chooseLabel="Seleccionar Archivos"
                            uploadLabel="Subir"
                            cancelLabel="Cancelar"
                            @select="onFilesSelect"
                            @remove="onFileRemove"
                            :auto="false"
                            :showUploadButton="false"
                            :showCancelButton="false">
                            
                            <template #empty>
                                <div class="flex items-center justify-center flex-col p-6">
                                    <i class="pi pi-cloud-upload text-4xl mb-3"></i>
                                    <p class="">Seleccione archivos para subir</p>
                                    <p class="text-xs mt-1">PDF, DOC, DOCX, XLS, XLSX, imágenes (máx. 5MB)</p>
                                </div>
                            </template>
                        </FileUpload>
                        
                        <!-- Lista de archivos seleccionados -->
                        <div v-if="selectedFiles.length > 0" class="mt-2">
                            <p class="text-sm text-gray-600 mb-2">Archivos seleccionados:</p>
                            <div class="space-y-1">
                                <div v-for="(file, index) in selectedFiles" :key="index" 
                                     class="flex items-center justify-between p-2 rounded text-sm">
                                    <div class="flex items-center">
                                        <i class="pi pi-file"></i>
                                        <span>{{ file.name }}</span>
                                        <span class="ml-2">({{ formatFileSize(file.size) }})</span>
                                    </div>
                                    <Button 
                                        type="button" 
                                        icon="pi pi-times" 
                                        size="small" 
                                        severity="danger" 
                                        text 
                                        @click="removeFile(index)"
                                        title="Eliminar archivo" />
                                </div>
                            </div>
                        </div>
                        
                        <small class="text-gray-500">
                            <i class="pi pi-info-circle mr-1"></i>
                            Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX, imágenes. Tamaño máximo: 5MB por archivo.
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
                            <Textarea 
                                id="observacion" 
                                v-model="formData.observacion" 
                                placeholder="Ingrese observaciones relevantes sobre el prospecto..."
                                :rows="5" 
                                :class="['w-full', getFieldClass('observacion')]"
                                :maxlength="500" />
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
                            <Textarea 
                                id="comentario" 
                                v-model="formData.comentario" 
                                placeholder="Ingrese comentarios adicionales o notas generales..."
                                :rows="5" 
                                :class="['w-full', getFieldClass('comentario')]"
                                :maxlength="800" />
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
import { router } from '@inertiajs/vue3';
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

const formData = ref({
    dni: '',
    nombre: '',
    apellido_paterno: '',
    apellido_materno: '',
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
const isSubmitting = ref(false);
const isConsultingDni = ref(false);
const dniConsultado = ref(false);
const apiError = ref('');
const direccionFromApi = ref(false);
const editandoDireccion = ref(false);
const fieldsFromApi = ref({});
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
    return formData.value.dni.length === 8 &&
        formData.value.nombre.trim() &&
        formData.value.apellido_paterno.trim() &&
        formData.value.apellido_materno.trim() &&
        formData.value.direccion.trim() &&
        formData.value.fecha_nacimiento &&
        formData.value.sexo &&
        formData.value.estado_civil &&
        formData.value.telefono.length === 9 &&
        formData.value.pagina_web &&
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

// Función para determinar si un campo debe estar deshabilitado
const isFieldDisabled = (fieldName) => {
    // Si hubo error en la API, todos los campos de datos personales se habilitan
    if (apiError.value) {
        return false;
    }

    // Si no se ha consultado el DNI, solo el DNI está habilitado
    if (!dniConsultado.value) {
        return fieldName !== 'dni';
    }

    // Si el campo viene de la API y tiene valor, está deshabilitado
    if (fieldsFromApi.value[fieldName] && formData.value[fieldName]) {
        return true;
    }

    // Los demás campos están habilitados
    return false;
};

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
    console.log('Archivos seleccionados:', selectedFiles.value);
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

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

// Función para consultar DNI
const consultarDni = async () => {
    if (formData.value.dni.length !== 8 || isConsultingDni.value) return;

    isConsultingDni.value = true;
    apiError.value = '';
    dniConsultado.value = false;
    fieldsFromApi.value = {};
    direccionFromApi.value = false;
    editandoDireccion.value = false;

    try {
        const response = await axios.get(`/api/consultas/consultar-dni/${formData.value.dni}`);

        if (response.data.success && response.data.data) {
            const data = response.data.data;
            dniConsultado.value = true;

            // Mapear datos de la API
            if (data.nombres && data.nombres.trim()) {
                formData.value.nombre = data.nombres.trim();
                fieldsFromApi.value.nombre = true;
            }

            if (data.apellido_paterno && data.apellido_paterno.trim()) {
                formData.value.apellido_paterno = data.apellido_paterno.trim();
                fieldsFromApi.value.apellido_paterno = true;
            }

            if (data.apellido_materno && data.apellido_materno.trim()) {
                formData.value.apellido_materno = data.apellido_materno.trim();
                fieldsFromApi.value.apellido_materno = true;
            }

            // Mapear fecha de nacimiento
            if (data.fecha_nacimiento && data.fecha_nacimiento.trim()) {
                try {
                    // Formato esperado: "11/03/2003"
                    const [dia, mes, año] = data.fecha_nacimiento.split('/');
                    const fechaNacimiento = new Date(parseInt(año), parseInt(mes) - 1, parseInt(dia));
                    if (!isNaN(fechaNacimiento.getTime())) {
                        formData.value.fecha_nacimiento = fechaNacimiento;
                        fieldsFromApi.value.fecha_nacimiento = true;
                    }
                } catch (error) {
                    console.error('Error al procesar fecha de nacimiento:', error);
                }
            }

            // Mapear sexo
            if (data.sexo && data.sexo.trim()) {
                const sexo = data.sexo.trim().toLowerCase();
                if (sexo.includes('varon') || sexo.includes('masculino')) {
                    formData.value.sexo = 'M';
                    fieldsFromApi.value.sexo = true;
                } else if (sexo.includes('mujer') || sexo.includes('femenino')) {
                    formData.value.sexo = 'F';
                    fieldsFromApi.value.sexo = true;
                }
            }

            // Mapear estado civil si viene con datos
            if (data.estado_civil && data.estado_civil.trim()) {
                const estadoCivil = data.estado_civil.trim().toLowerCase();
                let estadoMapeado = '';

                if (estadoCivil.includes('soltero')) {
                    estadoMapeado = 'Soltero';
                } else if (estadoCivil.includes('casado')) {
                    estadoMapeado = 'Casado';
                } else if (estadoCivil.includes('divorciado')) {
                    estadoMapeado = 'Divorciado';
                } else if (estadoCivil.includes('viudo')) {
                    estadoMapeado = 'Viudo';
                } else if (estadoCivil.includes('conviviente')) {
                    estadoMapeado = 'Conviviente';
                }

                if (estadoMapeado) {
                    formData.value.estado_civil = estadoMapeado;
                    fieldsFromApi.value.estado_civil = true;
                }
            }

            // Mapear dirección
            if (data.direccion && data.direccion.trim()) {
                formData.value.direccion = data.direccion.trim();
                direccionFromApi.value = true;
                fieldsFromApi.value.direccion = true;
            } else if (data.direccion_completa && data.direccion_completa.trim()) {
                formData.value.direccion = data.direccion_completa.trim();
                direccionFromApi.value = true;
                fieldsFromApi.value.direccion = true;
            }

            toast.add({
                severity: 'success',
                summary: 'DNI consultado',
                detail: 'Datos obtenidos correctamente de RENIEC.',
                life: 3000
            });

        } else {
            apiError.value = response.data.message || 'No se encontraron datos para este DNI';
            toast.add({
                severity: 'warn',
                summary: 'DNI no encontrado',
                detail: apiError.value,
                life: 4000
            });
        }

    } catch (error) {
        console.error('Error al consultar DNI:', error);
        apiError.value = 'Error al consultar el DNI. Complete los datos manualmente.';

        toast.add({
            severity: 'error',
            summary: 'Error de consulta',
            detail: 'No se pudo consultar el DNI. Puede completar los datos manualmente.',
            life: 5000
        });
    } finally {
        isConsultingDni.value = false;
    }
};

// Métodos de validación en tiempo real
const validateDni = () => {
    const dni = formData.value.dni.replace(/\D/g, '');
    formData.value.dni = dni;

    // Reset cuando cambia el DNI
    if (dniConsultado.value) {
        dniConsultado.value = false;
        apiError.value = '';
        fieldsFromApi.value = {};
        direccionFromApi.value = false;
        editandoDireccion.value = false;
    }

    if (errors.value.dni && dni.length === 8) {
        delete errors.value.dni;
    }
};

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
        dni: '',
        nombre: '',
        apellido_paterno: '',
        apellido_materno: '',
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
    errors.value = {};
    dniConsultado.value = false;
    apiError.value = '';
    fieldsFromApi.value = {};
    direccionFromApi.value = false;
    editandoDireccion.value = false;

    // Limpiar el componente FileUpload
    if (fileUpload.value) {
        fileUpload.value.clear();
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
    
    // Agregar datos del formulario
    formDataForBackend.append('tipo_documento_id', props.selectedTipoId);
    formDataForBackend.append('producto_id', props.selectedProductoId);
    formDataForBackend.append('moneda_id', props.selectedMonedaId);
    formDataForBackend.append('dni', formData.value.dni);
    formDataForBackend.append('nombre', formData.value.nombre);
    formDataForBackend.append('apellido_paterno', formData.value.apellido_paterno);
    formDataForBackend.append('apellido_materno', formData.value.apellido_materno);
    formDataForBackend.append('direccion', formData.value.direccion);
    formDataForBackend.append('sexo', formData.value.sexo);
    formDataForBackend.append('estado_civil', formData.value.estado_civil);
    formDataForBackend.append('pagina_web', formData.value.pagina_web);
    formDataForBackend.append('telefono', formData.value.telefono);
    formDataForBackend.append('linea_cliente', formData.value.linea_cliente);
    formDataForBackend.append('porcentaje_anticipo', formData.value.porcentaje_anticipo);
    formDataForBackend.append('monto_comision', formData.value.monto_comision);
    formDataForBackend.append('tasa_tem', formData.value.tasa_tem);
    formDataForBackend.append('adelanto', formData.value.adelanto);
    formDataForBackend.append('linea_adelanto', formData.value.linea_adelanto);

    // Agregar fecha de nacimiento
    if (formData.value.fecha_nacimiento) {
        formDataForBackend.append('fecha_nacimiento', formatDateForBackend(formData.value.fecha_nacimiento));
    }

    // Agregar observaciones y comentarios
    if (formData.value.observacion) {
        formDataForBackend.append('observacion', formData.value.observacion);
    }
    
    if (formData.value.comentario) {
        formDataForBackend.append('comentario', formData.value.comentario);
    }

    // Agregar archivos
    selectedFiles.value.forEach((file, index) => {
        formDataForBackend.append(`archivos[${index}]`, file);
    });

    console.log('🔍 AddDniPersona - Datos preparados para envío:');
    console.log('📄 Datos del formulario:', {
        dni: formData.value.dni,
        nombre: formData.value.nombre,
        observacion: formData.value.observacion,
        comentario: formData.value.comentario,
        archivos: selectedFiles.value.map(f => ({ name: f.name, size: f.size }))
    });
    console.log('🔍 AddDniPersona - Props recibidas:', {
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

        const response = await axios.post('/persona', dataToSend, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Accept': 'application/json'
            }
        });

        if (response.data.success) {
            const newId = response.data.id; // El ID real
            toast.add({
                severity: 'success',
                summary: '¡Éxito!',
                detail: response.data.message || 'Prospecto registrado correctamente.',
                life: 5000
            });

            clearForm();

            router.visit(`/panel/detalle/${newId}`); // ⬅️ REDIRIGIR
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
</style>