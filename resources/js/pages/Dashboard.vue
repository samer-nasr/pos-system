<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted ,computed  } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps<{
    categories: { name: string; id: number }[]; 
    items: { name: string , id : number , price: number, total_price: number , quantity: number, order_quantity: number}[];
    selectedCategory: { name: string; id: number };
}>();

const currentTime = ref('');
const selectedItems = ref<{ name: string , id : number , price: number, total_price: number , quantity: number, order_quantity: number}[]>([]);
// const total_price = ref(0);

const totalOrderPrice = computed(() => {
  return selectedItems.value.reduce((sum, item) => {
    const itemTotal = item.total_price ?? item.price * item.quantity
    return sum + itemTotal
  }, 0)
})

// merge items with selected items
const mergedItems = computed(() => {
  return props.items.map(item => {
    const selected = selectedItems.value.find(i => i.id === item.id)
    return selected 
      ? selected 
      : { ...item, order_quantity: 0 } 
  })
})

//load items based on the selected category
const loadItems = (categoryId: number) => {
  router.get('dashboard', { category_id: categoryId }, { preserveScroll: true, preserveState: true })
}

// go back to category list
const backToCategories = () => {
    router.get('/dashboard',{}, { preserveScroll: true, preserveState: true })
}

// add item to order when the user click on it
const addItemToOrder = (item: {name: string , id: number , price: number,total_price: number, quantity: number, order_quantity: number}) => {
    // check if the item is already in the order
    const existing_item = selectedItems.value.find(selectedItem => selectedItem.id === item.id);
    if(existing_item)
    {
        // check if the item is out of stock
        if(item.quantity == item.order_quantity)
        {
            showAlertMessage('Item out of stock!' , 'error');
            return;
        }
        existing_item.order_quantity++;
        existing_item.total_price += item.price;
        return;
    }
    item.total_price = item.price;
    item.order_quantity = 1;
    selectedItems.value.push(item);
    // alert(`Added ${item.name} to order`);
}

const removeItemFromOrder = (item: {name: string , id: number , price: number,total_price: number, quantity: number , order_quantity: number}) => {
    const existing_item = selectedItems.value.find(selectedItem => selectedItem.id === item.id);
    if(existing_item)
    {
        existing_item.order_quantity--;
        existing_item.total_price -= item.price;
        if(existing_item.order_quantity <= 0) {
            selectedItems.value = selectedItems.value.filter(selectedItem => selectedItem.id !== item.id);
        }
        return;
    }
    selectedItems.value = selectedItems.value.filter(item => item.id !== item.id);
}

const payCart = () => {
    alert(selectedItems.value.length);
    if(!(selectedItems.value.length > 0))
    {
        showAlertMessage('Order is empty', 'error');
    } 
    else
    {
        router.post('dashboard/pay', 
            {
                items: selectedItems.value,
                total_price: totalOrderPrice.value
            },
            {
                onSuccess: () => {
                    selectedItems.value = [];
                    showAlertMessage('Payed successfully', 'success');
            },
        });
    }
}

const updateTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
}

const showAlertMessage = (message: string, type: 'success' | 'error' | 'warning' | 'info' | 'question') => {
  Swal.fire({
    title: type.charAt(0).toUpperCase() + type.slice(1) + '!',
    text: message,
    icon: type,
    confirmButtonText: 'OK',
  })
}

