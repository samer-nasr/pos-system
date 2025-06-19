<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { defineEmits,defineProps } from 'vue'

const props = defineProps<{
    categories: { name: string; id: number }[]; 
    selectedCategory: { name: string; id: number };
    mergedItems: { 
        name: string , 
        id : number , 
        price: number, 
        total_price: number , 
        quantity: number, 
        order_quantity: number,
        currency: {name: string , code: string, id:number}
    }[];

}>();

const emit = defineEmits(['loadItems', 'addItemToOrder','backToCategories']);

const loadItems = (category_id : number) => {
    emit('loadItems', category_id);
}

const addItemToOrder = (item : typeof props.mergedItems[0]) => {
    emit('addItemToOrder', item);
}

const backToCategories = () => {
    emit('backToCategories');
}

</script>

<template>

    <div class="w-1/2 h-[90vh] rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white p-2 flex flex-col">
        <!-- Title -->
        <h1 class="text-center text-2xl font-bold mb-2 text-black">POS System</h1>

        <!-- Yellow Category Buttons -->
        <div v-if="!props.selectedCategory" class="grid grid-cols-3 gap-2 mb-2 overflow-auto">
            <button
                class="bg-yellow-200 text-black py-2 h-36"
                v-for="category in props.categories"
                :key="category.id"
                @click="loadItems(category.id)"
            >
                {{ category.name }}
            </button>
        </div>

        <!-- Items Section -->
        <div v-else class="flex flex-col h-full">
        <div class="mt-auto pb-4 text-center">
            <button
            @click="backToCategories"
            class="text-blue-600 text-sm hover:text-blue-800 border p-1 rounded bg-blue-100 transition-colors"
            >
            ← Back to Categories
            </button>
        </div>

        <!-- Items Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 mb-2 flex-grow overflow-auto">
            <button
            v-for="item in mergedItems"
            :key="item.id"
            @click="addItemToOrder(item)"
            class="flex flex-col items-center bg-blue-100 rounded-lg p-4 text-black hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            style="min-height: 130px;"
            >
            <!-- Placeholder for item image -->
            <div class="w-20 h-20 bg-blue-300 rounded-md mb-3 flex items-center justify-center text-white font-bold">
                Img
            </div>

            <!-- Item name with ellipsis if too long -->
            <div class="text-center font-semibold text-base truncate w-full mb-1" :title="item.name">
                {{ item.name }}
            </div>

            <!-- Price with currency -->
            <div class="text-sm text-gray-700">
                {{ item.currency.code }} {{ item.price.toFixed(2) }}
            </div>
            </button>
        </div>
        </div>

    </div>
   
</template>