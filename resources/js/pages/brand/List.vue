<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'brands', href: '/brands' },
];

const props = defineProps<{
    brands: { name: string; id: number }[];
}>();

const goToCreateBrand = () => {
    router.get(route('brands.create'));
};

const exportBrand = () => {
    window.location.href = route('brands.export');
};

const deleteBrand = (brandId: number): void => {
    if (confirm("Are you sure you want to delete this brand?")) {
        router.delete(route('brands.destroy', brandId), {
            onSuccess: () => {
                alert("Brand deleted successfully!");
            },
            onError: () => {
                alert("Failed to delete the brand.");
            }
        });
    }
};

const editBrand = (brandId: number): void => {
    router.get(route('brands.edit', brandId));
};
</script>

<template>
    <Head title="Brands" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="">
            <h2 class="text-xl font-semibold text-center">Brands</h2>
            <div class="flex justify-between px-4">
                <Button size="sm" class="ms-4 w-40 bg-blue-800 text-white hover:bg-blue-700" @click="goToCreateBrand">
                                    Create Brand
                </Button>
                <Button size="sm" class="ms-4 w-40 bg-blue-800 text-white hover:bg-blue-700" @click="exportBrand">
                                        Export Brands
                </Button>
            </div>
        </div>
        <div class="flex flex-col gap-4 rounded-xl p-4 bg-white shadow-md mt-5">
            <h2 class="text-center text-xl font-semibold mb-4">Brand List</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 uppercase text-sm font-semibold">
                            <th class="border border-gray-200 px-4 py-2 text-left">Name</th>
                            <th class="border border-gray-200 px-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            v-for="brand in props.brands" 
                            :key="brand.id"
                            class="border border-gray-200 hover:bg-gray-50 transition"
                        >
                            <td class="px-4 py-2 text-gray-700">{{ brand.name }}</td>
                            <td class="px-4 py-2 text-center space-x-2  ">
                                <Button variant="destructive" size="sm" @click="deleteBrand(brand.id)">
                                    Delete
                                </Button>
                                <Button variant="secondary" size="sm" @click="editBrand(brand.id)">
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