onMounted(() => {
  updateTime()
  setInterval(updateTime, 1000)
})
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">

       <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
            <div class="flex gap-4">
                <!-- Left Panel (25%) -->
                <div class="w-1/4 h-[90vh] rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white p-1 flex flex-col">
                    <!-- Time -->
                    <div class="bg-gray-100 text-black text-lg p-2 mb-2">{{ currentTime }}</div>

                    <!-- Order List -->
                    <div class="flex-1 bg-gray-50 text-black mb-2 rounded p-2 overflow-auto">
                        <table class="w-full text-sm text-left text-gray-700 border-collapse border border-gray-300 rounded-lg overflow-hidden">
                            <thead class="bg-gray-100 border-b border-gray-300">
                                <tr>
                                <th class="px-2 py-3 border-r border-gray-300">Item</th>
                                <th class="px-2 py-3 border-r border-gray-300">Price</th>
                                <th class="py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in selectedItems" :key="item.id" class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-2 py-3 border-r border-gray-200">{{ item.name }} ({{ item.order_quantity }})</td>
                                <td class="px-2 py-3 border-r border-gray-200">${{ (item.total_price ?? item.price).toFixed(2) }}</td>
                                <td class="py-3 text-center">
                                    <button @click="removeItemFromOrder(item)" class="text-red-600 hover:text-red-800 font-semibold">
                                    X
                                    </button>
                                </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <!-- Totals -->
                    <div class="grid grid-cols-3 text-sm bg-gray-100 text-black p-2 mb-2">
                        <span>00:00:07</span>
                        <span>Market</span>
                        <span>${{ totalOrderPrice.toFixed(2) }}</span>
                    </div>

                    <!-- Keypad -->
                    <div class="grid grid-cols-3 gap-1 mb-2">
                        <button class="bg-white text-black py-2">7</button>
                        <button class="bg-white text-black py-2">8</button>
                        <button class="bg-white text-black py-2">9</button>
                        <button class="bg-white text-black py-2">4</button>
                        <button class="bg-white text-black py-2">5</button>
                        <button class="bg-white text-black py-2">6</button>
                        <button class="bg-white text-black py-2">1</button>
                        <button class="bg-white text-black py-2">2</button>
                        <button class="bg-white text-black py-2">3</button>
                        <button class="bg-black text-white py-2">CLEAR</button>
                        <button class="bg-white text-black py-2">0</button>
                        <button class="bg-black text-white py-2">REPEAT</button>
                    </div>
                </div>

                <!-- Middle Panel (50%) -->
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
                        <!-- Items Grid -->
                        <div class="grid grid-cols-3 gap-2 mb-2 flex-grow">
                            <button
                            class="bg-blue-100 text-black py-2"
                            v-for="item in mergedItems"
                            :key="item.id"
                            @click="addItemToOrder(item)"
                            >
                            {{ item.name }}
                            <div class="text-sm text-gray-700">${{ item.price.toFixed(2) }}</div>
                            </button>
                        </div>

                        <!-- Back Button -->
                        <div class="mt-auto pt-4 text-center">
                            <button @click="backToCategories" class="text-blue-600 underline text-sm hover:text-blue-800">
                            ← Back to Categories
                            </button>
                        </div>
                    </div>


                    <!-- Orange/Black Controls -->
                    <!-- <div class="grid grid-cols-3 gap-2 mb-2">
                        <button class="bg-orange-500 text-white py-2">MAINS AWAY</button>
                        <button class="bg-orange-500 text-white py-2">MESSAGES</button>
                        <button class="bg-orange-500 text-white py-2">EXTRAS</button>

                        <button class="bg-black text-white py-2">MAINS AWAY TIME</button>
                        <button class="bg-black text-white py-2">PLATES</button>
                        <button class="bg-black text-white py-2">PRINT CHECK</button>

                        <button class="bg-red-700 text-white py-2">ERROR</button>
                        <button class="bg-green-500 text-white py-2">CORRECT</button>
                        <button class="bg-green-600 text-white py-2">SEND</button>
                    </div> -->
                </div>

                <!-- Right Panel (25%) -->
                <div class="w-1/4 h-[90vh] rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white p-2 flex flex-col gap-2">
                    <button class="bg-gray-600 text-white py-2">DRINKS</button>
                    <button class="bg-red-600 text-white py-2">FOOD</button>
                    <button class="bg-gray-600 text-white py-2">TAKE AWAY</button>
                    <button class="bg-gray-600 text-white py-2">SET MENUS</button>
                    <button class="bg-white text-black font-bold py-2" @click="payCart">PAY</button>
                    <button class="bg-gray-600 text-white py-2">MANAGER PAY</button>
                    <button class="bg-gray-600 text-white py-2">PAID BY APP</button>
                    <button class="bg-gray-600 text-white py-2">LIST</button>
                    <button class="bg-gray-600 text-white py-2">ENTER NAME</button>
                </div>
            </div>
        </div> 
    </AppLayout>
</template>
