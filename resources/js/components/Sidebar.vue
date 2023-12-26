<script setup>
import { array } from "vue-types";
import { Link } from "@inertiajs/inertia-vue3";

import PieChartIcon from "@/components/icons/PieChartIcon.vue";
import UsersIcon from "@/components/icons/UsersIcon.vue";

const icons = {
    PieChartIcon,
    UsersIcon,
};

const props = defineProps({
    modules: array().def([]),
});

const getUrlPathName = (route) => {
    return new URL(route).pathname;
};
</script>
<template>
    <div class="h-full flex flex-col relative z-10 bg-white shadow-lg w-64 p-8">
        <div class="mb-16">
            <h3 class="font-bold text-2xl">BANSOSKUH</h3>
        </div>
        <div class="flex flex-col gap-6">
            <div
                v-for="(item, index) in modules"
                :key="index"
                class="px-4 py-3 transition-all"
                :class="[
                    getUrlPathName(item.url) === $page.url
                        ? 'text-white bg-primary rounded-lg shadow-lg'
                        : 'text-gray-400',
                ]"
            >
                <Link :href="item.url" class="flex items-center gap-4">
                    <component :is="icons[item.icon]" class="w-6 h-6" />
                    <span class="font-medium">{{ item.text }}</span>
                </Link>
            </div>
        </div>
    </div>
</template>
