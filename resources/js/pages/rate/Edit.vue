<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head , useForm} from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { LoaderCircle } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Rates',
        href: '/Rates',
    },
];

const props = defineProps<{
    rate: { currency_id: number; id: number , counter_currency_id: number, rate: number};
    currency: { name:string; code:string; id:number}[];
    counter_currency: { name:string; code:string; id:number}[];
}>();

const form = useForm({
    currency: props.rate.currency_id,
    counter_currency: props.rate.counter_currency_id,
    rate: props.rate.rate,
});

const showSuccessAlert = () => {
    Swal.fire({
        title: 'Success!',
        text: 'Rate saved successfully.',
        icon: 'success',
        confirmButtonText: 'OK',
    });
};

const submit = () => {
    form.put(route('rates.update', props.rate.id), {
        onSuccess: () => {
            showSuccessAlert();
            form.reset('currency', 'counter_currency' , 'rate');
        },
    });
};
</script>

<template>
    <Head title="Rates" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <form @submit.prevent="submit">
                <h2 class="text-center mb-4">Create new rate</h2>
                <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="currency">Currency</Label>
                    <select id="currency" :tabindex="4" autocomplete="currency" v-model="form.currency" class="text-black h-8 rounded-lg px-1">
                            <option value=""></option>
                            <option v-for="currency in props.currency" :key="currency.id" :value="currency.id">
                                    {{ currency.name }} 
                            </option>
                    </select>
                    <InputError :message="form.errors.currency" />
                </div>

                <div class="grid gap-2">
                    <Label for="counter_currency">Counter currency</Label>
                    <select id="counter_currency" :tabindex="4" autocomplete="counter_currency" v-model="form.counter_currency" class="text-black h-8 rounded-lg px-1">
                            <option value=""></option>
                            <option v-for="counter_currency in props.counter_currency" :key="counter_currency.id" :value="counter_currency.id">
                                    {{ counter_currency.name }} 
                            </option>
                    </select>
                    <InputError :message="form.errors.counter_currency" />
                </div>

                <div class="grid gap-2">
                    <Label for="rate">Rate</Label>
                    <Input id="rate" type="text" required autofocus :tabindex="1" autocomplete="rate" v-model="form.rate" placeholder="Rate" />
                    <InputError :message="form.errors.rate" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Save rate
                </Button>
            </div>

            </form>
        </div>
    </AppLayout>
</template>
