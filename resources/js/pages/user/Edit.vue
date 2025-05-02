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
        title: 'Users',
        href: '/users',
    },
];

const props = defineProps<{
    user: { name: string; email: string , id: number };
}>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
});

const showAlert = (message: any , type: any) => {
    Swal.fire({
        title: 'Success!',
        text: message,
        icon: type,
        confirmButtonText: 'OK',
    });
};

const submit = () => {
    if(form.password !== form.password_confirmation) 
    {
        showAlert('Password and confirm password do not match. Try again' , 'error');
        form.reset('password', 'password_confirmation');
    } 
    else
    {
        form.put(route('users.update', props.user), {
            onSuccess: () => {
                showAlert('User updated successfully.' , 'success');
                form.reset('name');
            },
        });
    }
};
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <form @submit.prevent="submit">
                <h2 class="text-center mb-4">Edit user</h2>
                <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="User name" />
                    <InputError :message="form.errors.name" />
                </div>
                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input id="email" type="text" required autofocus :tabindex="2" autocomplete="email" v-model="form.email" placeholder="User email" />
                    <InputError :message="form.errors.email" />
                </div>
                    
                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input id="password" type="password"  autofocus :tabindex="3" autocomplete="password" v-model="form.password" placeholder="User password" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="confirm_password">Confirm password</Label>
                    <Input id="confirm_password" type="password"  autofocus :tabindex="4" autocomplete="confirm_password" v-model="form.password_confirmation" placeholder="Confirm password" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Save User
                </Button>
            </div>

            </form>
        </div>
    </AppLayout>
</template>
