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
        title: 'Brands',
        href: '/brands',
    },
];

const props = defineProps<{
    brand : { name: string; id: number};
}>();

const form = useForm({
    name: props.brand.name,
});

const showSuccessAlert = () => {
    Swal.fire({
        title: 'Success!',
        text: 'Brand saved successfully.',
        icon: 'success',
        confirmButtonText: 'OK',
    });
};

const submit = () => {
    form.put(route('brands.update', props.brand), {
        onSuccess: () => {
            showSuccessAlert();
            form.reset('name');
        },
    });
};
</script>

<template>
    <Head title="Brands" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <form @submit.prevent="submit">
                <h2 class="text-center mb-4">Edit brand</h2>
                <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="Item name" />
                    <InputError :message="form.errors.name" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Save brand
                </Button>
            </div>

            </form>
        </div>
    </AppLayout>
</template>
