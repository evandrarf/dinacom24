<script setup>
import { object, array, bool } from "vue-types";
import UserDropdown from "./UserDropdown.vue";

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
        <UserDropdown :user="user" :user_role="user_role">
            <template v-slot:logout-button>
                <button
                    @click="$emit('logout')"
                    :disabled="logoutLoading"
                    :class="[
                        logoutLoading ? 'text-gray-500' : 'text-red-600',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                    ]"
                >
                    {{ logoutLoading ? "Loading..." : "Keluar" }}
                </button>
            </template>
        </UserDropdown>
    </div>
</template>
