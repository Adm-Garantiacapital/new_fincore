<template>
    <div class="mt-6">
        <!-- Indicador de progreso del formulario -->
        <div class="mb-4 p-3 bg-blue-50 border-l-4 border-blue-400 rounded">
            <div class="flex items-center">
                <i class="pi pi-info-circle text-blue-600 mr-2"></i>
                <span class="text-blue-800 font-medium">Complete el formulario para registrar una persona juridica</span>
            </div>
        </div>

        <form @submit.prevent="submitForm" class="flex flex-col gap-6">
            
            <!-- Datos de la Empresa -->
           <div class="flex flex-col gap-4">
                <h3 class="font-semibold text-lg border-b pb-1 text-gray-700">
                    <i class="pi pi-building mr-2"></i>
                     Registro de Prospecto - Persona Juridica
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- RUC -->
                    <div class="flex flex-col gap-2">
                        <label for="ruc" class="font-medium text-gray-700">
                            <i class="pi pi-id-card mr-1 text-sm"></i>
                            RUC <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <InputText 
                                id="ruc"
                                v-model="formData.ruc" 
                                placeholder="20123456789"
                                maxlength="11"
                                :class="['flex-1', getFieldClass('ruc')]"
                                @input="validateRuc" />
                            <Button 
                                type="button" 
                                icon="pi pi-search" 
                                severity="secondary"
                                @click="consultarRuc"
                                :loading="isConsultingRuc"
                                :disabled="formData.ruc.length !== 11"
                                size="small"
                                title="Consultar RUC" />
                        </div>
                        <small v-if="errors.ruc" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.ruc[0] }}
                        </small>
                        <small v-else-if="rucConsultado && !apiError" class="text-green-600 flex items-center">
                            <i class="pi pi-check-circle mr-1"></i>
                            Datos obtenidos de SUNAT
                        </small>
                        <small v-else-if="apiError" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ apiError }}
                        </small>
                    </div>

                    <!-- Razón Social -->
                    <div class="flex flex-col gap-2">
                        <label for="razon_social" class="font-medium text-gray-700">
                            <i class="pi pi-building mr-1 text-sm"></i>
                            Razón Social <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="razon_social"
                            v-model="formData.razon_social" 
                            placeholder="Ingrese razón social"
                            :class="['w-full', getFieldClass('razon_social')]"
                            @input="capitalizeInput('razon_social')"
                            :disabled="isFieldDisabled('razon_social')" />
                        <small v-if="errors.razon_social" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.razon_social[0] }}
                        </small>
                    </div>

                    <!-- Nombre Comercial -->
                    <div class="flex flex-col gap-2">
                        <label for="nombre_comercial" class="font-medium text-gray-700">
                            <i class="pi pi-tag mr-1 text-sm"></i>
                            Nombre Comercial <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="nombre_comercial"
                            v-model="formData.nombre_comercial" 
                            placeholder="Ingrese nombre comercial"
                            :class="['w-full', getFieldClass('nombre_comercial')]"
                            @input="capitalizeInput('nombre_comercial')"
                            :disabled="isFieldDisabled('nombre_comercial')" />
                        <small v-if="errors.nombre_comercial" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.nombre_comercial[0] }}
                        </small>
                    </div>

                    <!-- Inicio de Actividad -->
                    <div class="flex flex-col gap-2">
                        <label for="inicio_actividad" class="font-medium text-gray-700">
                            <i class="pi pi-calendar mr-1 text-sm"></i>
                            Inicio de Actividad <span class="text-red-500">*</span>
                        </label>
                        <DatePicker 
                            id="inicio_actividad"
                            v-model="formData.inicio_actividad" 
                            dateFormat="dd/mm/yy"
                            placeholder="Seleccione fecha"
                            :class="['w-full', getFieldClass('inicio_actividad')]" 
                            showIcon
                            :maxDate="new Date()"
                            :yearRange="'1900:2024'"
                            :disabled="isFieldDisabled('inicio_actividad')" />
                        <small v-if="errors.inicio_actividad" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.inicio_actividad[0] }}
                        </small>
                    </div>

                    <!-- Estado -->
                    <div class="flex flex-col gap-2">
                        <label for="estado" class="font-medium text-gray-700">
                            <i class="pi pi-flag mr-1 text-sm"></i>
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <Select 
                            id="estado" 
                            v-model="formData.estado" 
                            :options="estadoOptions" 
                            optionLabel="label"
                            optionValue="value" 
                            placeholder="Seleccione estado"
                            :class="['w-full', getFieldClass('estado')]"
                            :disabled="isFieldDisabled('estado')" />
                        <small v-if="errors.estado" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.estado[0] }}
                        </small>
                    </div>

                    <!-- Dirección -->
                    <div class="flex flex-col gap-2">
                        <label for="direccion" class="font-medium text-gray-700">
                            <i class="pi pi-map-marker mr-1 text-sm"></i>
                            Dirección <span class="text-red-500">*</span>
                            <span v-if="direccionFromApi" class="text-xs text-blue-600 ml-2">
                                (Datos de SUNAT - puede actualizar si es necesario)
                            </span>
                        </label>
                        <div class="flex gap-2" v-if="direccionFromApi && !editandoDireccion">
                            <InputText 
                                :value="formData.direccion"
                                readonly
                                :class="['flex-1', getFieldClass('direccion')]" 
                                placeholder="Dirección obtenida de SUNAT" />
                            <Button 
                                type="button" 
                                icon="pi pi-pencil" 
                                severity="secondary"
                                @click="editandoDireccion = true"
                                size="small"
                                title="Editar dirección" />
                        </div>
                        <InputText 
                            v-else
                            id="direccion"
                            v-model="formData.direccion" 
                            placeholder="Ingrese dirección completa"
                            :class="['w-full', getFieldClass('direccion')]"
                            :disabled="isFieldDisabled('direccion')" />
                        <small v-if="errors.direccion" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.direccion[0] }}
                        </small>
                    </div>

                    <!-- Actividad Económica -->
                    <div class="flex flex-col gap-2">
                        <label for="actividad_economica" class="font-medium text-gray-700">
                            <i class="pi pi-briefcase mr-1 text-sm"></i>
                            Actividad Económica <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="actividad_economica"
                            v-model="formData.actividad_economica" 
                            placeholder="Ingrese actividad económica principal"
                            :class="['w-full', getFieldClass('actividad_economica')]"
                            :disabled="isFieldDisabled('actividad_economica')" />
                        <small v-if="errors.actividad_economica" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.actividad_economica[0] }}
                        </small>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-4">
                <h3 class="font-semibold text-lg border-b pb-1 text-gray-700 flex items-center gap-2">
                    <i class="pi pi-dollar"></i>
                    Información de Parametros Sugeridos
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Monto Comisión -->
                    <div class="flex flex-col gap-1">
                        <label for="monto_comision" class="font-medium text-gray-700 text-sm flex items-center gap-1">
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
                        <label for="linea_cliente" class="font-medium text-gray-700 text-sm flex items-center gap-1">
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
                        <label for="adelanto" class="font-medium text-gray-700 text-sm flex items-center gap-1">
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
                        <label for="tasa_tem" class="font-medium text-gray-700 text-sm flex items-center gap-1">
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
                        <label for="linea_adelanto" class="font-medium text-gray-700 text-sm flex items-center gap-1">
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
                        <label for="porcentaje_anticipo" class="font-medium text-gray-700 text-sm flex items-center gap-1">
                            <i class="pi pi-percentage"></i> % Adelanto <span class="text-red-500">*</span>
                        </label>
                        <InputNumber id="porcentaje_anticipo" v-model="formData.porcentaje_anticipo" placeholder="Ej: 10.50"
                            :minFractionDigits="2" :maxFractionDigits="4" :max="100" :min="0"
                            :class="['w-full', getFieldClass('porcentaje_anticipo')]" />
                        <small class="text-gray-500">Porcentaje del anticipo sobre el total.</small>
                        <small v-if="errors.porcentaje_anticipo" class="text-red-500">{{ errors.porcentaje_anticipo[0] }}</small>
                    </div>
                </div>
            </div>
            <!-- Datos del Representante -->
            <div class="flex flex-col gap-4">
                <h3 class="font-semibold text-lg border-b pb-1 text-gray-700">
                    <i class="pi pi-user mr-2"></i>
                    Datos del Contacto
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- DNI del Representante -->
                    <div class="flex flex-col gap-2">
                        <label for="dni" class="font-medium text-gray-700">
                            <i class="pi pi-id-card mr-1 text-sm"></i>
                            DNI <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <InputText 
                                id="dni"
                                v-model="formData.dni" 
                                placeholder="12345678"
                                maxlength="8"
                                :class="['flex-1', getFieldClass('dni')]"
                                @input="validateDni"
                                @blur="consultarDniRepresentante"
                                :disabled="isConsultingDni" />
                            <Button 
                                type="button" 
                                icon="pi pi-search" 
                                severity="secondary"
                                @click="consultarDniRepresentante"
                                :loading="isConsultingDni"
                                :disabled="formData.dni.length !== 8"
                                size="small"
                                title="Consultar DNI" />
                        </div>
                        <small v-if="errors.dni" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.dni[0] }}
                        </small>
                        <small v-else-if="dniConsultado && !apiErrorDni" class="text-green-600 flex items-center">
                            <i class="pi pi-check-circle mr-1"></i>
                            Datos obtenidos de RENIEC
                        </small>
                        <small v-else-if="apiErrorDni" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ apiErrorDni }}
                        </small>
                    </div>

                    <!-- Nombres -->
                    <div class="flex flex-col gap-2">
                        <label for="nombre" class="font-medium text-gray-700">
                            <i class="pi pi-user mr-1 text-sm"></i>
                            Nombres <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="nombre"
                            v-model="formData.nombre" 
                            placeholder="Ingrese nombres"
                            :class="['w-full', getFieldClass('nombre')]"
                            @input="capitalizeInput('nombre')"
                            :disabled="isFieldDisabled('nombre')" />
                        <small v-if="errors.nombre" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.nombre[0] }}
                        </small>
                    </div>

                    <!-- Apellido Paterno -->
                    <div class="flex flex-col gap-2">
                        <label for="apellido_paterno" class="font-medium text-gray-700">
                            <i class="pi pi-user mr-1 text-sm"></i>
                            Apellido Paterno <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="apellido_paterno"
                            v-model="formData.apellido_paterno" 
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
                        <label for="apellido_materno" class="font-medium text-gray-700">
                            <i class="pi pi-user mr-1 text-sm"></i>
                            Apellido Materno <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="apellido_materno"
                            v-model="formData.apellido_materno" 
                            placeholder="Ingrese apellido materno"
                            :class="['w-full', getFieldClass('apellido_materno')]"
                            @input="capitalizeInput('apellido_materno')"
                            :disabled="isFieldDisabled('apellido_materno')" />
                        <small v-if="errors.apellido_materno" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.apellido_materno[0] }}
                        </small>
                    </div>

                    <!-- Cargo -->
                    <div class="flex flex-col gap-2">
                        <label for="cargo" class="font-medium text-gray-700">
                            <i class="pi pi-briefcase mr-1 text-sm"></i>
                            Cargo <span class="text-red-500">*</span>
                        </label>
                        <Select 
                            id="cargo" 
                            v-model="formData.cargo" 
                            :options="cargoOptions" 
                            optionLabel="label"
                            optionValue="value" 
                            placeholder="Seleccione cargo"
                            :class="['w-full', getFieldClass('cargo')]" />
                        <small v-if="errors.cargo" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.cargo[0] }}
                        </small>
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="flex flex-col gap-2">
                        <label for="correo" class="font-medium text-gray-700">
                            <i class="pi pi-envelope mr-1 text-sm"></i>
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="correo"
                            v-model="formData.correo" 
                            type="email"
                            placeholder="ejemplo@empresa.com"
                            :class="['w-full', getFieldClass('correo')]"
                            @input="validateEmail" />
                        <small v-if="errors.correo" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.correo[0] }}
                        </small>
                        <small v-else-if="isValidEmail && formData.correo" class="text-green-600 flex items-center">
                            <i class="pi pi-check-circle mr-1"></i>
                            Correo válido
                        </small>
                    </div>

                    <!-- Sitio Web -->
                    <div class="flex flex-col gap-2">
                        <label for="pagina_web" class="font-medium text-gray-700">
                            <i class="pi pi-globe mr-1 text-sm"></i>
                            Sitio Web <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="pagina_web"
                            v-model="formData.pagina_web" 
                            type="url"
                            placeholder="https://empresa.com"
                            :class="['w-full', getFieldClass('pagina_web')]" />
                        <small v-if="errors.pagina_web" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.pagina_web[0] }}
                        </small>
                    </div>

                    <!-- Número Móvil -->
                    <div class="flex flex-col gap-2">
                        <label for="telefono" class="font-medium text-gray-700">
                            <i class="pi pi-mobile mr-1 text-sm"></i>
                            Número Móvil <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="telefono"
                            v-model="formData.telefono" 
                            placeholder="987654321"
                            maxlength="9"
                            :class="['w-full', getFieldClass('telefono')]"
                            @input="validatePhone" />
                        <small v-if="errors.telefono" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.telefono[0] }}
                        </small>
                        <small v-else-if="formData.telefono && formData.telefono.length === 9" class="text-green-600 flex items-center">
                            <i class="pi pi-check-circle mr-1"></i>
                            Número válido
                        </small>
                    </div>

                    <!-- Observaciones -->
                    <div class="flex flex-col gap-2 lg:col-span-4">
                        <label for="observaciones" class="font-medium text-gray-700">
                            <i class="pi pi-comment mr-1 text-sm"></i>
                            Observaciones
                        </label>
                        <Textarea 
                            id="observaciones"
                            v-model="formData.observaciones" 
                            rows="3"
                            placeholder="Ingrese observaciones adicionales (opcional)"
                            :class="['w-full', getFieldClass('observaciones')]" />
                        <small v-if="errors.observaciones" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.observaciones[0] }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-4 border-t">
                <Button 
                    type="submit" 
                    label="Guardar Prospecto" 
                    icon="pi pi-check" 
                    :loading="isSubmitting"
                    :disabled="isSubmitting || !isFormValid"
                    severity="contrast" />
                <Button 
                    type="button" 
                    label="Limpiar Formulario" 
                    icon="pi pi-refresh" 
                    severity="secondary"
                    @click="clearForm" 
                    :disabled="isSubmitting" 
                    class="flex-1 sm:flex-initial" />
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useToast } from 'primevue/usetoast'
import axios from 'axios'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import DatePicker from 'primevue/datepicker'
import Textarea from 'primevue/textarea'
import Select from 'primevue/select'
import Button from 'primevue/button'

