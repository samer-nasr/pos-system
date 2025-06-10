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
        title: 'Currencies',
        href: '/currencies',
    },
];

const props = defineProps<{
    currency: { name: string; id: number , code: string};
}>();

const form = useForm({
    code: props.currency.code,
    name: props.currency.name,
});

const showSuccessAlert = () => {
    Swal.fire({
        title: 'Success!',
        text: 'Currency saved successfully.',
        icon: 'success',
        confirmButtonText: 'OK',
    });
};

const submit = () => {
    form.put(route('currencies.update', props.currency.id), {
        onSuccess: () => {
            showSuccessAlert();
            form.reset('name', 'code');
        },
    });
};
</script>

<template>
    <Head title="Currencies" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <form @submit.prevent="submit">
                <h2 class="text-center mb-4">Create new currency</h2>
                <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="Name" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="counter_currency">Code</Label>
                    <Input id="code" type="text" required autofocus :tabindex="1" autocomplete="code" v-model="form.code" placeholder="Code" />
                    <InputError :message="form.errors.code" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Save currency
                </Button>
            </div>

            </form>
        </div>
    </AppLayout>
</template>
