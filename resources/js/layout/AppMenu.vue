<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppMenuItem from './AppMenuItem.vue';

const page = usePage();
const permissions = computed(() => page.props.auth.user?.permissions ?? []);
const hasPermission = (perm) => permissions.value.includes(perm);

const model = computed(() => [
    {
        label: 'Inicio',
        items: [
            { label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/dashboard' }
        ]
    },
    {
        label: 'Producto',
        icon: 'pi pi-fw pi-cog',
        items: [
            {
                label: 'Prospectos',
                icon: 'pi pi-fw pi-users',
                items: [
                    hasPermission('ver persona') && {
                        label: 'Reguistro de Prospecto',
                        icon: 'pi pi-fw pi-user-edit',
                        to: '/panel/tipo'
                    },
                    hasPermission('ver persona') && {
                        label: 'Listado de Persona',
                        icon: 'pi pi-fw pi-user-edit',
                        to: '/panel/tipo/list'
                    }
                ].filter(Boolean)
            }
        ].filter(section => section.items.length > 0),
    },
    {
        label: 'Administración',
        icon: 'pi pi-fw pi-cog',
        items: [
            {
                label: 'Gestión de Usuarios',
                icon: 'pi pi-fw pi-users',
                items: [
                    hasPermission('ver usuarios') && {
                        label: 'Usuarios',
                        icon: 'pi pi-fw pi-user-edit',
                        to: '/panel/usuario'
                    },
                    hasPermission('ver roles') && {
                        label: 'Roles',
                        icon: 'pi pi-fw pi-key',
                        to: '/panel/roles'
                    }
                ].filter(Boolean)
            },
            {
                label: 'Organización Interna',
                icon: 'pi pi-fw pi-sitemap',
                items: [
                    hasPermission('ver productos') && {
                        label: 'Productos',
                        icon: 'pi pi-fw pi-box',
                        to: '/panel/producto'
                    },
                    hasPermission('ver areas') && {
                        label: 'Áreas',
                        icon: 'pi pi-fw pi-building',
                        to: '/panel/area'
                    }
                ].filter(Boolean)
            },
            {
                label: 'Estado Generales',
                icon: 'pi pi-fw pi-tags',
                items: [
                    hasPermission('ver estado') && {
                        label: 'Estados',
                        icon: 'pi pi-fw pi-bookmark',
                        to: '/panel/estado'
                    }
                ].filter(Boolean)
            }
        ].filter(section => section.items.length > 0),
    },
].filter(section => section.items.length > 0));
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="i">
            <app-menu-item :item="item" :index="i" />
        </template>
    </ul>
</template>

<style scoped lang="scss">
/* Estilos opcionales */
</style>
