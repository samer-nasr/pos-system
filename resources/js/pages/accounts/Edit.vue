<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head , useForm} from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { LoaderCircle } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import Swal from 'sweetalert2';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Accounts',
        href: '/accounts',
    },
];

const props = defineProps<{
    account: { name: string; id: number , code: string};
    account_info : {balance: number , total_credits: number , total_debits: number};
}>();

const account_info = computed(() => {
     return props.account_info ?? {
        balance: 0,
        total_credits: 0,
        total_debits: 0
      }; 
});

const form = useForm({
    code: props.account.code,
    name: props.account.name,
});

const showSuccessAlert = () => {
    Swal.fire({
        title: 'Success!',
        text: 'Account saved successfully.',
        icon: 'success',
        confirmButtonText: 'OK',
    });
};

const submit = () => {
    form.put(route('accounts.update', props.account.id), {
        onSuccess: () => {
            showSuccessAlert();
            form.reset('name', 'code');
        },
    });
};
</script>

<template>
    <Head title="Accounts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <form @submit.prevent="submit">
                <h2 class="text-center mb-4">Edit account</h2>
                <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="Name" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="code">Code</Label>
                    <Input id="code" type="text" required autofocus :tabindex="1" autocomplete="code" v-model="form.code" placeholder="Code" />
                    <InputError :message="form.errors.code" />
                </div>

                <div class="grid gap-2">
                    <Label for="Amount">Total Credits</Label>
                    <Input id="Amount" v-model:model-value="account_info.total_credits" type="text" required autofocus :tabindex="1" autocomplete="Amount" readonly placeholder="Amount" />
                    <!-- <InputError :message="form.errors.code" /> -->
                </div>

                <div class="grid gap-2">
                    <Label for="Amount">Total Debits</Label>
                    <Input id="Amount" v-model:model-value="account_info.total_debits" type="text" required autofocus :tabindex="1" autocomplete="Amount" readonly placeholder="Amount" />
                    <!-- <InputError :message="form.errors.code" /> -->
                </div>

                <div class="grid gap-2">
                    <Label for="Amount">Balance</Label>
                    <Input id="Amount" v-model:model-value="account_info.balance" type="text" required autofocus :tabindex="1" autocomplete="Amount" readonly placeholder="Amount" />
                    <!-- <InputError :message="form.errors.code" /> -->
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Save account
                </Button>
            </div>

            </form>
        </div>
    </AppLayout>
</template>
