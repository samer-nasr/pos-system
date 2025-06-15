<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { LoaderCircle } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import Swal from 'sweetalert2';

const props = defineProps<{
    categories: { name: string; id: number }[];
    brands: { name: string; id: number }[];
    rates: {id: number, currency: string, counter_currency : string}[];
    currencies: {name: string, code: string, id:number}[];
    item: {
        id: number;
        name: string;
        price: number;
        quantity: number;
        category: { id: number, name:string};
        brand: { id: number, name:string};
        bar_code: string;
        rate: { id: number, currency:string , counter_currency : string};
        currency: {id: number, name:string, code:string}
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Items', href: '/items' },
];

// Initialize form with empty values
const form = useForm({
    name: props.item.name,
    price: props.item.price,
    quantity: props.item.quantity,
    category: props.item.category.id,
    brand: props.item.brand.id,
    bar_code: props.item.bar_code,
    rate: props.item.rate.id,
    currency: props.item.currency.id
});

const showSuccessAlert = () => {
    Swal.fire({
        title: 'Success!',
        text: 'Item saved successfully.',
        icon: 'success',
        confirmButtonText: 'OK',
    });
};

const submit = () => {
    form.put(route('items.update', props.item.id), {
        onSuccess: () => {
            showSuccessAlert();
            form.reset('name', 'price', 'quantity', 'category', 'brand');
        },
    });
};
</script>

<template>
    <Head title="Items" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <form @submit.prevent="submit">
                <h2 class="text-center mb-4">Edit item</h2>
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name"
                            v-model="form.name" placeholder="Item name" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="category">Category</Label>
                        <select id="category" :tabindex="2" autocomplete="category" v-model="form.category"
                            class="text-black h-8 rounded-lg px-1">
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
                                    {{ rate.currency }} - {{ rate.counter_currency }}
                            </option>
                        </select>
                        <InputError :message="form.errors.brand" />
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
                        <Label for="price">Price</Label>
                        <Input id="price" type="number" step="0.01" required :tabindex="6" autocomplete="price" v-model="form.price"
                            placeholder="$100" />
                        <InputError :message="form.errors.price" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="quantity">Quantity</Label>
                        <Input id="quantity" type="number" required :tabindex="7" autocomplete="quantity"
                            v-model="form.quantity" placeholder="Quantity" />
                        <InputError :message="form.errors.quantity" />
                    </div>

                     <div class="grid gap-2">
                        <Label for="bar_code">Bar Code</Label>
                        <Input id="bar_code" type="text" required autofocus :tabindex="8" autocomplete="bar_code" v-model="form.bar_code" placeholder="Bar Code" />
                        <InputError :message="form.errors.bar_code" />
                    </div>

                    <Button type="submit" class="mt-2 w-full" :tabindex="9" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Save item
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
