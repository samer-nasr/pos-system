<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">

       <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex gap-4">
                <!-- Left Panel (25%) -->
                <div class="w-1/4 h-[90vh] rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white p-2 flex flex-col">
                    <!-- Time -->
                    <div class="bg-gray-100 text-black text-lg p-2 mb-2">16:12</div>

                    <!-- Order List -->
                    <div class="flex-1 bg-gray-50 text-black mb-2 rounded p-2 overflow-auto">
                        <!-- Orders will show here -->
                    </div>

                    <!-- Totals -->
                    <div class="grid grid-cols-3 text-sm bg-gray-100 text-black p-2 mb-2">
                        <span>00:00:07</span>
                        <span>Restaurant</span>
                        <span>£0.00</span>
                    </div>

                    <!-- Keypad -->
                    <div class="grid grid-cols-3 gap-1 mb-2">
                        <button class="bg-white text-black py-2">7</button>
                        <button class="bg-white text-black py-2">8</button>
                        <button class="bg-white text-black py-2">9</button>
                        <button class="bg-white text-black py-2">4</button>
                        <button class="bg-white text-black py-2">5</button>
                        <button class="bg-white text-black py-2">6</button>
                        <button class="bg-white text-black py-2">1</button>
                        <button class="bg-white text-black py-2">2</button>
                        <button class="bg-white text-black py-2">3</button>
                        <button class="bg-black text-white py-2">CLEAR</button>
                        <button class="bg-white text-black py-2">0</button>
                        <button class="bg-black text-white py-2">REPEAT</button>
                    </div>
                </div>

                <!-- Middle Panel (50%) -->
                <div class="w-1/2 h-[90vh] rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white p-2 flex flex-col">
                    <!-- Title -->
                    <h1 class="text-center text-2xl font-bold mb-2 text-black">POS System</h1>

                    <!-- Yellow Category Buttons -->
                    <div class="grid grid-cols-3 gap-2 mb-2">
                        <button class="bg-yellow-200 text-black py-2">APERITIFS</button>
                        <button class="bg-yellow-200 text-black py-2">BREAD & OLIVES</button>
                        <button class="bg-yellow-200 text-black py-2">STARTERS</button>

                        <button class="bg-yellow-200 text-black py-2">PIZZA</button>
                        <button class="bg-yellow-200 text-black py-2">CALZONE</button>
                        <button class="bg-yellow-200 text-black py-2">RISOTTO</button>

                        <button class="bg-yellow-200 text-black py-2">GRILLS</button>
                        <button class="bg-yellow-200 text-black py-2">FLATBREAD</button>
                        <button class="bg-yellow-200 text-black py-2">SIDES</button>

                        <button class="bg-yellow-200 text-black py-2">DESSERTS</button>
                        <button class="bg-yellow-200 text-black py-2">MINI DESSERTS</button>
                        <button class="bg-yellow-200 text-black py-2">GELATO</button>

                        <button class="bg-yellow-200 text-black py-2">MARCH SPECIALS</button>
                        <button class="bg-yellow-200 text-black py-2">OLD MENU</button>
                        <button class="bg-yellow-200 text-black py-2">KINGS ONLY</button>
                    </div>

                    <!-- Orange/Black Controls -->
                    <div class="grid grid-cols-3 gap-2 mb-2">
                        <button class="bg-orange-500 text-white py-2">MAINS AWAY</button>
                        <button class="bg-orange-500 text-white py-2">MESSAGES</button>
                        <button class="bg-orange-500 text-white py-2">EXTRAS</button>

                        <button class="bg-black text-white py-2">MAINS AWAY TIME</button>
                        <button class="bg-black text-white py-2">PLATES</button>
                        <button class="bg-black text-white py-2">PRINT CHECK</button>

                        <button class="bg-red-700 text-white py-2">ERROR</button>
                        <button class="bg-green-500 text-white py-2">CORRECT</button>
                        <button class="bg-green-600 text-white py-2">SEND</button>
                    </div>
                </div>

                <!-- Right Panel (25%) -->
                <div class="w-1/4 h-[90vh] rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white p-2 flex flex-col gap-2">
                    <button class="bg-gray-600 text-white py-2">DRINKS</button>
                    <button class="bg-red-600 text-white py-2">FOOD</button>
                    <button class="bg-gray-600 text-white py-2">TAKE AWAY</button>
                    <button class="bg-gray-600 text-white py-2">SET MENUS</button>
                    <button class="bg-white text-black font-bold py-2">PAY</button>
                    <button class="bg-gray-600 text-white py-2">MANAGER PAY</button>
                    <button class="bg-gray-600 text-white py-2">PAID BY APP</button>
                    <button class="bg-gray-600 text-white py-2">LIST</button>
                    <button class="bg-gray-600 text-white py-2">ENTER NAME</button>
                </div>
            </div>
        </div> 
    </AppLayout>
</template>
