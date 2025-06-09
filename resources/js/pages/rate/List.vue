<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Rates', href: '/rates' },
];

const props = defineProps<{
    rates: { currency: string; id: number , counter_currency: string , rate: number}[];
}>();

const goToCreateRate = () => {
    router.get(route('rates.create'));
};

// const exportRate = () => {
//     window.location.href = route('rate.export');
// };

const deleteRate = (rateId: number): void => {
    if (confirm("Are you sure you want to delete this rate?")) {
        router.delete(route('rate.destroy', rateId), {
            onSuccess: () => {
                alert("rate deleted successfully!");
            },
            onError: () => {
                alert("Failed to delete the rate.");
            }
        });
    }
};

const editRate = (rateId: number): void => {
    router.get(route('rates.edit', rateId));
};
</script>

<template>
    <Head title="Rates" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="">
            <h2 class="text-xl font-semibold text-center">Rates</h2>
            <div class="flex justify-between px-4">
                <Button size="sm" class="ms-4 w-40 bg-blue-800 text-white hover:bg-blue-700" @click="goToCreateRate">
                                    Create Rate
                </Button>
                <!-- <Button size="sm" class="ms-4 w-40 bg-blue-800 text-white hover:bg-blue-700" @click="exportRate">
                                        Export Rates
                </Button> -->
            </div>
        </div>
        <div class="flex flex-col gap-4 rounded-xl p-4 bg-white shadow-md mt-5">
            <h2 class="text-center text-xl font-semibold mb-4 text-gray-700">Rate List</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-300 text-gray-700 uppercase text-sm font-semibold">
                            <th class="border border-gray-200 px-4 py-2 text-left">Currency</th>
                            <th class="border border-gray-200 px-4 py-2 text-left">Counter Currency</th>
                            <th class="border border-gray-200 px-4 py-2 text-left">Rate</th>
                            <th class="border border-gray-200 px-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            v-for="rate in props.rates" 
                            :key="rate.id"
                            class="border border-gray-200 hover:bg-gray-50 transition odd:bg-white even:bg-gray-200"
                        >
                            <td class="px-4 py-2 text-gray-700">{{ rate.currency }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ rate.counter_currency }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ rate.rate }}</td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <Button variant="destructive" size="sm" @click="deleteRate(rate.id)">
                                    Delete
                                </Button>
                                <Button variant="secondary" size="sm" @click="editRate(rate.id)">
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
