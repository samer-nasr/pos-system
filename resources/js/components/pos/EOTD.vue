
<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl w-full max-w-lg">
      <h2 class="text-xl font-bold mb-4 text-black text-center">Payments</h2>

    <div class="mb-4">
        <label class="block mb-2 text-gray-700 text-center">Total Payments</label>
        <div class="flex space-x-2">
            <input
            v-model="props.total_sales.total_sales_currency"
            type="text"
            readonly
            class="w-1/2 border rounded p-2 text-black text-center"
            />
            <input
            v-model="props.total_sales.total_sales_cCurrency"
            type="text"
            readonly
            class="w-1/2 border rounded p-2 text-black text-center"
            />
        </div>
        <div class="flex space-x-2 my-2">
            <div class="">
                <label class="block mb-2 text-gray-700 mt-1 text-center">Total Orders</label>
                <input
                v-model="props.total_sales.total_orders"
                type="text"
                readonly
                step="0.01"
                class=" border rounded p-2 text-black text-center"
                />
            </div>

            <div>
                <label class="block mb-2 text-gray-700 mt-1 text-center">Total Items</label>
                <input
                v-model="props.total_sales.total_items"
                readonly
                type="text"
                class="border rounded p-2 text-black text-center"
                />
            </div>
        </div>
    </div>

      <div class="flex justify-center space-x-2">
        <button @click="cancel" class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">
          Cancel
        </button>
        <button @click="confirm" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
          Confirm
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineEmits, ref, watch,computed } from 'vue';
import { router } from '@inertiajs/vue3';


const props = defineProps<{
  show: boolean,
  total_sales : {
        total_sales_currency : string,
        total_sales_cCurrency : string,
        total_orders : number,
        total_items : number
    }
}>()

const emit = defineEmits(['update:show', 'getTotalSales']);



const cancel = () => emit('update:show', false);

const confirm = () => {
    router.post('dashboard/eotd' , {} , { preserveScroll: true, preserveState: true });
    emit('update:show', false);
}

</script>
