
<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl w-full max-w-lg">
      <h2 class="text-xl font-bold mb-4 text-black text-center">Confirm Payment</h2>

    <div class="mb-4">
        <label class="block mb-2 text-gray-700 text-center">Total Price</label>
        <div class="flex space-x-2">
            <input
            v-model="currencyPrice"
            type="text"
            readonly
            class="w-1/2 border rounded p-2 text-black text-center"
            placeholder="Currency"
            />
            <input
            v-model="counterCurrencyPrice"
            type="text"
            readonly
            class="w-1/2 border rounded p-2 text-black text-center"
            placeholder="Amount"
            />
        </div>

        <label class="block mb-2 text-gray-700 mt-2 text-center">Payed Price</label>
        <div class="flex space-x-2 my-2">
            <div class="">
                <label class="block mb-2 text-gray-700 mt-1 text-center">{{ rate.currency }}</label>
                <input
                :value="formattedCurrencyValue"
                @input="formatCurrency"
                type="text"
                step="0.01"
                class=" border rounded p-2 text-black text-center"
                :placeholder="rate.currency"
                />
            </div>

            <div>
                <label class="block mb-2 text-gray-700 mt-1 text-center">{{ rate.counter_currency }}</label>
                <input
                @input="formatCounterCurrency"
                :value="formattedCounterCurrencyValue"
                type="text"
                class="border rounded p-2 text-black text-center"
                :placeholder="rate.counter_currency"
                />
            </div>
        </div>

        <label class="block mb-2 text-gray-700 mt-2 text-center">Return</label>
        <div class="flex space-x-2 my-2">
            <div class="">
                <label class="block mb-2 text-gray-700 mt-1 text-center">{{ rate.currency }}</label>
                <input
                :value="returnCurrency"
                type="text"
                readonly
                class=" border rounded p-2 text-black text-center"
                :placeholder="rate.currency"
                />
            </div>

            <div>
                <label class="block mb-2 text-gray-700 mt-1 text-center">{{ rate.counter_currency }}</label>
                <input
                :value="returnCounterCurrency"
                type="text"
                readonly
                class="border rounded p-2 text-black text-center"
                :placeholder="rate.counter_currency"
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
import { defineEmits, ref, watch,computed } from 'vue'

const props = defineProps<{
  show: boolean
  currencyTotalOrderPrice: string
  counterCurrencyTotalOrderPrice: string
  totalCurrency: number
  totalCounterCurrency: number
  rate: {
    currency: string
    counter_currency: string
  }
}>()

const emit = defineEmits(['update:show', 'confirmed']);

const currencyPrice = ref(props.currencyTotalOrderPrice || 0);
const counterCurrencyPrice = ref(props.counterCurrencyTotalOrderPrice || 0);
const totalCurrency = ref(props.totalCurrency);
const totalCounterCurrency = ref(props.totalCounterCurrency || 0);
const inputCurrency = ref<number | null>(null);
const inputCounterCurrency = ref<number | null>(null);

const formattedCounterCurrencyValue = computed(() => {
  if (inputCounterCurrency.value === null) return ''
  return inputCounterCurrency.value.toLocaleString('en-US') // You can change 'en-US' if needed
});

const formattedCurrencyValue = computed(() => {
  if (inputCurrency.value === null) return ''
  return inputCurrency.value.toLocaleString('en-US') // You can change 'en-US' if needed
});

const returnCurrency = computed(() => {
  if (inputCurrency.value !== null)
  return inputCurrency.value  - totalCurrency.value;
else
    return 0;
});

const returnCounterCurrency = computed(() => {
  if (inputCounterCurrency.value !== null)
  return (inputCounterCurrency.value  - totalCounterCurrency.value).toLocaleString();
else
    return 0;
});

watch(() => props.show, (newVal) => {
  if (newVal) 
    currencyPrice.value = props.currencyTotalOrderPrice || 0 ; 
    counterCurrencyPrice.value = props.counterCurrencyTotalOrderPrice || 0;
    totalCurrency.value = props.totalCurrency;
    totalCounterCurrency.value = props.totalCounterCurrency || 0;
});

const cancel = () => emit('update:show', false);

const confirm = () => {
    inputCurrency.value = null;
    inputCounterCurrency.value = null;
    totalCurrency.value = 0;
    totalCounterCurrency.value = 0;
    currencyPrice.value = 0;
    counterCurrencyPrice.value = 0;
    emit('confirmed');
    emit('update:show', false);
}

const formatCurrency = (event: Event) => {
  const input = (event.target as HTMLInputElement).value
  const cleaned = input.replace(/,/g, '')
  const numeric = parseFloat(cleaned)
  inputCurrency.value = isNaN(numeric) ? null : numeric
}

const formatCounterCurrency = (event: Event) => {
  const input = (event.target as HTMLInputElement).value
  const cleaned = input.replace(/,/g, '')
  const numeric = parseFloat(cleaned)
  inputCounterCurrency.value = isNaN(numeric) ? null : numeric
}
</script>
