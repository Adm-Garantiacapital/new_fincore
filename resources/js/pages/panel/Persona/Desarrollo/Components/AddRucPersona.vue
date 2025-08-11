<template>
    <div class="mt-6">
        <!-- Indicador de progreso del formulario -->
        <Message severity="contrast">Complete el formulario para registrar una persona juridica</Message>
        <form @submit.prevent="submitForm" class="flex flex-col gap-6">
            
            <!-- Datos de la Empresa -->
           <div class="flex flex-col gap-4 py-5">
                <h3 class="font-semibold text-lg border-b pb-1">
                    <i class="pi pi-building mr-2"></i>
                     Registro de Prospecto - Persona Juridica
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- RUC -->
                    <div class="flex flex-col gap-2">
                        <label for="ruc" class="font-medium">
                            <i class="pi pi-id-card mr-1 text-sm"></i>
                            RUC <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <InputText 
                                id="ruc"
                                v-model="formData.ruc" 
                                placeholder="Ingrese RUC de 11 dígitos"
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
                        <small v-else-if="formData.ruc.length > 0 && formData.ruc.length < 11 && !isConsultingRuc" class="text-blue-600 flex items-center">
                            <i class="pi pi-info-circle mr-1"></i>
                            Complete 11 dígitos y presione buscar para consultar
                        </small>
                        <small v-else-if="formData.ruc.length === 11 && !rucConsultado && !isConsultingRuc && !apiError" class="text-orange-600 flex items-center">
                            <i class="pi pi-exclamation-circle mr-1"></i>
                            Presione el botón buscar o salga del campo para consultar
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
                        <label for="razon_social" class="font-medium ">
                            <i class="pi pi-building mr-1 text-sm"></i>
                            Razón Social <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="razon_social"
                            v-model="formData.razon_social" 
                            placeholder="Consulte el RUC para llenar automáticamente"
                            :class="['w-full', getFieldClass('razon_social')]"
                            @input="capitalizeInput('razon_social')"
                            :disabled="isFieldDisabled('razon_social')" />
                        <small v-if="errors.razon_social" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.razon_social[0] }}
                        </small>
                        <small v-else-if="!rucConsultado && !rucDataFailed && formData.ruc.length < 11" class="text-blue-600 flex items-center">
                            <i class="pi pi-info-circle mr-1"></i>
                            Consulte el RUC para llenar automáticamente
                        </small>
                        <small v-else-if="rucDataFailed" class="text-orange-600 flex items-center">
                            <i class="pi pi-exclamation-circle mr-1"></i>
                            RUC no encontrado. Complete manualmente
                        </small>
                    </div>

                    <!-- Nombre Comercial -->
                    <div class="flex flex-col gap-2">
                        <label for="nombre_comercial" class="font-medium ">
                            <i class="pi pi-tag mr-1 text-sm"></i>
                            Nombre Comercial <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="nombre_comercial"
                            v-model="formData.nombre_comercial" 
                            placeholder="Consulte el RUC para llenar automáticamente"
                            :class="['w-full', getFieldClass('nombre_comercial')]"
                            @input="capitalizeInput('nombre_comercial')"
                            :disabled="isFieldDisabled('nombre_comercial')" />
                        <small v-if="errors.nombre_comercial" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.nombre_comercial[0] }}
                        </small>
                        <small v-else-if="!rucConsultado && !rucDataFailed && formData.ruc.length < 11" class="text-blue-600 flex items-center">
                            <i class="pi pi-info-circle mr-1"></i>
                            Consulte el RUC para llenar automáticamente
                        </small>
                        <small v-else-if="rucDataFailed" class="text-orange-600 flex items-center">
                            <i class="pi pi-exclamation-circle mr-1"></i>
                            RUC no encontrado. Complete manualmente
                        </small>
                    </div>

                    <!-- Inicio de Actividad -->
                    <div class="flex flex-col gap-2">
                        <label for="inicio_actividad" class="font-medium ">
                            <i class="pi pi-calendar mr-1 text-sm"></i>
                            Inicio de Actividad <span class="text-red-500">*</span>
                        </label>
                        <DatePicker 
                            id="inicio_actividad"
                            v-model="formData.inicio_actividad" 
                            dateFormat="dd/mm/yy"
                            placeholder="Seleccione fecha de inicio de actividades"
                            :class="['w-full', getFieldClass('inicio_actividad')]" 
                            showIcon
                            :maxDate="new Date()"
                            :yearRange="'1900:2024'"
                            :disabled="false" />
                        <small v-if="errors.inicio_actividad" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.inicio_actividad[0] }}
                        </small>
                        <small v-else class="text-blue-600 flex items-center">
                            <i class="pi pi-info-circle mr-1"></i>
                            Fecha en que la empresa inició operaciones
                        </small>
                    </div>

                    <!-- Estado -->
                    <div class="flex flex-col gap-2">
                        <label for="estado" class="font-medium ">
                            <i class="pi pi-flag mr-1 text-sm"></i>
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <Select 
                            id="estado" 
                            v-model="formData.estado" 
                            :options="estadoOptions" 
                            optionLabel="label"
                            optionValue="value" 
                            placeholder="Consulte el RUC primero"
                            :class="['w-full', getFieldClass('estado')]"
                            :disabled="isFieldDisabled('estado')" />
                        <small v-if="errors.estado" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.estado[0] }}
                        </small>
                        <small v-else-if="!rucConsultado && !rucDataFailed && formData.ruc.length < 11" class="text-blue-600 flex items-center">
                            <i class="pi pi-info-circle mr-1"></i>
                            Consulte el RUC para obtener el estado
                        </small>
                        <small v-else-if="rucDataFailed" class="text-orange-600 flex items-center">
                            <i class="pi pi-exclamation-circle mr-1"></i>
                            Seleccione manualmente el estado
                        </small>
                    </div>

                    <!-- Dirección -->
                    <div class="flex flex-col gap-2">
                        <label for="direccion" class="font-medium ">
                            <i class="pi pi-map-marker mr-1 text-sm"></i>
                            Dirección <span class="text-red-500">*</span>
                            <span v-if="direccionFromApi && rucConsultado" class="text-xs text-blue-600 ml-2">
                                (Datos de SUNAT - puede editar si es necesario)
                            </span>
                        </label>
                        <div class="flex gap-2" v-if="direccionFromApi && !editandoDireccion && rucConsultado">
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
                            placeholder="Consulte el RUC o complete manualmente"
                            :class="['w-full', getFieldClass('direccion')]"
                            :disabled="isFieldDisabled('direccion')" />
                        <small v-if="errors.direccion" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.direccion[0] }}
                        </small>
                        <small v-else-if="!rucConsultado && !rucDataFailed && formData.ruc.length < 11" class="text-blue-600 flex items-center">
                            <i class="pi pi-info-circle mr-1"></i>
                            Consulte el RUC para obtener la dirección automáticamente
                        </small>
                        <small v-else-if="rucDataFailed" class="text-orange-600 flex items-center">
                            <i class="pi pi-exclamation-circle mr-1"></i>
                            RUC no encontrado. Complete la dirección manualmente
                        </small>
                        <small v-else-if="formData.ruc.length === 11 && !rucConsultado" class="text-orange-600 flex items-center">
                            <i class="pi pi-exclamation-circle mr-1"></i>
                            Consulte el RUC primero para obtener la dirección
                        </small>
                    </div>

                    <!-- Actividad Económica -->
                    <div class="flex flex-col gap-2">
                        <label for="actividad_economica" class="font-medium ">
                            <i class="pi pi-briefcase mr-1 text-sm"></i>
                            Actividad Económica <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="actividad_economica"
                            v-model="formData.actividad_economica" 
                            placeholder="Ingrese actividad económica principal"
                            :class="['w-full', getFieldClass('actividad_economica')]"
                            :disabled="false" />
                        <small v-if="errors.actividad_economica" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.actividad_economica[0] }}
                        </small>
                        <small v-else class="text-blue-600 flex items-center">
                            <i class="pi pi-info-circle mr-1"></i>
                            Describe la actividad principal de la empresa
                        </small>
                    </div>
                    <div class="flex flex-col gap-2 lg:col-span-2">
                        <label for="comentario" class="font-medium ">
                            <i class="pi pi-comment mr-1 text-sm"></i>
                            Comentario
                        </label>
                        <Textarea 
                            id="comentario"
                            v-model="formData.comentario" 
                            rows="3"
                            placeholder="Ingrese comentarios adicionales (opcional)"
                            :class="['w-full', getFieldClass('comentario')]" />
                        <small v-if="errors.comentario" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.comentario[0] }}
                        </small>
                        <small v-else class="text-blue-600 flex items-center">
                            <i class="pi pi-info-circle mr-1"></i>
                            Información adicional sobre el prospecto
                        </small>
                    </div>

                    <!-- Subida de Archivos -->
                    <div class="flex flex-col gap-2 lg:col-span-2">
                        <label for="archivos" class="font-medium">
                            <i class="pi pi-upload mr-1 text-sm"></i>
                            Documentos de Soporte
                        </label>
                        <FileUpload 
                            id="archivos"
                            ref="fileUpload"
                            mode="basic" 
                            :multiple="true"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx,.xls"
                            :maxFileSize="5000000"
                            :fileLimit="5"
                            chooseLabel="Seleccionar Archivos"
                            uploadLabel="Subir"
                            cancelLabel="Cancelar"
                            :customUpload="true"
                            @uploader="onFileUpload"
                            @select="onFileSelect"
                            @remove="onFileRemove"
                            :class="['w-full', getFieldClass('archivos')]"
                            :disabled="isSubmitting">
                            <template #empty>
                                <div class="flex flex-col items-center justify-center py-4">
                                    <i class="pi pi-cloud-upload text-2xl text-gray-400"></i>
                                    <p class="text-sm text-gray-500 mt-2">
                                        Arrastre archivos aquí o haga clic para seleccionar
                                    </p>
                                </div>
                            </template>
                        </FileUpload>
                        
                        <!-- Lista de archivos seleccionados -->
                        <div v-if="selectedFiles.length > 0" class="mt-2">
                            <p class="text-sm font-medium mb-2">Archivos seleccionados:</p>
                            <div class="flex flex-col gap-1">
                                <div v-for="(file, index) in selectedFiles" :key="index" 
                                     class="flex items-center justify-between p-2 bg-gray-50 rounded border">
                                    <div class="flex items-center gap-2">
                                        <i :class="getFileIcon(file.name)" class="text-blue-600"></i>
                                        <span class="text-sm">{{ file.name }}</span>
                                        <span class="text-xs text-gray-500">({{ formatFileSize(file.size) }})</span>
                                    </div>
                                    <Button 
                                        type="button"
                                        icon="pi pi-times" 
                                        severity="secondary"
                                        size="small"
                                        class="p-button-text p-button-sm"
                                        @click="removeFile(index)"
                                        :disabled="isSubmitting" />
                                </div>
                            </div>
                        </div>

                        <small v-if="errors.archivos" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.archivos[0] }}
                        </small>
                        <small v-else class="text-blue-600 flex items-center">
                            <i class="pi pi-info-circle mr-1"></i>
                            PDF, DOC, DOCX, JPG, PNG, XLSX (máx. 5MB por archivo, 5 archivos)
                        </small>
                    </div>
                </div>
            </div>
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
                <h3 class="font-semibold text-lg border-b pb-1 ">
                    <i class="pi pi-user mr-2"></i>
                    Datos del Contacto
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- DNI del Representante -->
                    <div class="flex flex-col gap-2">
                        <label for="dni" class="font-medium ">
                            <i class="pi pi-id-card mr-1 text-sm"></i>
                            DNI <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <InputText 
                                id="dni"
                                v-model="formData.dni" 
                                placeholder="Ingrese DNI de 8 dígitos"
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
                        <small v-else-if="formData.dni.length > 0 && formData.dni.length < 8 && !isConsultingDni" class="text-blue-600 flex items-center">
                            <i class="pi pi-info-circle mr-1"></i>
                            Complete 8 dígitos y presione buscar para consultar
                        </small>
                        <small v-else-if="formData.dni.length === 8 && !dniConsultado && !isConsultingDni && !apiErrorDni" class="text-orange-600 flex items-center">
                            <i class="pi pi-exclamation-circle mr-1"></i>
                            Presione el botón buscar o salga del campo para consultar
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
                        <label for="nombre" class="font-medium ">
                            <i class="pi pi-user mr-1 text-sm"></i>
                            Nombres <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="nombre"
                            v-model="formData.nombre"
                            placeholder="Consulte el DNI para llenar automáticamente"
                            :class="['w-full', getFieldClass('nombre')]"
                            @input="capitalizeInput('nombre')"
                            :disabled="isFieldDisabled('nombre')" />
                        <small v-if="errors.nombre" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.nombre[0] }}
                        </small>
                        <small v-else-if="!dniConsultado && !dniDataFailed && formData.dni.length < 8" class="text-blue-600 flex items-center">
                            <i class="pi pi-info-circle mr-1"></i>
                            Consulte el DNI para llenar automáticamente
                        </small>
                        <small v-else-if="dniDataFailed" class="text-orange-600 flex items-center">
                            <i class="pi pi-exclamation-circle mr-1"></i>
                            DNI no encontrado. Complete manualmente
                        </small>
                    </div>

                    <!-- Apellido Paterno -->
                    <div class="flex flex-col gap-2">
                        <label for="apellido_paterno" class="font-medium ">
                            <i class="pi pi-user mr-1 text-sm"></i>
                            Apellido Paterno <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="apellido_paterno"
                            v-model="formData.apellido_paterno"
                            placeholder="Consulte el DNI para llenar automáticamente"
                            :class="['w-full', getFieldClass('apellido_paterno')]"
                            @input="capitalizeInput('apellido_paterno')"
                            :disabled="isFieldDisabled('apellido_paterno')" />
                        <small v-if="errors.apellido_paterno" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.apellido_paterno[0] }}
                        </small>
                        <small v-else-if="!dniConsultado && !dniDataFailed && formData.dni.length < 8" class="text-blue-600 flex items-center">
                            <i class="pi pi-info-circle mr-1"></i>
                            Consulte el DNI para llenar automáticamente
                        </small>
                        <small v-else-if="dniDataFailed" class="text-orange-600 flex items-center">
                            <i class="pi pi-exclamation-circle mr-1"></i>
                            DNI no encontrado. Complete manualmente
                        </small>
                    </div>

                    <!-- Apellido Materno -->
                    <div class="flex flex-col gap-2">
                        <label for="apellido_materno" class="font-medium ">
                            <i class="pi pi-user mr-1 text-sm"></i>
                            Apellido Materno <span class="text-red-500">*</span>
                        </label>
                        <InputText 
                            id="apellido_materno"
                            v-model="formData.apellido_materno"
                            placeholder="Consulte el DNI para llenar automáticamente"
                            :class="['w-full', getFieldClass('apellido_materno')]"
                            @input="capitalizeInput('apellido_materno')"
                            :disabled="isFieldDisabled('apellido_materno')" />
                        <small v-if="errors.apellido_materno" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.apellido_materno[0] }}
                        </small>
                        <small v-else-if="!dniConsultado && !dniDataFailed && formData.dni.length < 8" class="text-blue-600 flex items-center">
                            <i class="pi pi-info-circle mr-1"></i>
                            Consulte el DNI para llenar automáticamente
                        </small>
                        <small v-else-if="dniDataFailed" class="text-orange-600 flex items-center">
                            <i class="pi pi-exclamation-circle mr-1"></i>
                            DNI no encontrado. Complete manualmente
                        </small>
                    </div>

                    <!-- Cargo -->
                    <div class="flex flex-col gap-2">
                        <label for="cargo" class="font-medium ">
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
                        <label for="correo" class="font-medium ">
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
                        <label for="pagina_web" class="font-medium ">
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
                        <label for="telefono" class="font-medium ">
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
                        <label for="observacion" class="font-medium ">
                            <i class="pi pi-comment mr-1 text-sm"></i>
                            Observaciones
                        </label>
                        <Textarea 
                            id="observaciones"
                            v-model="formData.observacion" 
                            rows="3"
                            placeholder="Ingrese observaciones adicionales (opcional)"
                            :class="['w-full', getFieldClass('observacion')]" />
                        <small v-if="errors.observacion" class="text-red-500 flex items-center">
                            <i class="pi pi-exclamation-triangle mr-1"></i>
                            {{ errors.observacion[0] }}
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
import Message from 'primevue/message'
import FileUpload from 'primevue/fileupload'

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
})