// Props
const props = defineProps({
    selectedProductoId: {
        type: Number,
        required: true
    },
    selectedTipoId: {
        type: Number,
        required: true
    }
})

// Form data
const formData = ref({
    ruc: '',
    razon_social: '',
    nombre_comercial: '',
    inicio_actividad: null,
    direccion: '',
    actividad_economica: '',
    estado: '',
    tasa_esperada: null,
    comisiones: null,
    dni: '',
    nombre: '',
    apellido_paterno: '',
    apellido_materno: '',
    cargo: '',
    correo: '',
    pagina_web: '',
    telefono: '',
    observaciones: '',
    producto_id: props.selectedProductoId,
    tipo_documento_id: props.selectedTipoId
})

// Estados de validación y control
const errors = ref({})
const isSubmitting = ref(false)
const isConsultingRuc = ref(false)
const isConsultingDni = ref(false)
const rucConsultado = ref(false)
const dniConsultado = ref(false)
const apiError = ref('')
const apiErrorDni = ref('')
const direccionFromApi = ref(false)
const editandoDireccion = ref(false)
const isValidEmail = ref(false)

// Opciones para selectores
const estadoOptions = ref([
    { label: 'ACTIVO', value: 'ACTIVO' },
    { label: 'SUSPENDIDO TEMPORALMENTE', value: 'SUSPENDIDO TEMPORALMENTE' },
    { label: 'BAJA PROVISIONAL', value: 'BAJA PROVISIONAL' },
    { label: 'BAJA DEFINITIVA', value: 'BAJA DEFINITIVA' },
    { label: 'BAJA DE OFICIO', value: 'BAJA DE OFICIO' }
])

