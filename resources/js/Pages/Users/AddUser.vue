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
                            <label for="name">Name</label>
                            <input
                                v-model="form.name"
                                type="text"
                                id="name"
                                class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                                :class="{ 'is-invalid': errors.name }"
                            />
                            <span v-if="errors.name" class="invalid-feedback">{{
                                errors.name[0]
                            }}</span>
                        </div>

                        <div class="form-group mt-3">
                            <label for="email">Email</label>
                            <input
                                v-model="form.email"
                                type="email"
                                id="email"
                                class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                                :class="{ 'is-invalid': errors.email }"
                            />
                            <span
                                v-if="errors.email"
                                class="invalid-feedback"
                                >{{ errors.email[0] }}</span
                            >
                        </div>

                        <div class="form-group mt-3">
                            <label for="password">Password</label>
                            <input
                                v-model="form.password"
                                type="password"
                                id="password"
                                class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                                :class="{ 'is-invalid': errors.password }"
                            />
                            <span
                                v-if="errors.password"
                                class="invalid-feedback"
                                >{{ errors.password[0] }}</span
                            >
                        </div>

                        <div class="form-group mt-3">
                            <label for="password_confirmation"
                                >Confirm Password</label
                            >
                            <input
                                v-model="form.password_confirmation"
                                type="password"
                                id="password_confirmation"
                                class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                                :class="{
                                    'is-invalid': errors.password_confirmation,
                                }"
                            />
                            <span
                                v-if="errors.password_confirmation"
                                class="invalid-feedback"
                                >{{ errors.password_confirmation[0] }}</span
                            >
                        </div>
                        <div class="form-group mt-3">
                            <label for="role">Role</label>
                            <select
                                v-model="form.role"
                                id="role"
                                class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                                :class="{ 'is-invalid': errors.role }"
                            >
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                            <span v-if="errors.role" class="invalid-feedback">{{
                                errors.role[0]
                            }}</span>
                        </div>
                        <PrimaryButton
                            type="submit"
                            class="mt-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Add User
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
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    role:""
});

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
        await Inertia.post("/users/add", form.data(), {
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
