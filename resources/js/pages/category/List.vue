<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Categories', href: '/Categories' },
];

const props = defineProps<{
    categories: { name: string; id: number }[];
}>();

const goToCreateCategory = () => {
    router.get(route('category.create'));
};

const exportCategory = () => {
    window.location.href = route('category.export');
};

const deleteCategory = (categoryId: number): void => {
    if (confirm("Are you sure you want to delete this category?")) {
        router.delete(route('category.destroy', categoryId), {
            onSuccess: () => {
                alert("Category deleted successfully!");
            },
            onError: () => {
                alert("Failed to delete the category.");
            }
        });
    }
};

const editCategory = (categoryId: number): void => {
    router.get(route('category.edit', categoryId));
};
</script>

<template>
    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="">
            <h2 class="text-xl font-semibold text-center">Categories</h2>
            <div class="flex justify-between px-4">
                <Button size="sm" class="ms-4 w-40 bg-blue-800 text-white hover:bg-blue-700" @click="goToCreateCategory">
                                    Create Category
                </Button>
                <Button size="sm" class="ms-4 w-40 bg-blue-800 text-white hover:bg-blue-700" @click="exportCategory">
                                        Export Categories
                </Button>
            </div>
        </div>
        <div class="flex flex-col gap-4 rounded-xl p-4 bg-white shadow-md mt-5">
            <h2 class="text-center text-xl font-semibold mb-4 text-gray-700">Category List</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-300 text-gray-700 uppercase text-sm font-semibold">
                            <th class="border border-gray-200 px-4 py-2 text-left">Name</th>
                            <th class="border border-gray-200 px-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            v-for="category in props.categories" 
                            :key="category.id"
                            class="border border-gray-200 hover:bg-gray-50 transition odd:bg-white even:bg-gray-200"
                        >
                            <td class="px-4 py-2 text-gray-700">{{ category.name }}</td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <Button variant="destructive" size="sm" @click="deleteCategory(category.id)">
                                    Delete
                                </Button>
                                <Button variant="secondary" size="sm" @click="editCategory(category.id)">
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