// Referencias
const fileUpload = ref(null)

// Form data
const formData = ref({
    ruc: '',
    razon_social: '',
    nombre_comercial: '',
    inicio_actividad: null,
    direccion: '',
    actividad_economica: '',
    estado: '',
    comentario: '',
    monto_comision: null,
    linea_cliente: null,
    adelanto: null,
    tasa_tem: null,
    linea_adelanto: null,
    porcentaje_anticipo: null,
    dni: '',
    nombre: '',
    apellido_paterno: '',
    apellido_materno: '',
    cargo: '',
    correo: '',
    pagina_web: '',
    telefono: '',
    observacion: '',
    archivos: [],
    producto_id: props.selectedProductoId,
    tipo_documento_id: props.selectedTipoId,
    moneda_id: props.selectedMonedaId  // ✅ AGREGAR ESTA LÍNEA
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
const rucDataFailed = ref(false)
const dniDataFailed = ref(false)
const selectedFiles = ref([])

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
        'ruc', 'razon_social', 'nombre_comercial', 'direccion', 'estado',
        'actividad_economica', 'monto_comision', 'linea_cliente', 'adelanto', 
        'tasa_tem', 'linea_adelanto', 'porcentaje_anticipo', 'dni', 'nombre', 
        'apellido_paterno', 'apellido_materno', 'cargo', 'correo', 
        'pagina_web', 'telefono'
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
        rucDataFailed.value = false
    }
}

