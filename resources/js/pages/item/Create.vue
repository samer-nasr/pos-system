<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head , useForm} from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { LoaderCircle } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import Swal from 'sweetalert2';

const props = defineProps<{
    categories: { name: string , id: number}[];
    brands: { name: string , id: number}[];
    rates: { currency_id: number , id: number , counter_currency_id: number , rate: number}[];
    currencies: {name: string, code: string, id : number}[];
    counter_currencies:{name: string, code: string, id : number}[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Items',
        href: '/items',
    },
];

const form = useForm({
    name: '',
    price: '',
    cost: '',
    quantity: '',
    category:'',
    bar_code: '',
    brand:'',
    rate:'',
    currency:'',
    counter_currency:''
});

const showSuccessAlert = () => {
    Swal.fire({
        title: 'Success!',
        text: 'Item created successfully.',
        icon: 'success',
        confirmButtonText: 'OK',
    });
};

const submit = () => {
    form.post(route('items.store'), {
        onSuccess: () => {
            showSuccessAlert();
            form.reset('name', 'price','quantity');
        },
    });
};
</script>

<template>
    <Head title="Items" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <form @submit.prevent="submit">
                <h2 class="text-center mb-4">Create new item</h2>
                <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="Item name" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="category">Category</Label>
                    <select id="category" :tabindex="2" autocomplete="category" v-model="form.category" class="text-black h-8 rounded-lg px-1">
                        <option value="">Select a category</option>
                        <option v-for="category in props.categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    <InputError :message="form.errors.category" />
                </div>

                <div class="grid gap-2">
                    <Label for="brand">Brand</Label>
                    <select id="brand" :tabindex="3" autocomplete="brand" v-model="form.brand" class="text-black h-8 rounded-lg px-1">
                        <option value="">Select a brand</option>
                        <option v-for="brand in props.brands" :key="brand.id" :value="brand.id">
                            {{ brand.name }}
                        </option>
                    </select>
                    <InputError :message="form.errors.brand" />
                </div>

                <div class="grid gap-2">
                    <Label for="rate">Rate</Label>
                    <select id="rate" :tabindex="4" autocomplete="rate" v-model="form.rate" class="text-black h-8 rounded-lg px-1">
                        <option value="">Select a rate</option>
                        <option v-for="rate in props.rates" :key="rate.id" :value="rate.id">
                            {{ rate.rate }} 
                        </option>
                    </select>
                    <InputError :message="form.errors.rate" />
                </div>

                <div class="grid gap-2">
                    <Label for="currency">Currency</Label>
                    <select id="currency" :tabindex="5" autocomplete="currency" v-model="form.currency" class="text-black h-8 rounded-lg px-1">
                        <option value="">Select a currency</option>
                        <option v-for="currency in props.currencies" :key="currency.id" :value="currency.id">
                            {{ currency.code }} - {{ currency.name }}
                        </option>
                    </select>
                    <InputError :message="form.errors.currency" />
                </div>

                <div class="grid gap-2">
                    <Label for="counter_currency">Counter Currency</Label>
                    <select id="counter_currency" :tabindex="5" autocomplete="counter_currency" v-model="form.counter_currency" class="text-black h-8 rounded-lg px-1">
                        <option value="">Select a counter currency</option>
                        <option v-for="counter_currency in props.counter_currencies" :key="counter_currency.id" :value="counter_currency.id">
                            {{ counter_currency.code }} - {{ counter_currency.name }}
                        </option>
                    </select>
                    <InputError :message="form.errors.counter_currency" />
                </div>

                <div class="grid gap-2">
                    <Label for="price">Price</Label>
                    <Input id="price" type="number" step="0.01" required :tabindex="6" autocomplete="price" v-model="form.price" placeholder="$100" />
                    <InputError :message="form.errors.price" />
                </div>

                <div class="grid gap-2">
                    <Label for="cost">Cost</Label>
                    <Input id="cost" type="number" step="0.01" required :tabindex="6" autocomplete="cost" v-model="form.cost" placeholder="$100" />
                    <InputError :message="form.errors.cost" />
                </div>

                <div class="grid gap-2">
                    <Label for="quantity">Quantity</Label>
                    <Input
                        id="quantity"
                        type="number"
                        required
                        :tabindex="5"
                        autocomplete="quantity"
                        v-model="form.quantity"
                        placeholder="Quantity"
                    />
                    <InputError :message="form.errors.quantity" />
                </div>

                <div class="grid gap-2">
                    <Label for="bar_code">Bar Code</Label>
                    <Input id="bar_code" type="text" required autofocus :tabindex="7" autocomplete="bar_code" v-model="form.bar_code" placeholder="Bar Code" />
                    <InputError :message="form.errors.bar_code" />
                </div>

                <Button type="submit" class="mt-2 w-full" :tabindex="8" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Create item
                </Button>
            </div>

            </form>
        </div>
    </AppLayout>
</template>
