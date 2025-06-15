<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { defineEmits,defineProps } from 'vue'

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
        }[]
}>();

const emit = defineEmits(['loadItems', 'addItemToOrder','backToCategories']);

</script>

<template>

    <div class="w-1/4 h-[90vh] rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white p-2 flex flex-col gap-2">       
        <h2 class="text-center text-black">History</h2>          
        <div class="flex-1 bg-gray-50 text-black mb-2 rounded p-2 overflow-auto">
            <table class="w-full text-sm text-left text-gray-700 border-collapse border border-gray-300 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 border-b border-gray-300">
                    <tr>
                        <th class="px-1 py-3 border-r border-gray-300">Total</th>
                        <th class="px-3 py-1 border-r border-gray-300 text-center">Items</th>
                    </tr>
                </thead>
                <tbody  class="text-black border ">
                    <tr v-for="his in props.history" class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="px-1 py-3 border-r border-gray-200">${{ his.total_price }}</td>
                        <td class="px-3 py-1 border-r border-gray-300">
                            <tr v-for="item in his.items" :key="item.id" class="">
                                <td class="text-center">{{ item.item.name }} ( ${{ item.item.price }} X {{ item.quantity }} ) </td>
                            </tr>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
   
</template>