function validateDni() {
    const dni = formData.value.dni.replace(/\D/g, '')
    formData.value.dni = dni
    
    if (dni.length === 8) {
        dniConsultado.value = false
        apiErrorDni.value = ''
        dniDataFailed.value = false
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

// Métodos para manejo de archivos
const onFileSelect = (event) => {
    selectedFiles.value = [...event.files]
    formData.value.archivos = [...event.files]
    
    // Validar archivos seleccionados
    const validFiles = validateFiles(event.files)
    if (validFiles.length !== event.files.length) {
        // Actualizar solo con archivos válidos
        selectedFiles.value = validFiles
        formData.value.archivos = validFiles
        
        // Limpiar y re-agregar archivos válidos al componente
        if (fileUpload.value) {
            fileUpload.value.clear()
            validFiles.forEach(file => {
                fileUpload.value.files.push(file)
            })
        }
    }
}

const onFileRemove = (event) => {
    selectedFiles.value = selectedFiles.value.filter(file => file.name !== event.file.name)
    formData.value.archivos = selectedFiles.value
}

const onFileUpload = (event) => {
    // Este método se ejecuta cuando se usa el upload automático
    // En nuestro caso usamos customUpload, así que aquí podríamos 
    // manejar la subida personalizada si fuera necesario
    console.log('Archivos listos para subir:', event.files)
}

const removeFile = (index) => {
    const removedFile = selectedFiles.value[index]
    selectedFiles.value.splice(index, 1)
    formData.value.archivos = selectedFiles.value
    
    // Actualizar el componente FileUpload
    if (fileUpload.value && fileUpload.value.files) {
        const fileIndex = fileUpload.value.files.findIndex(f => f.name === removedFile.name)
        if (fileIndex !== -1) {
            fileUpload.value.files.splice(fileIndex, 1)
        }
    }
    
    toast.add({
        severity: 'info',
        summary: 'Archivo eliminado',
        detail: `${removedFile.name} fue eliminado de la lista`,
        life: 2000
    })
}

const validateFiles = (files) => {
    const validFiles = []
    const maxSize = 5 * 1024 * 1024 // 5MB
    const allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg',
        'image/jpg',
        'image/png'
    ]
    
    files.forEach(file => {
        // Validar tamaño
        if (file.size > maxSize) {
            toast.add({
                severity: 'error',
                summary: 'Archivo muy grande',
                detail: `${file.name} excede el tamaño máximo de 5MB`,
                life: 4000
            })
            return
        }
        
        // Validar tipo
        if (!allowedTypes.includes(file.type)) {
            toast.add({
                severity: 'error',
                summary: 'Tipo de archivo no válido',
                detail: `${file.name} no es un tipo de archivo permitido`,
                life: 4000
            })
            return
        }
        
        validFiles.push(file)
    })
    
    return validFiles
}

const getFileIcon = (fileName) => {
    const extension = fileName.split('.').pop().toLowerCase()
    const iconMap = {
        'pdf': 'pi pi-file-pdf',
        'doc': 'pi pi-file-word',
        'docx': 'pi pi-file-word',
        'xls': 'pi pi-file-excel',
        'xlsx': 'pi pi-file-excel',
        'jpg': 'pi pi-image',
        'jpeg': 'pi pi-image',
        'png': 'pi pi-image'
    }
    return iconMap[extension] || 'pi pi-file'
}

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes'
    const k = 1024
    const sizes = ['Bytes', 'KB', 'MB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i]
}

// Consultas de APIs
async function consultarRuc() {
    if (formData.value.ruc.length !== 11) return
    
    isConsultingRuc.value = true
    apiError.value = ''
    rucDataFailed.value = false
    
    try {
        const response = await axios.get(`/api/consultas/ruc/${formData.value.ruc}`)
        
        if (response.data.success && response.data.data) {
            const data = response.data.data
            
            // Mapear campos según la estructura de la API
            formData.value.razon_social = data.nombre_o_razon_social || data.name || ''
            formData.value.nombre_comercial = data.trade_name || data.nombre_o_razon_social || data.name || ''
            formData.value.estado = data.estado || data.state || ''
            formData.value.direccion = data.direccion_completa || data.address || data.direccion || ''
            
            // Para actividad económica, si no viene en la API, permitir edición manual
            if (data.actividad_economica) {
                formData.value.actividad_economica = data.actividad_economica
            }
            
            rucConsultado.value = true
            direccionFromApi.value = !!formData.value.direccion
            editandoDireccion.value = false
            
            toast.add({
                severity: 'success',
                summary: 'RUC consultado',
                detail: 'Datos obtenidos correctamente de SUNAT',
                life: 3000
            })
        } else {
            // Datos no encontrados o inválidos
            rucDataFailed.value = true
            apiError.value = 'No se encontraron datos para este RUC'
            
            toast.add({
                severity: 'warn',
                summary: 'RUC no encontrado',
                detail: 'No se encontraron datos para este RUC. Complete los campos manualmente.',
                life: 4000
            })
        }
        
    } catch (error) {
        console.error('Error consultando RUC:', error)
        rucDataFailed.value = true
        
        if (error.response?.status === 404) {
            apiError.value = 'RUC no encontrado en SUNAT'
        } else if (error.response?.status === 429) {
            apiError.value = 'Demasiadas consultas. Intente nuevamente en unos minutos.'
        } else {
            apiError.value = 'Error al consultar RUC. Complete los campos manualmente.'
        }
        
        toast.add({
            severity: 'warn',
            summary: 'Error en consulta',
            detail: apiError.value,
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
    dniDataFailed.value = false
    
    try {
        const response = await axios.get(`/api/consultas/consultar-dni/${formData.value.dni}`)
        
        if (response.data.success && response.data.data) {
            const data = response.data.data
            
            // Mapear campos según la estructura de la API
            formData.value.nombre = data.nombres || ''
            formData.value.apellido_paterno = data.apellido_paterno || ''
            formData.value.apellido_materno = data.apellido_materno || ''
            
            dniConsultado.value = true
            
            toast.add({
                severity: 'success',
                summary: 'DNI consultado',
                detail: 'Datos obtenidos correctamente de RENIEC',
                life: 3000
            })
        } else {
            // Datos no encontrados o inválidos
            dniDataFailed.value = true
            apiErrorDni.value = 'No se encontraron datos para este DNI'
            
            toast.add({
                severity: 'warn',
                summary: 'DNI no encontrado',
                detail: 'No se encontraron datos para este DNI. Complete los campos manualmente.',
                life: 4000
            })
        }
        
    } catch (error) {
        console.error('Error consultando DNI:', error)
        dniDataFailed.value = true
        
        if (error.response?.status === 404) {
            apiErrorDni.value = 'DNI no encontrado en RENIEC'
        } else if (error.response?.status === 429) {
            apiErrorDni.value = 'Demasiadas consultas. Intente nuevamente en unos minutos.'
        } else {
            apiErrorDni.value = 'Error al consultar DNI. Complete los campos manualmente.'
        }
        
        toast.add({
            severity: 'warn',
            summary: 'Error en consulta',
            detail: apiErrorDni.value,
            life: 4000
        })
    } finally {
        isConsultingDni.value = false
    }
}

// Función para determinar si un campo debe estar deshabilitado
function isFieldDisabled(field) {
    const fieldsFromRuc = ['razon_social', 'nombre_comercial', 'estado']
    const fieldsFromDni = ['nombre', 'apellido_paterno', 'apellido_materno']
    const fieldsFromDireccion = ['direccion']
    
    // Solo deshabilitar si la consulta fue exitosa
    if (fieldsFromRuc.includes(field) && rucConsultado.value && !rucDataFailed.value) {
        return true
    }
    
    if (fieldsFromDni.includes(field) && dniConsultado.value && !dniDataFailed.value) {
        return true
    }
    
    if (fieldsFromDireccion.includes(field) && direccionFromApi.value && !editandoDireccion.value && !rucDataFailed.value) {
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
        if (key === 'producto_id' || key === 'tipo_documento_id' || key === 'moneda_id') return
        
        if (key === 'inicio_actividad') {
            formData.value[key] = null
        } else if (typeof formData.value[key] === 'number') {
            formData.value[key] = null
        } else if (key === 'archivos') {
            formData.value[key] = []
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
    rucDataFailed.value = false
    dniDataFailed.value = false
    selectedFiles.value = []
    
    // Limpiar el componente de archivos
    if (fileUpload.value) {
        fileUpload.value.clear()
    }
    
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

        // Preparar FormData para envío con archivos
        const formDataToSend = new FormData()
        
        // Agregar todos los campos del formulario
        Object.keys(formData.value).forEach(key => {
            if (key === 'archivos') {
                // Los archivos se agregan por separado
                return
            }
            
            const value = formData.value[key]
            if (value !== null && value !== undefined && value !== '') {
                // Convertir fecha a formato ISO si es necesario
                if (key === 'inicio_actividad' && value instanceof Date) {
                    formDataToSend.append(key, value.toISOString().split('T')[0])
                } else {
                    formDataToSend.append(key, value.toString())
                }
            }
        })
        
        // Agregar archivos
        selectedFiles.value.forEach((file, index) => {
            formDataToSend.append(`archivos[${index}]`, file)
        })

        const { data } = await axios.post('/persona/ruc', formDataToSend, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })

        toast.add({
            severity: 'success',
            summary: 'Registro exitoso',
            detail: `El prospecto empresarial fue registrado correctamente${selectedFiles.value.length > 0 ? ` con ${selectedFiles.value.length} archivo(s)` : ''}.`,
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

// Watchers
watch(() => formData.value.ruc, () => {
    if (errors.value.ruc) {
        delete errors.value.ruc
    }
    if (formData.value.ruc.length !== 11) {
        rucConsultado.value = false
        apiError.value = ''
        rucDataFailed.value = false
        direccionFromApi.value = false
        editandoDireccion.value = false
    }
})

watch(() => formData.value.dni, () => {
    if (errors.value.dni) {
        delete errors.value.dni
    }
    if (formData.value.dni.length !== 8) {
        dniConsultado.value = false
        apiErrorDni.value = ''
        dniDataFailed.value = false
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
                    'actividad_economica', 'estado', 'comentario', 'monto_comision', 'linea_cliente',
                    'adelanto', 'tasa_tem', 'linea_adelanto', 'porcentaje_anticipo',
                    'nombre', 'apellido_paterno', 'apellido_materno', 'cargo', 
                    'pagina_web', 'telefono', 'observacion']

watchFields.forEach(field => {
    watch(() => formData.value[field], () => {
        if (errors.value[field]) {
            delete errors.value[field]
        }
    })
})

// Watcher para archivos
watch(() => selectedFiles.value, () => {
    if (errors.value.archivos) {
        delete errors.value.archivos
    }
}, { deep: true })
</script>