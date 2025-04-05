<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head , router, useForm} from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { LoaderCircle } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import Swal from 'sweetalert2';
import { Ref, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Brands',
        href: '/brands',
    },
];

const form = useForm({
    name: '',
});

const showSuccessAlert = () => {
    Swal.fire({
        title: 'Success!',
        text: 'Brand created successfully.',
        icon: 'success',
        confirmButtonText: 'OK',
    });
};

const submit = () => {
    form.post(route('brands.store'), {
        onSuccess: () => {
            showSuccessAlert();
            form.reset('name');
        },
    });
};

const file: Ref<File | null> = ref(null);

const handleFileChange = (event: Event) => {
    const input = event.target as HTMLInputElement; // Type assertion
    if (input?.files?.[0]) {
        file.value = input.files[0];
    }
};

const importBrand = () => {
    if(!file.value) return alert("Please select a file");

    let formData = new FormData();
    formData.append('file' , file.value);

    router.post(route('brands.import'), formData ,{
        onSuccess: () => {
            showSuccessAlert();
            file.value = null;
        },
        onError: (errors) => {
        console.error(errors);
        },
    });
}

</script>

<template>
    <Head title="Brands" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <form @submit.prevent="submit">
                <h2 class="text-center mb-4">Create new brand</h2>
                <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="Item name" />
                    <InputError :message="form.errors.name" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Create brand
                </Button>
            </div>
            </form>

            <form @submit.prevent="importBrand" enctype="multipart/form-data">
                <div class="mt-10 flex flex-col  items-center gap-4 border-2 border-white py-8">
                        <h2 class="text-center text-xl font-semibold mb-4">Import Excel</h2>
                        <input @change="handleFileChange"  type="file" ref="file" class="border-2 border-gray-300 rounded p-2 w-64" />
                        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Import Brands
                        </button>
                </div>
            </form>

        </div>
    </AppLayout>
</template>
