<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head , useForm, router  } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Currency, LoaderCircle } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import Swal from 'sweetalert2';
import { ref ,type Ref} from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Rates',
        href: '/rates',
    },
];

const form = useForm({
    currency: '',
    counter_currency: '',
    rate: '',
});

// const file: Ref<File | null> = ref(null);

// const handleFileChange = (event: Event) => {
//     const input = event.target as HTMLInputElement; // Type assertion
//     if (input?.files?.[0]) {
//         file.value = input.files[0];
//     }
// };

const showSuccessAlert = () => {
    Swal.fire({
        title: 'Success!',
        text: 'Rate created successfully.',
        icon: 'success',
        confirmButtonText: 'OK',
    });
};

// const importCategories = () => {
//     if(!file.value) return alert("Please select a file");

//     let formData = new FormData();
//     formData.append('file' , file.value);

//     router.post(route('category.import'), formData ,{
//         onSuccess: () => {
//             showSuccessAlert();
//             file.value = null;
//         },
//         onError: (errors) => {
//         console.error(errors);
//         },
//     });
// }

const submit = () => {
    form.post(route('rates.store'), {
        onSuccess: () => {
            showSuccessAlert();
            form.reset('currency' , 'counter_currency', 'rate');
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
                    <Input id="currency" type="text" required autofocus :tabindex="1" autocomplete="currency" v-model="form.currency" placeholder="Currency" />
                    <InputError :message="form.errors.currency" />
                </div>

                 <div class="grid gap-2">
                    <Label for="counter_currency">Counter currency</Label>
                    <Input id="counter_currency" type="text" required autofocus :tabindex="2" autocomplete="counter_currency" v-model="form.counter_currency" placeholder="Counter currency" />
                    <InputError :message="form.errors.counter_currency" />
                </div>

                 <div class="grid gap-2">
                    <Label for="rate">Rate</Label>
                    <Input id="rate" type="number" required autofocus :tabindex="1" autocomplete="rate" v-model="form.rate" placeholder="Rate" />
                    <InputError :message="form.errors.rate" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Create rate
                </Button>
            </div>

            </form>

            <!-- <form @submit.prevent="importCategories" enctype="multipart/form-data">
                <div class="mt-10 flex flex-col  items-center gap-4 border-2 border-white py-8">
                        <h2 class="text-center text-xl font-semibold mb-4">Import Excel</h2>
                        <input @change="handleFileChange"  type="file" ref="file" class="border-2 border-gray-300 rounded p-2 w-64" />
                        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Import Categories
                        </button>
                </div>
            </form> -->

        </div>
    </AppLayout>
</template>
