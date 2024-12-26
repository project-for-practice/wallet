<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    role: String,
});
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout :role="role">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Admin Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <img src="../../../public/images/home.webp" alt="" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
