<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
defineProps({
    transaction: Array,
});
</script>

<template>
    <Head title="Transaction List" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Transaction List
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="py-3 px-4 text-[bold] text-gray-900">
                        Transaction List
                    </div>
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left border-b">
                                    User Name
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    Transaction Id
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    Amount
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    Transaction Status
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    Created at
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    Updated at
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                class="hover:bg-gray-200"
                                v-for="tr in transaction"
                                :key="tr.id"
                            >
                                <td class="px-4 py-2 border-b">
                                    {{ tr.user_id }}
                                </td>
                                <td class="px-4 py-2 border-b">
                                    xdfgdfgd454wsfrertere
                                </td>
                                <td class="px-4 py-2 border-b">
                                    {{ tr.amount }}
                                </td>
                                <td class="px-4 py-2 border-b">
                                    {{ tr.status }}
                                </td>
                                <td class="px-4 py-2 border-b">
                                    {{ tr.created_at }}
                                </td>
                                <td class="px-4 py-2 border-b">
                                    {{ tr.created_at }}
                                </td>
                                <th class="px-4 py-2 text-left border-b">
                                    <button
                                        class="px-4 py-2 bg-[#474E93] text-white rounded"
                                    >
                                        Change Status
                                    </button>
                                </th>
                            </tr>
                            <tr class="hover:bg-gray-200">
                                <td class="px-4 py-2 border-b">Test</td>
                                <td class="px-4 py-2 border-b">
                                    xedrtfee454nderere
                                </td>
                                <td class="px-4 py-2 border-b">Completed</td>
                                <td class="px-4 py-2 border-b">21/12/2024</td>
                                <td class="px-4 py-2 border-b">21/12/2024</td>
                                <th class="px-4 py-2 text-left border-b">
                                    <button
                                        class="px-4 py-2 bg-[#474E93] text-white rounded"
                                    >
                                        Change Status
                                    </button>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
