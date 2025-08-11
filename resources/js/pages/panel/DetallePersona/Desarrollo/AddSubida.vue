<template>
    <div class="space-y-6">
        <!-- Header con información del prospecto -->
        <Message severity="secondary" class="p-4">
            <div class="flex items-center gap-8">
                <!-- Icono principal -->
                <i class="pi pi-user text-3xl"></i>
                
                <!-- Mostrar datos del cliente si existe -->
                <div v-if="detallePersona.data.cliente" class="flex gap-6 text-sm font-medium">
                    <div><span class="font-semibold mb-1">DNI:</span> {{ detallePersona.data.cliente.dni }}</div>
                    <div><span class="font-semibold mb-1">Nombre:</span> {{ detallePersona.data.cliente.nombre }}</div>
                    <div><span class="font-semibold mb-1">Apellidos:</span> {{ detallePersona.data.cliente.apellidos }}</div>
                    <div><span class="font-semibold mb-1">Producto:</span> {{ detallePersona.data.producto }}</div>
                    <Tag :severity="getEstadoSeverity(detallePersona.data.estado)"
                        :value="detallePersona.data.estado" />
                </div>
                
                <!-- Mostrar datos del proveedor si existe y cliente no -->
                <div v-else-if="detallePersona.data.proveedor" class="flex gap-6 text-sm font-medium">
                    <div><span class="font-semibold mb-1">RUC:</span> {{ detallePersona.data.proveedor.ruc }}</div>
                    <div><span class="font-semibold mb-1">Razón Social:</span> {{ detallePersona.data.proveedor.razon_social }}</div>
                    <div><span class="font-semibold mb-1">Nombre Comercial:</span> {{ detallePersona.data.proveedor.nombre_comercial }}</div>
                    <div><span class="font-semibold mb-1">Producto:</span> {{ detallePersona.data.producto }}</div>
                    <Tag :severity="getEstadoSeverity(detallePersona.data.estado)"
                        :value="detallePersona.data.estado" />
                </div>
            </div>
        </Message>
        <!-- Sección de subida de archivos -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white p-4">
                <div class="flex items-center gap-2">
                    <i class="pi pi-upload text-xl"></i>
                    <span class="font-semibold text-lg">Subir Reporte Tributario</span>
                </div>
            </div>

            <div>
                <!-- File Upload Component -->
                <FileUpload ref="fileUpload" name="archivo" accept=".pdf" :maxFileSize="10000000" :customUpload="true"
                    @uploader="onUpload" :disabled="isUploading" class="w-full">
                    <template #empty>
                        <div
                            class="text-center p-12 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-400 transition-colors">
                            <i class="pi pi-file-pdf text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-600 mb-2 font-medium">Arrastra y suelta tu archivo PDF aquí</p>
                            <p class="text-sm text-gray-500">o haz clic para seleccionar (máximo 10MB)</p>
                        </div>
                    </template>
                </FileUpload>
                <!-- Progress Bar -->
                <ProgressBar v-if="isUploading" mode="indeterminate" class="mt-4" />
            </div>
        </div>

        <!-- Datos procesados - Solo se muestran si hay datos -->
        <div v-if="pdfData" class="space-y-6">
            <!-- Datos Generales -->
            <ListDatosGenerales v-if="pdfData.datos_generales" :datos="pdfData.datos_generales" />

            <!-- Información Financiera -->
            <ListInformacionFinanciera v-if="pdfData.informacion_economico"
                :informacion="pdfData.informacion_economico" />

            <!-- Declaraciones Mensuales -->
            <ListDeclaracionMensuales
                v-if="pdfData.declaraciones_mensuales && Object.keys(pdfData.declaraciones_mensuales).length > 0"
                :declaraciones="pdfData.declaraciones_mensuales" />

            <!-- Información de Ventas -->
            <ListInformacionVentas v-if="pdfData.ventas && Object.keys(pdfData.ventas).length > 0"
                :ventas="pdfData.ventas" />

            <!-- Participación Patrimonial -->
            <ListInformacionPatrimonial v-if="pdfData.participacion_patrimonial"
                :participacion="pdfData.participacion_patrimonial" />

            <!-- Botones de acción -->
            <div class="flex justify-center gap-4 pt-8 pb-4">
                <Button label="Guardar Reporte Tributario" icon="pi pi-save" :loading="guardando" :disabled="!pdfData"
                    @click="guardarReporte"
                    class="px-8 py-3 bg-green-600 hover:bg-green-700 border-green-600 hover:border-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200" />
                <Button v-if="botonAceptante" label="Aceptante" icon="pi pi-arrow-right" @click="irAAceptante"
                    class="px-8 py-3 bg-blue-600 hover:bg-blue-700 border-blue-600 hover:border-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { useToast } from 'primevue/usetoast'

