<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head , useForm, router  } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { LoaderCircle } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import Swal from 'sweetalert2';
import { ref ,type Ref} from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create User',
        href: '/users/create',
    },
];

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const file: Ref<File | null> = ref(null);

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
    } 
    else
    {
        form.post(route('users.store'), {
        onSuccess: () => {
            showAlert('User created successfully.' , 'success');
            form.reset('name', 'email', 'password', 'password_confirmation');
            router.get(route('users.index'));
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
                <h2 class="text-center mb-4">Create new user</h2>
                <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="User name" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input id="email" type="email" required autofocus :tabindex="1" autocomplete="email" v-model="form.email" placeholder="User email" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input id="password" type="password" required autofocus :tabindex="1" autocomplete="password" v-model="form.password" placeholder="User password" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="confirm_password">Confirm password</Label>
                    <Input id="confirm_password" type="password" required autofocus :tabindex="1" autocomplete="confirm_password" v-model="form.password_confirmation" placeholder="Confirm password" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Create user
                </Button>
            </div>

            </form>

        </div>
    </AppLayout>
</template>