const cargoOptions = ref([
    { label: 'Gerente General', value: 'GERENTE GENERAL' },
    { label: 'Representante Legal', value: 'REPRESENTANTE LEGAL' },
    { label: 'Administrador', value: 'ADMINISTRADOR' },
    { label: 'Apoderado', value: 'APODERADO' },
    { label: 'Director', value: 'DIRECTOR' },
    { label: 'Presidente', value: 'PRESIDENTE' }
])

// Toast
const toast = useToast()

// Computed properties
const isFormValid = computed(() => {
    const requiredFields = [
        'ruc', 'razon_social', 'nombre_comercial', 'inicio_actividad', 
        'direccion', 'actividad_economica', 'estado', 'tasa_esperada', 
        'comisiones', 'dni', 'nombre', 'apellido_paterno', 
        'apellido_materno', 'cargo', 'correo', 'pagina_web', 'telefono'
    ]
    
    return requiredFields.every(field => {
        const value = formData.value[field]
        return value !== '' && value !== null && value !== undefined
    })
})

// Métodos de validación
function validateRuc() {
    const ruc = formData.value.ruc.replace(/\D/g, '')
    formData.value.ruc = ruc
    
    if (ruc.length === 11) {
        rucConsultado.value = false
        apiError.value = ''
    }
}

