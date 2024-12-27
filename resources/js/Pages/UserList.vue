<template>
    <Head title="User List" />
    <AuthenticatedLayout :role="role">
        <template #header>
            <div v-if="message" class="popup-overlay p-10" @click="closePopup">
                <div class="popup p-10">
                    <p>{{ message }}</p>
                    <PrimaryButton @click="closePopup" class="ml-10"
                        >close</PrimaryButton
                    >
                </div>
            </div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                User List {{ message }}

                <PrimaryButton @click="goToAddUser" class="ml-10"
                    >Add New User</PrimaryButton
                >
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Search and Filter Form -->
                <div class="grid grid-cols-5 gap-1 items-start mb-4">
                    <div>
                        <label for="search">Search by Name</label>
                        <input
                            id="search"
                            v-model="filters.search"
                            type="text"
                            placeholder="Enter name"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                        />
                    </div>

                    <div>
                        <label for="status">Status</label>
                        <select
                            v-model="filters.status"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                        >
                            <option value="">All</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>

                    <div>
                        <label for="role">Role</label>
                        <select
                            v-model="filters.role"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md w-[100%]"
                        >
                            <option value="">All</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                    <button
                        @click.prevent="applyFilters"
                        class="bg-blue-500 text-white p-2 rounded mt-[30px]"
                    >
                        Apply Filters
                    </button>
                </div>
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left border-b">Add Amount</th>
                                <th class="px-4 py-2 text-left border-b">
                                    Name
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    Email
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    Balance
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    Role
                                </th>

                                <th class="px-4 py-2 text-left border-b">
                                    Status
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    Created at
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    <select
                                        v-model="status"
                                        id="status"
                                        name="status"
                                        class="p-1 w-[100%]"
                                    >
                                        <option value="" disabled selected>
                                            Select a status
                                        </option>
                                        <option
                                            v-for="c in userStatus"
                                            :key="`status-${c}`"
                                            :value="c"
                                        >
                                            {{ c }}
                                        </option>
                                    </select>
                                </th>
                                <th class="px-4 py-2 text-left border-b">
                                    Delete
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through users and display their data -->
                            <tr
                                v-for="user in userList"
                                :key="user.id"
                                class="hover:bg-gray-200"
                            >
                                <td class="px-2 py-2 border-b">
                                    <PrimaryButton
                                        @click="goToAddTransacton(user.id)"
                                        class=""
                                        >New ($)</PrimaryButton
                                    >
                                </td>
                                <td class="px-4 py-2 border-b">
                                    {{ user.name }}
                                </td>
                                <td class="px-4 py-2 border-b">
                                    {{ user.email }}
                                </td>
                                <td class="px-4 py-2 border-b">
                                    <BalanceComponent :user-id="user.id" />
                                </td>
                                <td class="px-4 py-2 border-b">
                                    {{ user.roles }}
                                </td>
                                <td class="px-4 py-2 border-b">
                                    <span :class="getStatusClass(user.status)">
                                        {{
                                            user.status
                                                .charAt(0)
                                                .toUpperCase() +
                                            user.status.slice(1)
                                        }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 border-b">
                                    {{
                                        dayjs(user.updated_at).format(
                                            "dddd, D MM, YYYY"
                                        )
                                    }}
                                </td>
                                <th class="px-4 py-2 text-left border-b">
                                    <button
                                        class="w-[100%] bg-blue-500 text-white py-1 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 active:bg-blue-800"
                                        type="submit"
                                        @click.prevent="updateStatus(user.id)"
                                    >
                                        Update status
                                    </button>
                                </th>
                                <td class="px-4 py-2 border-b">
                                    <button @click="deleteUser(user.id)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import BalanceComponent from "@/Components/User/BalanceComponent.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, useForm } from "@inertiajs/vue3";
import axios from "axios";
import dayjs from "dayjs";
import { ref, watch } from "vue";
const props = defineProps({
    users: Array,
    message: String,
    filters: Object,
});
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
const userStatus = ["active", "inactive", "suspended"];
const status = ref("");
const filters = ref({ ...props.filters });
const userList = ref({ ...props.users });
const updateStatus = (userId) => {
    useForm({
        status: status.value,
    }).put(route("users-updateStatus", userId));
};
// const applyFilters = () => {
//     Inertia.get("/users/filter", filters.value);
// };
const goToAddUser = () => {
    Inertia.visit("/users/create");
};
const goToAddTransacton = (user_id) => {
    Inertia.visit(`/transaction/create/${user_id}`);
};
const applyFilters = async () => {
    try {
        const response = await axios.get("/users/filter", {
            params: filters.value, // Send filter params to the backend
        });
        userList.value = response.data;
    } catch (error) {
        console.error("Error fetching filtered users:", error);
    }
};

const deleteUser = (userId) => {
    if (confirm("Are you sure you want to delete this user?")) {
        Inertia.delete(`/users/${userId}`, {
            onSuccess: () => {
                // Optionally, filter out the deleted user locally (optimistic UI update)
                users.value = users.value.filter((user) => user.id !== userId);
            },
        });
    }
};
const getStatusClass = (status) => {
    switch (status) {
        case "active":
            return "text-green-500";
        case "inactive":
            return "text-gray-500";
        case "suspended":
            return "text-red-500";
        default:
            return "";
    }
};
</script>

<style scoped>
.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.popup {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 300px;
}
.popup button {
    background: #007bff;
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
}

.popup button:hover {
    background: #0056b3;
}
</style>
