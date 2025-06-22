<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Accounts', href: '/accounts' },
];

const props = defineProps<{
    accounts: { name: string; id: number , code: string , type: string}[];
}>();

const goToCreateAccount = () => {
    router.get(route('accounts.create'));
};

// const exportRate = () => {
//     window.location.href = route('rate.export');
// };

const deleteAccount = (accountId: number): void => {
    if (confirm("Are you sure you want to delete this account?")) {
        router.delete(route('accounts.destroy', accountId), {
            onSuccess: () => {
                alert("Account deleted successfully!");
            },
            onError: () => {
                alert("Failed to delete the account.");
            }
        });
    }
};

const editAccount = (accountId: number): void => {
    router.get(route('accounts.edit', accountId));
};
</script>

<template>
    <Head title="Accounts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="">
            <h2 class="text-xl font-semibold text-center">Accounts</h2>
            <div class="flex justify-between px-4">
                <Button size="sm" class="ms-4 w-40 bg-blue-800 text-white hover:bg-blue-700" @click="goToCreateAccount">
                                    Create Account
                </Button>
                <!-- <Button size="sm" class="ms-4 w-40 bg-blue-800 text-white hover:bg-blue-700" @click="exportRate">
                                        Export Rates
                </Button> -->
            </div>
        </div>
        <div class="flex flex-col gap-4 rounded-xl p-4 bg-white shadow-md mt-5">
            <h2 class="text-center text-xl font-semibold mb-4 text-gray-700">Account List</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-300 text-gray-700 uppercase text-sm font-semibold">
                            <th class="border border-gray-200 px-4 py-2 text-left">Name</th>
                            <th class="border border-gray-200 px-4 py-2 text-left">Code</th>
                            <th class="border border-gray-200 px-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            v-for="account in props.accounts" 
                            :key="account.id"
                            class="border border-gray-200 hover:bg-gray-50 transition odd:bg-white even:bg-gray-200"
                        >
                            <td class="px-4 py-2 text-gray-700">{{ account.name }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ account.code }}</td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <Button variant="destructive" size="sm" @click="deleteAccount(account.id)">
                                    Delete
                                </Button>
                                <Button variant="secondary" size="sm" @click="editAccount(account.id)">
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
