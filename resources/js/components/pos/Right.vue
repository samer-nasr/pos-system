<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { defineEmits, defineProps } from 'vue';
import EOTD from '@/components/pos/EOTD.vue';


const props = defineProps<{
    history:
    {
        item_quantity: number,
        total_price: number,
        items:
        {
            id: number,
            quantity: number,
            item:
            {
                name: string,
                price: number,
                quantity: number
            }
        }[];
    }[],
    total_sales: {
        total_sales_currency: string,
        total_sales_cCurrency: string,
        total_orders: number,
        total_items: number
    }
}>();

const emit = defineEmits(['getTotalSales']);

const showModal = ref(false);
const openModal = () => {
    emit('getTotalSales')
    showModal.value = true
}

</script>

<template>

    <div
        class="w-1/4 h-[90vh] rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white p-2 flex flex-col gap-2">
        <div class="flex justify-between text-black">
            <h2 class="text-center ">History</h2>
            <button class="border px-1 hover:bg-black hover:text-white" @click="openModal">
                CASHOUT
            </button>
            <button class="border px-1 hover:bg-black hover:text-white" @click="openModal">
                End Of the Day
            </button>
        </div>
        <div class="flex-1 bg-gray-50 text-black mb-2 rounded p-2 overflow-auto">
            <table
                class="w-full text-sm text-left text-gray-700 border-collapse border border-gray-300 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 border-b border-gray-300">
                    <tr>
                        <th class="px-1 py-3 border-r border-gray-300">Total</th>
                        <th class="px-3 py-1 border-r border-gray-300 text-center">Items</th>
                    </tr>
                </thead>
                <tbody class="text-black border ">
                    <tr v-for="his in props.history" class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="px-1 py-3 border-r border-gray-200">${{ his.total_price }}</td>
                        <td class="px-3 py-1 border-r border-gray-300">
                    <tr v-for="item in his.items" :key="item.id" class="">
                        <td class="text-center">{{ item.item.name }} ( ${{ item.item.price }} X {{ item.quantity }} )
                        </td>
                    </tr>
                    </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <EOTD :show="showModal" :total_sales="props.total_sales" @update:show="showModal = $event" />

</template>