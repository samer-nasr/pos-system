<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { defineEmits ,computed } from 'vue'
import ConfirmPayment from '@/components/pos/ConfirmPayment.vue';

const props = defineProps<{
    selectedItems: { 
                    name: string , 
                    id : number , 
                    price: number, 
                    total_price: number , 
                    quantity: number, 
                    order_quantity: number,
                    currency: {name: string , code: string, id:number},
                    rate: {rate: number , currency: string , counter_currency: string}
                    }[];
    totalOrderPrice: number;
    totalOrderPriceCounterCurrency: number;
}>();

const currentTime = ref('');
const barcode = ref('');
const showModal = ref(false);

const currencyTotalOrderPrice = computed(()=> {
    return props.selectedItems[0]?.currency?.code
    ? props.selectedItems[0].currency.code + ' ' + props.totalOrderPrice.toFixed(2)
    : props.totalOrderPrice.toFixed(2);
})

const counterCurrencyTotalOrderPrice = computed(()=> {
    return props.selectedItems[0]?.rate?.counter_currency
    ? props.selectedItems[0].rate.counter_currency + ' ' + props.totalOrderPriceCounterCurrency.toLocaleString()
    : props.totalOrderPriceCounterCurrency.toLocaleString();
})


const openPaymentModal = () => {
  showModal.value = true
}

const handleConfirmed = () => {
    payCart();
}

const emit = defineEmits(['payCart','removeItemFromOrder' ,'handleBarcodeChange', 'addItemToOrder']);

const removeItemFromOrder = (item: typeof props.selectedItems[0]) => {
    emit('removeItemFromOrder' , item);
}

const payCart = () => {
    emit('payCart');
}

const handleBarcodeChange = () => {
    if(barcode.value.length >= 3)
    {
        emit('handleBarcodeChange' , barcode.value);
        barcode.value = '';
    }
}

const changeItemQuantity = (item: typeof props.selectedItems[0], event: Event) => {
    const input = event.target as HTMLInputElement;
    const newQuantity = parseInt(input.value, 10);

    if (isNaN(newQuantity) || newQuantity < 0) {
        input.value = item.order_quantity.toString(); // Reset to previous value if invalid
        return;
    }
    // check if new quantity > current quantity add items
    if(newQuantity > item.order_quantity)
    {
        const itemsNbToAdd = newQuantity - item.order_quantity;
        if(newQuantity > item.quantity)  input.value = item.quantity.toString();
        for(let i = 0; i < itemsNbToAdd; i++)
        {
            emit('addItemToOrder', item);
        }
    }
    // check if new quantity < currenct quantity remove items
    else if(newQuantity < item.order_quantity)
    {
        const itemsNbToRemove = item.order_quantity - newQuantity;
        for(let i = 0; i < itemsNbToRemove; i++)
        {
            emit('removeItemFromOrder', item);
        }
    }
}

const appendToInput = (val:string) => {
    barcode.value += val;
    handleBarcodeChange();
}
const clearInput = () => {
    barcode.value = ''
}

const updateTime = () => {
  const now = new Date()
//   currentTime.value = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
  currentTime.value = [
  now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
  now.toLocaleDateString([], { year: 'numeric', month: '2-digit', day: 'numeric' })
].join('\t\t\t\t\t\t\t\t');

                    //   now.toLocaleDateString([] , { year: 'numeric', month: '2-digit', day: 'numeric' })
}
const inputRef = ref<HTMLInputElement | null>(null)

onMounted(() => {
  updateTime();
  setInterval(updateTime, 1000);
//   setInterval(() => {
//     inputRef.value?.focus()
//   }, 2000) // Delay in milliseconds
})

</script>