// Componentes PrimeVue
import FileUpload from 'primevue/fileupload'
import Button from 'primevue/button'
import Tag from 'primevue/tag'
import Message from 'primevue/message'
import ProgressBar from 'primevue/progressbar'

// Componentes personalizados
import ListDatosGenerales from './ListDatosGenerales.vue'
import ListInformacionFinanciera from './ListInformacionFinanciera.vue'
import ListDeclaracionMensuales from './ListDeclaracionMensuales.vue'
import ListInformacionVentas from './ListInformacionVentas.vue'
import ListInformacionPatrimonial from './ListInformacionPatrimonial.vue'

// Props
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

// Composables
const toast = useToast()

// Refs
const isUploading = ref(false)
const uploadedFileName = ref('')
const errorMessage = ref('')
const uploadSuccess = ref(false)
const pdfData = ref(null)
const guardando = ref(false)
const botonAceptante = ref(false)
const fileUpload = ref()

// Computed properties
const getEstadoSeverity = (estado: string) => {
    const severityMap = {
        'Registrado': 'success',
        'Inactivo': 'danger',
        'Pendiente': 'warning',
        'En Proceso': 'info'
    }
    return severityMap[estado] || 'info'
}

// Utility functions
const convertirFecha = (fecha: string): string | null => {
    const partes = fecha.split('/')
    if (partes.length !== 3) return null
    const [dia, mes, anio] = partes
    if (
        isNaN(Number(dia)) ||
        isNaN(Number(mes)) ||
        isNaN(Number(anio))
    ) return null
    return `${anio.padStart(4, '0')}-${mes.padStart(2, '0')}-${dia.padStart(2, '0')}`
}

const convertirNumero = (value: string): number => {
    if (!value) return 0
    const cleanValue = value.replace(/,/g, '')
    return Number(cleanValue)
}

const obtenerSuma12 = (value: any[]): number => {
    if (!Array.isArray(value)) return 0
    let suma = 0
    for (let i = 0; i < value.length; i++) {
        if (Array.isArray(value[i]) && value[i][1]) {
            const cleanValue = Number(value[i][1].replace(/,/g, ''))
            suma += cleanValue
        }
    }
    return suma
}

const obtenerPromedio12 = (value: any[]): number => {
    if (!Array.isArray(value) || value.length === 0) return 0
    return obtenerSuma12(value) / value.length
}

const obtenerSuma6 = (value: any[]): number => {
    if (!Array.isArray(value)) return 0
    const lastSix = value.slice(value.length - 6)
    return obtenerSuma12(lastSix)
}

const obtenerPromedio6 = (value: any[]): number => {
    if (!Array.isArray(value) || value.length === 0) return 0
    const lastSix = value.slice(value.length - 6)
    if (lastSix.length === 0) return 0
    return obtenerSuma12(lastSix) / lastSix.length
}

