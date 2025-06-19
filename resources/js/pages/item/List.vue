<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Items', href: '/Items' },
];

const props = defineProps<{
    items: {
        data: {
            brand: any;
            category: any;
            name: string;
            id: number;
            price: number;
            quantity: number;
        }[];
        links: {
            url: string | null;
            label: string;
            active: boolean;
        }[];
    };
    filters: {
        search: string | null;
    };
}>();

const search = ref(props.filters.search || '');

watch(search, (value) => {
    router.get(route('items.index'), { search: value }, {
        preserveState: true,
        replace: true,
    });
});

const goToPage = (url: string | null) => {
    if (url) router.visit(url);
}

const goToCreateItem = () => {
    router.get(route('items.create'));
};

const exportItem = () => {
    window.location.href = route('items.export');
}

const deleteItem = (itemsId: number): void => {
    if (confirm("Are you sure you want to delete this item?")) {
        router.delete(route('items.destroy', itemsId), {
            onSuccess: () => {
                alert("item deleted successfully!");
            },
            onError: () => {
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
            <div class="flex justify-between px-4">
                <Button size="sm" class="ms-4 w-40 bg-blue-800 text-white hover:bg-blue-700" @click="goToCreateItem">
                                    Create Item
                </Button>
                <input
                    type="text"
                    v-model="search"
                    placeholder="Search items..."
                    class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring focus:border-blue-300 text-black"
                />
                <Button size="sm" class="ms-4 w-40 bg-blue-800 text-white hover:bg-blue-700" @click="exportItem">
                                        Export Items
                </Button>
            </div>
        </div>
        <div class="flex flex-col gap-4 rounded-xl p-4 bg-white shadow-md mt-5">
            <h2 class="text-center text-xl font-semibold mb-4 text-gray-700">Items List</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-300 text-gray-700 uppercase text-sm font-semibold">
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
                            v-for="item in props.items.data" 
                            :key="item.id"
                            class="border border-gray-200 hover:bg-gray-50 transition odd:bg-white even:bg-gray-200"

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
            <!-- Pagination Links -->
            <div class="mt-4 flex flex-wrap justify-center gap-2">
            <button
                v-for="(link, index) in props.items.links"
                :key="index"
                :disabled="!link.url"
                @click="goToPage(link.url)"
                v-html="link.label"
                :class="[
                'px-3 py-1 border rounded text-sm',
                link.active ? 'bg-blue-600 text-black' : 'bg-white text-black',
                !link.url ? 'text-black cursor-not-allowed' : 'hover:bg-gray-400'
                ]"
            ></button>
            </div>
        </div>
    </AppLayout>
</template>