<template>
    <div class="w-1/4 h-[90vh] rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white p-1 flex flex-col">
                    <!-- Time -->
                    <div class="bg-gray-100 text-black text-lg px-2" style="white-space: pre">{{ currentTime }}</div>

                    <!-- Order List -->
                    <div class="flex-1 bg-gray-50 text-black mb-2 rounded  overflow-auto">
                        <table class="w-full text-sm text-left text-gray-700 border-collapse border border-gray-300 rounded-lg overflow-hidden">
                            <thead class="bg-gray-100 border-b border-gray-300">
                                <tr>
                                <th class="px-2 py-2 border-r border-gray-300">Item</th>
                                <th class="px-2 py-2 border-r border-gray-300">Price</th>
                                <th class="py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in selectedItems" :key="item.id" class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-2 py-3 border-r border-gray-200 flex space-x-2">
                                    <p>
                                        {{ item.name }} 
                                        <!-- ({{ item.order_quantity }})  -->
                                    </p>
                                    <input 
                                        type="number" 
                                        class="w-10 text-center border border-gray-500 rounded" 
                                        :value="item.order_quantity"
                                        @change="changeItemQuantity(item , $event)"
                                    >
                                    <span> X</span>
                                </td>
                                <td class="px-2 py-3 border-r border-gray-200">{{ item.currency.code }} {{ (item.total_price ?? item.price).toFixed(2) }}</td>
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
                    <div class="grid grid-cols-3 text-sm bg-gray-100 text-black p-1">
                        <span class="text-center">{{ counterCurrencyTotalOrderPrice }}</span>
                        <span class="text-center" >Market</span>
                        <!-- <span class="text-center" >{{ selectedItems[0]?.currency?.code ? selectedItems[0].currency.code : '' }} {{ totalOrderPrice.toFixed(2) }}</span> -->
                        <span class="text-center" >{{ currencyTotalOrderPrice }}</span>

                    </div>
                    <div class="grid grid-cols-1 text-sm bg-gray-100 text-black p-1 ">
                       <input ref="inputRef" v-model="barcode" @input="handleBarcodeChange" type="text" class="col-span-2 p-1 border border-gray-300 rounded text-center" placeholder="Enter bar code" />
                    </div>

                    <!-- Keypad -->
                    <div class="grid grid-cols-3 gap-1 mb-2">
                    <button class="bg-white text-black py-1 hover:bg-gray-300" @click="appendToInput('7')">7</button>
                    <button class="bg-white text-black py-1 hover:bg-gray-300" @click="appendToInput('8')">8</button>
                    <button class="bg-white text-black py-1 hover:bg-gray-300" @click="appendToInput('9')">9</button>
                    <button class="bg-white text-black py-1 hover:bg-gray-300" @click="appendToInput('4')">4</button>
                    <button class="bg-white text-black py-1 hover:bg-gray-300" @click="appendToInput('5')">5</button>
                    <button class="bg-white text-black py-1 hover:bg-gray-300" @click="appendToInput('6')">6</button>
                    <button class="bg-white text-black py-1 hover:bg-gray-300" @click="appendToInput('1')">1</button>
                    <button class="bg-white text-black py-1 hover:bg-gray-300" @click="appendToInput('2')">2</button>
                    <button class="bg-white text-black py-1 hover:bg-gray-300" @click="appendToInput('3')">3</button>
                    <button class="bg-black text-white py-2 border rounded" @click="clearInput">CLEAR</button>
                    <button class="bg-white text-black py-1 hover:bg-gray-300" @click="appendToInput('0')">0</button>
                    <!-- <button class="bg-black text-white py-2" @click="payCart">Pay</button> -->
                    <button class="bg-black text-white py-2 border rounded" @click="openPaymentModal">Pay</button>
                    </div>
                </div>
                <ConfirmPayment
                        :show="showModal"
                        :total-currency="props.totalOrderPrice"
                        :total-counter-currency="props.totalOrderPriceCounterCurrency"
                        :currencyTotalOrderPrice="currencyTotalOrderPrice"
                        :counterCurrencyTotalOrderPrice="counterCurrencyTotalOrderPrice"
                        :rate="selectedItems[0]?.rate? selectedItems[0].rate : {rate: 1, currency: 'USD', counter_currency: 'LBP'}"
                        @update:show="showModal = $event"
                        @confirmed="handleConfirmed"
                  />
</template>