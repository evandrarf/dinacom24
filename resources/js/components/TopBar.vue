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
        class="w-full bg-white px-16 py-4 h-16 top-0 flex items-center justify-end"
    >
        <UserPopover :user="user" :user_role="user_role">
            <div class="flex flex-col items-start px-6 py-4 gap-4">
                <span class="text-primary font-semibold">
                    {{ user_role[0] }}
                </span>
                <button
                    class="rounded text-white font-medium"
                    @click="$emit('logout')"
                    :disabled="logoutLoading"
                    :class="{
                        'text-gray-300 cursor-not-allowed': logoutLoading,
                        'text-red-600': !logoutLoading,
                    }"
                >
                    {{ logoutLoading ? "Loading..." : "Keluar" }}
                </button>
            </div>
        </UserPopover>
    </div>
</template>
