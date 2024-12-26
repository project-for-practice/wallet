<template>
    <Head title="User List" />
    <AuthenticatedLayout :role="role">
        <template #header>
            <div v-if="message" class="popup-overlay" @click="closePopup">
                <div class="popup">
                    <p>{{ message }}</p>
                </div>
            </div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Add New User
            </h2>
        </template>
        <div>
            <div v-if="errors.length" class="alert alert-danger">
                <ul>
                    <li v-for="error in errors" :key="error">{{ error }}</li>
                </ul>
            </div>
            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <!-- User Form -->
                    <form @submit.prevent="submit">
                        <div class="form-group mt-3">
                            <label for="name">User Name</label>
                            <input
                                v-model="form.user_id"
                                type="text"
                                id="name"
                                class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                                :class="{ 'is-invalid': errors.user_id }"
                            />
                            <span
                                v-if="errors.user_id"
                                class="invalid-feedback"
                                >{{ errors.user_id[0] }}</span
                            >
                        </div>

                        <div class="form-group mt-3">
                            <label for="email">Transaction id</label>
                            <input
                                v-model="form.email"
                                type="text"
                                id="transaction_id"
                                class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                                :class="{ 'is-invalid': errors.transaction_id }"
                            />
                            <span
                                v-if="errors.transaction_id"
                                class="invalid-feedback"
                                >{{ errors.transaction_id[0] }}</span
                            >
                        </div>

                        <div class="form-group mt-3">
                            <label for="password">Amount</label>
                            <input
                                v-model="form.amount"
                                type="number"
                                id="amount"
                                class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                                :class="{ 'is-invalid': errors.amount }"
                            />
                            <span
                                v-if="errors.amount"
                                class="invalid-feedback"
                                >{{ errors.amount[0] }}</span
                            >
                        </div>

                        <div class="form-group mt-3">
                            <label for="role">Status</label>
                            <select
                                v-model="form.status"
                                id="role"
                                class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                                :class="{ 'is-invalid': errors.status }"
                            >
                                <option value="">Select Status</option>
                                <option v-for="c in cat" :key="c" :value="c">
                                    {{ c }}
                                </option>
                            </select>
                            <span
                                v-if="errors.status"
                                class="invalid-feedback"
                                >{{ errors.status[0] }}</span
                            >
                        </div>
                        <div class="form-group mt-3">
                            <label for="role">Transaction type</label>
                            <select
                                v-model="form.transaction_type"
                                id="role"
                                class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                                :class="{ 'is-invalid': errors.role }"
                            >
                                <option value="">Select type</option>
                                <option
                                    v-for="tt in transType"
                                    :key="tt"
                                    :value="tt"
                                >
                                    {{ tt }}
                                </option>
                            </select>
                            <span
                                v-if="errors.transaction_type"
                                class="invalid-feedback"
                                >{{ errors.transaction_type[0] }}</span
                            >
                        </div>
                        <PrimaryButton
                            type="submit"
                            class="mt-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Add Transaction
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
const props = defineProps({
    message: String,
});
const form = useForm({
    user_id: "",
    amount: "",
    transaction_type: "",
    transaction_id: "",
    status: "",
});
const cat = ["pending", "completed", "cancelled"];
const transType = ["deposit", "withdrawal"];
const errors = ref([]);
const message = ref(props.message);
watch(
    () => props.message,
    (newMessage) => {
        message.value = newMessage;
    }
);
const closePopup = () => {
    message.value = "";
};
// Method to handle form submission
const submit = async () => {
    try {
        // Send the form data via Inertia
        await Inertia.post("/transactions/add", form.data(), {
            onSuccess: () => {
                form.reset(); // Reset form if the user is added successfully
                errors.value = []; // Clear errors
            },
            onError: (error) => {
                errors.value = error.response.data.errors || []; // Capture validation errors
            },
        });
    } catch (error) {
        console.error("There was an error adding the user:", error);
    }
};
</script>

<style scoped>
.invalid-feedback {
    color: red;
}
</style>
