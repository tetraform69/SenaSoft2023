<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import CalcularRuta from '@/Components/CalcularRuta.vue';
</script>

<template>
    <Head title="Calcular Rutas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Calcular Rutas</h2>
        </template>
        <div class="py-0 my-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <calcular-ruta></calcular-ruta>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