function validateDni() {
    const dni = formData.value.dni.replace(/\D/g, '')
    formData.value.dni = dni
    
    if (dni.length === 8) {
        dniConsultado.value = false
        apiErrorDni.value = ''
    }
}

function validatePhone() {
    const phone = formData.value.telefono.replace(/\D/g, '')
    formData.value.telefono = phone
}

function validateEmail() {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    isValidEmail.value = emailRegex.test(formData.value.correo)
}

function capitalizeInput(field) {
    if (formData.value[field]) {
        const words = formData.value[field].toLowerCase().split(' ')
        const capitalizedWords = words.map(word => 
            word.charAt(0).toUpperCase() + word.slice(1)
        )
        formData.value[field] = capitalizedWords.join(' ')
    }
}

// Consultas de APIs
async function consultarRuc() {
    if (formData.value.ruc.length !== 11) return
    
    isConsultingRuc.value = true
    apiError.value = ''
    
    try {
        // Aquí iría la llamada real a la API de SUNAT
        // const response = await axios.get(`/api/sunat/ruc/${formData.value.ruc}`)
        
        // Simulación de respuesta
        await new Promise(resolve => setTimeout(resolve, 1500))
        
        // Datos simulados
        const datosSimulados = {
            razon_social: 'EMPRESA EJEMPLO S.A.C.',
            nombre_comercial: 'EMPRESA EJEMPLO',
            estado: 'ACTIVO',
            direccion: 'AV. EXAMPLE 123 - SAN ISIDRO - LIMA - LIMA',
            actividad_economica: 'ACTIVIDADES DE CONSULTORÍA DE GESTIÓN EMPRESARIAL'
        }
        
        formData.value.razon_social = datosSimulados.razon_social
        formData.value.nombre_comercial = datosSimulados.nombre_comercial
        formData.value.estado = datosSimulados.estado
        formData.value.direccion = datosSimulados.direccion
        formData.value.actividad_economica = datosSimulados.actividad_economica
        
        rucConsultado.value = true
        direccionFromApi.value = true
        editandoDireccion.value = false
        
        toast.add({
            severity: 'success',
            summary: 'RUC consultado',
            detail: 'Datos obtenidos correctamente de SUNAT',
            life: 3000
        })
        
    } catch (error) {
        apiError.value = 'No se pudo consultar el RUC. Intente nuevamente.'
        toast.add({
            severity: 'warn',
            summary: 'Error en consulta',
            detail: 'No se pudo obtener información del RUC',
            life: 4000
        })
    } finally {
        isConsultingRuc.value = false
    }
}