// Methods
const onUpload = async (event) => {
    const file = event.files[0]

    if (!file) return

    if (file.size > 10 * 1024 * 1024) {
        errorMessage.value = 'El archivo no debe exceder los 10MB'
        return
    }

    const formData = new FormData()
    formData.append('archivo', file)

    try {
        isUploading.value = true
        errorMessage.value = ''
        uploadSuccess.value = false

        const response = await axios.post('/procesar-pdf-sunat', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })

        pdfData.value = response.data
        uploadedFileName.value = file.name
        uploadSuccess.value = true

        toast.add({
            severity: 'success',
            summary: 'Éxito',
            detail: 'Archivo procesado correctamente',
            life: 3000
        })

        fileUpload.value.clear()

    } catch (error) {
        console.error('Error al procesar el archivo:', error)
        errorMessage.value = error.response?.data?.error || 'Error al procesar el archivo PDF'
        pdfData.value = null
        uploadSuccess.value = false

        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: errorMessage.value,
            life: 5000
        })
    } finally {
        isUploading.value = false
    }
}

const guardarReporte = async () => {
    if (!pdfData.value?.datos_generales) return

    try {
        guardando.value = true

        const response = await axios.post('/detalle/reporte/sunat', {
            persona_id: detallePersona.data.id,
            ano: '2025',
            nombre_comercial: pdfData.value?.datos_generales.NombreComercial,
            fecha_inscripcion: convertirFecha(pdfData.value?.datos_generales.FechaInscripcion),
            fecha_inicio_actividades: convertirFecha(pdfData.value?.datos_generales.FechaInicioActividades),
            estado_contribuyente: pdfData.value?.datos_generales.EstadoContribuyente,
            condicion_contribuyente: pdfData.value?.datos_generales.CondicionContribuyente,
            domicilio_fiscal: pdfData.value?.datos_generales.DomicilioFiscal,
            actividad_comercio_exterior: pdfData.value?.datos_generales.ActividadComercioExterior,
            actividad_economica: pdfData.value?.datos_generales.ActividadEconomica,
            ingresos_netos: convertirNumero(pdfData.value?.informacion_economico.IngresosNetosPeriodo[0]),
            otros_ingresos: convertirNumero(pdfData.value?.informacion_economico.OtrosIngresosDeclarados[0]),
            total_activos: convertirNumero(pdfData.value?.informacion_economico.TotalActivosNetos[0]),
            total_cuentas_por_pagar: convertirNumero(pdfData.value?.informacion_economico.TotalCuentasPorPagar[0]),
            total_pasivo: convertirNumero(pdfData.value?.informacion_economico.TotalPasivo[0]),
            total_patrimonio: convertirNumero(pdfData.value?.informacion_economico.TotalPatrimonio[0]),
            capital_social: convertirNumero(pdfData.value?.informacion_economico.CapitalSocial[0]),
            resultado_bruto: convertirNumero(pdfData.value?.informacion_economico.ResultadoBruto[0]),
            resultado_antes_imp: convertirNumero(pdfData.value?.informacion_economico.ResultadoAntesParticipacionesImpuestos[0]),
            importe_pagado: convertirNumero(pdfData.value?.informacion_economico.ImportePagado[0]),
            ingreso_12_meses: obtenerSuma12(pdfData.value?.declaraciones_mensuales.EjercicioAnterior),
            ingreso_6_meses: obtenerSuma6(pdfData.value?.declaraciones_mensuales.EjercicioAnterior),
            promedio_ingreso_12_meses: obtenerPromedio12(pdfData.value?.declaraciones_mensuales.EjercicioAnterior),
            promedio_ingreso_6_meses: obtenerPromedio6(pdfData.value?.declaraciones_mensuales.EjercicioAnterior),
        })

        if (response.status === 200 || response.status === 201) {
            toast.add({
                severity: 'success',
                summary: 'Guardado',
                detail: response.data.message || 'Reporte tributario guardado exitosamente',
                life: 3000
            })

            botonAceptante.value = true
        }

    } catch (error) {
        console.error('Error al guardar reporte:', error)
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Error al guardar el reporte',
            life: 5000
        })
    } finally {
        guardando.value = false
    }
}

const irAAceptante = () => {
    // Implementar navegación a aceptante
    console.log('Ir a aceptante')
}
</script>