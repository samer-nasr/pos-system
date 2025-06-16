<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Left from '@/components/pos/Left.vue';
import Center from '@/components/pos/Center.vue';
import Right from '@/components/pos/Right.vue';
import ConfirmPayment from '@/components/pos/ConfirmPayment.vue';
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
    // todo: impelement get items from category without fetch again
    categories: { 
        name: string; 
        id: number;
        items: { 
            name: string , 
            id : number , 
            price: number, 
            total_price: number , 
            quantity: number, 
            order_quantity: number,
            currency: {name: string , code: string, id:number},
            rate: {rate: number , currency: string , counter_currency: string}
        }[];
    }[]; 
    selectedCategory: { name: string; id: number };
    barcode_item: typeof selectedItems.value[0] | null;
    items: { 
        name: string , 
        id : number , 
        price: number, 
        total_price: number , 
        quantity: number, 
        order_quantity: number,
        currency: {name: string , code: string, id:number},
        rate: {rate: number , currency: string , counter_currency: string}
    }[];
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

const selectedItems = ref<
                    { 
                            name: string , 
                            id : number , 
                            price: number, 
                            total_price: number , 
                            quantity: number, 
                            order_quantity: number,
                            currency: {name: string , code: string, id:number},
                            rate: {rate: number , currency: string , counter_currency: string}
                        }[]>([]);

// merge items with selected items
const mergedItems = computed(() => {
  return props.items.map(item => {
    const selected = selectedItems.value.find(i => i.id === item.id)
    return selected 
      ? selected 
      : { ...item, order_quantity: 0 } 
  })
})

// total price of the order
const totalOrderPrice = computed(() => {
  return selectedItems.value.reduce((sum, item) => {
    const itemTotal = item.total_price ?? item.price * item.quantity
    return sum + itemTotal
  }, 0)
})

// total price of the order
const totalOrderPriceCounterCurrency = computed(() => {
//   return totalOrderPrice.value * (props.items[0]?.rate?.rate? props.items[0].rate.rate :0);
  return totalOrderPrice.value * (props.categories[0]?.items[0]?.rate?.rate ? props.categories[0]?.items[0]?.rate?.rate : 0);
});

//load items based on the selected category
const loadItems = (categoryId: number) => {
  router.get('dashboard', { category_id: categoryId }, { preserveScroll: true, preserveState: true })
}

// go back to category list
const backToCategories = () => {
    router.get('dashboard',{}, { preserveScroll: true, preserveState: true })
}

// add item to order when the user click on it
const addItemToOrder = (item: typeof selectedItems.value[0]) => {
    // check if the item is out of stock
    const order_quantity = selectedItems.value.find(selectedItem => selectedItem.id === item.id)?.order_quantity ?? 0;
    // alert(item.quantity+' '+order_quantity);
    if(item.quantity <= order_quantity)
    {
        showAlertMessage('Item out of stock!' , 'error');
        return;
    }
    
    // check if the item is already in the order
    const existing_item = selectedItems.value.find(selectedItem => selectedItem.id === item.id);
    if(existing_item)
    {
        existing_item.order_quantity++;
        existing_item.total_price += item.price;
        return;
    }
    
    item.total_price = item.price;
    item.order_quantity = 1;
    selectedItems.value.push(item);
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

const showAlertMessage = (message: string, type: 'success' | 'error' | 'warning' | 'info' | 'question') => {
  Swal.fire({
    title: type.charAt(0).toUpperCase() + type.slice(1) + '!',
    text: message,
    icon: type,
    confirmButtonText: 'OK',
  })
}

const handleBarcodeChange = (barcode: string)=> {
    router.get('dashboard', { barcode: barcode }, { preserveScroll: true, preserveState: true, 
        onSuccess: ()=> {
            if(props.barcode_item)
            {
                addItemToOrder(props.barcode_item);
            }
            else
            {
                showAlertMessage('Item not found', 'error');
            }
            barcode = '';
        } 
    });
}

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">

       <div class="flex h-full flex-1 flex-col  rounded-xl">
            <div class="flex gap-1">
                <!-- Left Panel (25%) -->
                 <Left 
                    :selected-items="selectedItems"  
                    :total-order-price="totalOrderPrice"
                    :totalOrderPriceCounterCurrency="totalOrderPriceCounterCurrency"
                    @payCart="payCart" 
                    @removeItemFromOrder="removeItemFromOrder"
                    @handleBarcodeChange="handleBarcodeChange"
                    @addItemToOrder="addItemToOrder"
                />
                <!-- Middle Panel (50%) -->
                <Center 
                    :categories="props.categories" 
                    :selectedCategory="selectedCategory" 
                    :mergedItems="mergedItems" 
                    @add-item-to-order="addItemToOrder"
                    @load-items="loadItems"
                    @back-to-categories="backToCategories"
                   />
               
                <!-- Right Panel (25%) -->
                <Right :history="props.history" />
            </div>
        </div> 
    </AppLayout>
</template>
