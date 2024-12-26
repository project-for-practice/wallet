<template>
    <div class="w-[150px]">
        <button
            v-if="amount == null"
            class="bg-green-500 text-white py-1 px-4 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 active:bg-green-800"
            @click="fetchAmount(userId)"
        >
            Show
        </button>
        <p v-if="amount !== null">${{ amount.toFixed(2) }}</p>
    </div>
</template>

<script setup>
import { ref } from "vue";
defineProps({
    userId: Number,
});
const amount = ref(null);

const fetchAmount = async (userId) => {
    const response = await axios.get(`/transaction/user/${userId}/amount`);
    amount.value = response.data.amount;
};
</script>
