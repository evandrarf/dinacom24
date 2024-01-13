<script setup>
import { array, string } from "vue-types";
import { Link } from "@inertiajs/inertia-vue3";

import PieChartIcon from "@/components/icons/PieChartIcon.vue";
import UsersIcon from "@/components/icons/UsersIcon.vue";
import CityIcon from "@/components/icons/CityIcon.vue";
import OfficerIcon from "@/components/icons/OfficerIcon.vue";
import HoldingHandIcon from "@/components/icons/HoldingHandIcon.vue";
import ClipboardCheckIcon from "@/components/icons/ClipboardCheckIcon.vue";

const icons = {
    PieChartIcon,
    UsersIcon,
    CityIcon,
    OfficerIcon,
    HoldingHandIcon,
    ClipboardCheckIcon,
};

const props = defineProps({
    modules: array().def([]),
    app_name: string().def(""),
});

const getUrlPathName = (route) => {
    return new URL(route).pathname;
};
</script>
<template>
    <div class="h-full flex flex-col relative z-10 bg-white shadow w-72 p-8">
        <Link
            :href="route('admin.dashboard.index')"
            class="mb-16 flex items-center gap-4"
        >
            <img src="/assets/logo.svg" alt="Logo Aplikasi" class="w-8 h-8" />
            <h3 class="font-bold text-lg">
                {{ app_name }}
            </h3>
        </Link>
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
                    <span class="">{{ item.text }}</span>
                </Link>
            </div>
        </div>
    </div>
</template>
