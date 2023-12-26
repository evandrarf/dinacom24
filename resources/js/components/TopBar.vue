<script setup>
import { object, array, bool } from "vue-types";
import UserPopover from "./UserPopover.vue";

const props = defineProps({
    user: object().def({}),
    user_role: array().def(null),
    logoutLoading: bool().def(false),
});

const emit = defineEmits(["logout"]);
</script>
<template>
    <div
        class="w-full bg-white px-16 py-4 h-16 shadow-[0_1px_3px_0px_rgba(0,0,0,0.3)] top-0 flex items-center justify-end"
    >
        <UserPopover :user="user" :user_role="user_role">
            <div class="flex flex-col px-6 py-4 gap-4">
                <span class="text-primary font-semibold">
                    {{ user_role[0] }}
                </span>
                <button
                    class="rounded px-3 text-white py-1"
                    @click="$emit('logout')"
                    :disabled="logoutLoading"
                    :class="{
                        'bg-red-300 cursor-not-allowed': logoutLoading,
                        'bg-red-600': !logoutLoading,
                    }"
                >
                    {{ logoutLoading ? "Loading..." : "Keluar" }}
                </button>
            </div>
        </UserPopover>
    </div>
</template>
