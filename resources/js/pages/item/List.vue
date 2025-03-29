<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Items', href: '/Items' },
];

const props = defineProps<{
    items: 
    {
        brand: any;category: any; name: string; id: number; price: number; quantity: number
    }[];
}>();

const goToCreateItem = () => {
    router.get(route('items.create'));
};

const deleteItem = (itemsId: number): void => {
    if (confirm("Are you sure you want to delete this item?")) {
        router.delete(route('items.destroy', itemsId), {
            onSuccess: () => {
                alert("item deleted successfully!");
            },
            onError: (error: unknown) => {
                alert("Failed to delete the item.");
            }
        });
    }
};

const editItem = (itemId: number): void => {
    router.get(route('items.edit' , itemId));
}
</script>

<template>
    <Head title="Items" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="">
            <h2 class="text-xl font-semibold text-center">Items</h2>
            <Button size="sm" class="ms-4 w-40 bg-blue-800 text-white hover:bg-blue-700" @click="goToCreateItem">
                                    Create Item
            </Button>
        </div>
        <div class="flex flex-col gap-4 rounded-xl p-4 bg-white shadow-md mt-5">
            <h2 class="text-center text-xl font-semibold mb-4">Items List</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 uppercase text-sm font-semibold">
                            <th class="border border-gray-200 px-4 py-2 text-left">Name</th>
                            <th class="border border-gray-200 px-4 py-2 text-left">Price</th>
                            <th class="border border-gray-200 px-4 py-2 text-left">Quantity</th>
                            <th class="border border-gray-200 px-4 py-2 text-left">Category</th>
                            <th class="border border-gray-200 px-4 py-2 text-left">Brand</th>
                            <th class="border border-gray-200 px-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            v-for="item in props.items" 
                            :key="item.id"
                            class="border border-gray-200 hover:bg-gray-50 transition"
                        >
                            <td class="px-4 py-2 text-gray-700">{{ item.name }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ item.price }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ item.quantity }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ item.category.name }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ item.brand.name }}</td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <Button variant="destructive" size="sm" @click="deleteItem(item.id)">
                                    Delete
                                </Button>
                                <Button variant="secondary" size="sm" @click="editItem(item.id)">
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
