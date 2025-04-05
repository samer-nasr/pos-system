<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: '/users' },
];

const props = defineProps<{
    users: { name: string; id: number }[];
}>();

const goToCreateUser = () => {
    router.get(route('users.create'));
};

const exportUser = () => {
    window.location.href = route('users.export');
};

const deleteUser = (userId: number): void => {
    if (confirm("Are you sure you want to delete this user?")) {
        router.delete(route('users.destroy', userId), {
            onSuccess: () => {
                alert("User deleted successfully!");
            },
            onError: () => {
                alert("Failed to delete the user.");
            }
        });
    }
};

const editUser = (userId: number): void => {
    router.get(route('users.edit', userId));
};
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="">
            <h2 class="text-xl font-semibold text-center">Users</h2>
            <div class="flex justify-between px-4">
                <Button size="sm" class="ms-4 w-40 bg-blue-800 text-white hover:bg-blue-700" @click="goToCreateUser">
                                    Create User
                </Button>
                <Button size="sm" class="ms-4 w-40 bg-blue-800 text-white hover:bg-blue-700" @click="exportUser">
                                        Export Users
                </Button>
            </div>
        </div>
        <div class="flex flex-col gap-4 rounded-xl p-4 bg-white shadow-md mt-5">
            <h2 class="text-center text-xl font-semibold mb-4 text-gray-700">User List</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-300 text-gray-700 uppercase text-sm font-semibold">
                            <th class="border border-gray-200 px-4 py-2 text-left">Name</th>
                            <th class="border border-gray-200 px-4 py-2 text-center">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            v-for="user in props.users" 
                            :key="user.id"
                            class="border border-gray-200 hover:bg-gray-50 transition odd:bg-white even:bg-gray-200"
                        >
                            <td class="px-4 py-2 text-gray-700">{{ user.name }}</td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <Button variant="destructive" size="sm" @click="deleteUser(user.id)">
                                    Delete
                                </Button>
                                <Button variant="secondary" size="sm" @click="editUser(user.id)">
                                    Edit
                                </Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
