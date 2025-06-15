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
        <div v-if="!props.selectedCategory" class="grid grid-cols-3 gap-2 mb-2">
            <button
                class="bg-yellow-200 text-black py-2"
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
                <button @click="backToCategories" class="text-blue-600 text-sm hover:text-blue-800 border p-1 rounded bg-blue-100">
                ← Back to Categories
                </button>
            </div>
            <!-- Items Grid -->
            <div class="grid grid-cols-3 gap-2 mb-2 flex-grow">
                <button
                class="bg-blue-100 text-black py-2"
                v-for="item in mergedItems"
                :key="item.id"
                @click="addItemToOrder(item)"
                >
                {{ item.name }}
                <div class="text-sm text-gray-700">{{ item.currency.code }} {{ item.price.toFixed(2) }}</div>
                </button>
            </div>

            <!-- Back Button -->
            <!-- <div class="mt-auto pt-4 text-center">
                <button @click="backToCategories" class="text-blue-600 underline text-sm hover:text-blue-800">
                ← Back to Categories
                </button>
            </div> -->
        </div>
    </div>
   
</template>