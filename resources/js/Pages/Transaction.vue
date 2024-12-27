<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import dayjs from "dayjs";
import { ref } from "vue";
import { debounce } from "lodash";
import PrimaryButton from "@/Components/PrimaryButton.vue";
const props = defineProps({
    transaction: Array,
});

const cat = ["pending", "completed", "cancelled"];
const status = ref("");
const isModalOpen = ref(false);
const selectedTransaction = ref();
const start_date = ref();
const end_date = ref();
const searchQuery = ref();
const filterSearch = ref("");
const selectedTransactionType = ref("");
const closeModal = () => {
    isModalOpen.value = false;
    selectedTransaction.value = null;
};
const updateStatus = async (transactionId) => {
    selectedTransaction.value = transactionId;
    isModalOpen.value = true;
};

const filterTransactions = async () => {
    Inertia.get("/transaction/filter", {
        start_date: start_date.value,
        end_date: end_date.value,
    });
};

const isEndDateDisabled = ref(false);
const checkEndDate = () => {
    const startDate = new Date(start_date.value);
    const endDate = new Date(end_date.value);

    if (endDate < startDate) {
        isEndDateDisabled.value = true; // Disable the end date field
        end_date.value = ""; // Clear the end date if it's invalid
    } else {
        isEndDateDisabled.value = false; // Enable the end date field
    }
};
const confirmUpdate = async () => {
    try {
        const response = await axios.put(
            `/transaction/update/${selectedTransaction.value}/status`,
            {
                status: status.value,
            }
        );
        const updatedTransaction = response.data.transaction;
        closeModal();
        const index = props.transaction.findIndex(
            (t) => t.id === updatedTransaction.id
        );
        if (index !== -1) {
            props.transaction[index] = updatedTransaction;
        }
    } catch (error) {
        closeModal();
        console.error("Error updating status:", error);
    }
};
const searchTransactions = debounce(function () {
    const params = {
        search: searchQuery.value,
        start_date: start_date.value,
        end_date: end_date.value,
        status: filterSearch.value,
        transaction_type: selectedTransactionType.value,
    };
    Inertia.get("/transaction/search", params);
}, 500);


</script>

<template>
    <Head title="Transaction List" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Transaction List
            </h2>
        </template>
        <!-- Date filter form -->

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <form @submit.prevent="filterTransactions">
                    <div class="grid grid-cols-5 gap-1 items-start mb-4">
                        <!-- Status filter -->

                        <div class="w-[100%]">
                            <label for="status" class="block">Status</label>
                            <select
                                v-model="filterSearch"
                                id="filterSearch"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                            >
                                <option value="">All</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                                <!-- Add more status options as needed -->
                            </select>
                        </div>
                        <div>
                            <label for="transaction_type" class="block"
                                >Transaction Type</label
                            >
                            <select
                                id="transaction_type"
                                v-model="selectedTransactionType"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md"
                            >
                                <option value="" selected="true">All</option>
                                <option value="deposit">Deposit</option>
                                <option value="withdrawal">Withdrawal</option>
                            </select>
                        </div>
                        <div class="w-[100%]">
                            <label for="status" class="block">Search</label>
                            <input
                                type="text"
                                v-model="searchQuery"
                                placeholder="Search transactions..."
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                            />
                        </div>

                        <!-- Date filter form -->

                        <div class="flex space-x-4">
                            <div>
                                <label for="start_date" class="block"
                                    >Start Date</label
                                >
                                <input
                                    type="date"
                                    v-model="start_date"
                                    id="start_date"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md"
                                    @change="checkEndDate"
                                />
                            </div>

                            <div>
                                <label for="end_date" class="block"
                                    >End Date</label
                                >
                                <input
                                    type="date"
                                    v-model="end_date"
                                    id="end_date"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md p-2"
                                    :min="start_date"
                                    :disabled="isEndDateDisabled"
                                />
                            </div>

                            <div>
                                <button
                                    type="button"
                                    @click="searchTransactions"
                                    class="bg-blue-500 text-white p-2 rounded mt-[25px]"
                                >
                                    Search
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
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
                                    Transaction Type
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    Amount($)
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    Created at
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    Updated at
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    <div
                                        class="flex flex-row gap-2 items-center"
                                    >
                                        <select
                                            v-model="status"
                                            id="status"
                                            name="status"
                                            class="p-1"
                                        >
                                            <option value="" disabled selected>
                                                Select a status
                                            </option>
                                            <option
                                                v-for="c in cat"
                                                :key="`cat-${c}`"
                                                :value="c"
                                            >
                                                {{ c }}
                                            </option>
                                        </select>
                                    </div>
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
                                    {{ tr.transaction_id }}
                                </td>
                                <td class="px-4 py-2 border-b">
                                    {{ tr.transaction_type }}
                                    <br />
                                    <span
                                        v-if="tr.status == 'completed'"
                                        class="inline-block bg-green-500 text-white text-xs font-bold rounded-full px-2 py-1"
                                        >{{ tr.status }}</span
                                    >
                                    <span
                                        v-if="tr.status == 'pending'"
                                        class="inline-block bg-yellow-500 text-white text-xs font-bold rounded-full px-2 py-1"
                                        >{{ tr.status }}</span
                                    >
                                    <span
                                        v-if="tr.status == 'cancelled'"
                                        class="inline-block bg-red-500 text-white text-xs font-bold rounded-full px-2 py-1"
                                        >{{ tr.status }}</span
                                    >
                                </td>
                                <td class="px-4 py-2 border-b">
                                    {{ tr.amount }}
                                </td>

                                <td class="px-4 py-2 border-b">
                                    {{
                                        dayjs(tr.created_at).format(
                                            "dddd, D MM, YYYY"
                                        )
                                    }}
                                </td>
                                <td class="px-4 py-2 border-b">
                                    {{
                                        dayjs(tr.updated_at).format(
                                            "dddd, D MM, YYYY"
                                        )
                                    }}
                                </td>
                                <!--<td class="px-4 py-2 border-b">
                                    {{ tr.status }}
                                </td>
                                -->
                                <th class="px-4 py-2 text-left border-b">
                                    <button
                                        class="bg-blue-500 text-white py-1 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 active:bg-blue-800"
                                        type="submit"
                                        @click.prevent="updateStatus(tr.id)"
                                    >
                                        Update status
                                    </button>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Confirmation Popup -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50"
        >
            <div class="bg-white rounded-lg w-1/3 p-6 shadow-lg">
                <h3 class="text-xl font-semibold mb-4">Are you sure?</h3>
                <p class="mb-4">
                    Do you really want to update the status of this transaction?
                </p>
                <div class="flex justify-end space-x-4">
                    <button
                        @click="confirmUpdate"
                        class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-700"
                    >
                        Yes
                    </button>
                    <button
                        @click="closeModal"
                        class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-700"
                    >
                        No
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
