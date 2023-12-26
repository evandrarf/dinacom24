<script setup>
import { computed, ref } from "vue";
import { array, object } from "vue-types";
import axios from "axios";
import { notify } from "notiwind";

import Notification from "@/components/Notification.vue";
import Sidebar from "@/components/Sidebar.vue";
import TopBar from "../components/TopBar.vue";

const props = defineProps({
    modules: array().def([]),
    user: object().def({}),
    user_role: array().def(null),
});

const user = computed(() => props.user);
const user_role = computed(() => props.user_role);

const logoutLoading = ref(false);

const logout = () => {
    logoutLoading.value = true;
    axios
        .post(route("admin.auth.logout"))
        .then((res) => {
            notify(
                {
                    type: "success",
                    group: "top",
                    text: res.data.meta.message,
                },
                2500
            );
            window.location.reload();
        })
        .catch((error) => {
            notify(
                {
                    type: "error",
                    group: "top",
                    text: error.response.data.message,
                },
                2500
            );
        })
        .finally(() => {
            logoutLoading.value = false;
        });
};
</script>
<template>
    <Notification></Notification>
    <div class="w-screen h-screen overflow-hidden flex bg-gray-50">
        <Sidebar :modules="modules" />
        <div class="flex flex-col relative grow">
            <TopBar
                :user="user"
                :user_role="user_role"
                @logout="logout"
                :logoutLoading="logoutLoading"
            />
            <main class="p-12">
                <slot />
            </main>
        </div>
    </div>
</template>