async function consultarDniRepresentante() {
    if (formData.value.dni.length !== 8) return
    
    isConsultingDni.value = true
    apiErrorDni.value = ''
    
    try {
        // Aquí iría la llamada real a la API de RENIEC
        // const response = await axios.get(`/api/reniec/dni/${formData.value.dni}`)
        
        // Simulación de respuesta
        await new Promise(resolve => setTimeout(resolve, 1000))
        
        // Datos simulados
        const datosSimulados = {
            nombres: 'JUAN CARLOS',
            apellido_paterno: 'PÉREZ',
            apellido_materno: 'GARCÍA'
        }
        
        formData.value.nombre = datosSimulados.nombres
        formData.value.apellido_paterno = datosSimulados.apellido_paterno
        formData.value.apellido_materno = datosSimulados.apellido_materno
        
        dniConsultado.value = true
        
        toast.add({
            severity: 'success',
            summary: 'DNI consultado',
            detail: 'Datos obtenidos correctamente de RENIEC',
            life: 3000
        })
        
    } catch (error) {
        apiErrorDni.value = 'No se pudo consultar el DNI. Intente nuevamente.'
        toast.add({
            severity: 'warn',
            summary: 'Error en consulta',
            detail: 'No se pudo obtener información del DNI',
            life: 4000
        })
    } finally {
        isConsultingDni.value = false
    }
}

// Función para determinar si un campo debe estar deshabilitado
function isFieldDisabled(field) {
    const fieldsFromRuc = ['razon_social', 'nombre_comercial', 'estado', 'actividad_economica']
    const fieldsFromDni = ['nombre', 'apellido_paterno', 'apellido_materno']
    const fieldsFromDireccion = ['direccion']
    
    if (fieldsFromRuc.includes(field) && rucConsultado.value) {
        return true
    }
    
    if (fieldsFromDni.includes(field) && dniConsultado.value) {
        return true
    }
    
    if (fieldsFromDireccion.includes(field) && direccionFromApi.value && !editandoDireccion.value) {
        return true
    }
    
    return false
}

// Clase para campos con error
function getFieldClass(field) {
    return errors.value[field] ? 'p-invalid' : ''
}

// Limpiar formulario
function clearForm() {
    // Resetear todos los campos excepto los IDs de producto y tipo
    Object.keys(formData.value).forEach(key => {
        if (key === 'producto_id' || key === 'tipo_documento_id') return
        
        if (key === 'inicio_actividad') {
            formData.value[key] = null
        } else if (typeof formData.value[key] === 'number') {
            formData.value[key] = null
        } else {
            formData.value[key] = ''
        }
    })
    
    // Resetear estados
    errors.value = {}
    rucConsultado.value = false
    dniConsultado.value = false
    direccionFromApi.value = false
    editandoDireccion.value = false
    apiError.value = ''
    apiErrorDni.value = ''
    isValidEmail.value = false
    
    toast.add({
        severity: 'info',
        summary: 'Formulario limpiado',
        detail: 'Todos los campos han sido restablecidos',
        life: 2000
    })
}

// Enviar formulario
async function submitForm() {
    try {
        isSubmitting.value = true
        errors.value = {}

        // Validaciones adicionales antes del envío
        if (!isFormValid.value) {
            toast.add({
                severity: 'error',
                summary: 'Formulario incompleto',
                detail: 'Complete todos los campos obligatorios',
                life: 4000
            })
            return
        }

        const { data } = await axios.post('/prospectos-ruc', formData.value)

        toast.add({
            severity: 'success',
            summary: 'Registro exitoso',
            detail: 'El prospecto empresarial fue registrado correctamente.',
            life: 4000
        })

        // Limpiar formulario después del registro exitoso
        clearForm()

    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors
            toast.add({
                severity: 'error',
                summary: 'Errores de validación',
                detail: 'Corrige los errores marcados antes de continuar.',
                life: 5000
            })
        } else {
            console.error('Error del servidor:', error)
            toast.add({
                severity: 'error',
                summary: 'Error del servidor',
                detail: 'Ocurrió un error inesperado. Intente nuevamente.',
                life: 4000
            })
        }
    } finally {
        isSubmitting.value = false
    }
}

// Watch para limpiar errores cuando se modifiquen los campos
watch(() => formData.value.ruc, () => {
    if (errors.value.ruc) {
        delete errors.value.ruc
    }
    if (formData.value.ruc.length !== 11) {
        rucConsultado.value = false
        apiError.value = ''
    }
})

watch(() => formData.value.dni, () => {
    if (errors.value.dni) {
        delete errors.value.dni
    }
    if (formData.value.dni.length !== 8) {
        dniConsultado.value = false
        apiErrorDni.value = ''
    }
})

watch(() => formData.value.correo, () => {
    if (errors.value.correo) {
        delete errors.value.correo
    }
    validateEmail()
})

// Limpiar errores cuando se modifiquen otros campos
const watchFields = ['razon_social', 'nombre_comercial', 'inicio_actividad', 'direccion', 
                    'actividad_economica', 'estado', 'tasa_esperada', 'comisiones', 
                    'nombre', 'apellido_paterno', 'apellido_materno', 'cargo', 
                    'pagina_web', 'telefono', 'observaciones']

watchFields.forEach(field => {
    watch(() => formData.value[field], () => {
        if (errors.value[field]) {
            delete errors.value[field]
        }
    })
})
</script>