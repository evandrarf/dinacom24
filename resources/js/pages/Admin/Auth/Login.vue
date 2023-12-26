<script>
export default {
    layout: AuthLayout,
};
</script>
<script setup>
import { ref } from "vue";
import axios from "axios";
import { notify } from "notiwind";

import AuthLayout from "@/layouts/AuthLayout.vue";

const form = ref({
    email: "",
    password: "",
});
const isLoading = ref(false);

const login = () => {
    isLoading.value = true;
    axios
        .post(route("admin.auth.login"), form.value)
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
            isLoading.value = false;
        });
};
</script>
<template>
    <form
        @submit.prevent="login"
        class="shadow-lg flex flex-col rounded-2xl bg-white p-12 w-3/4 md:w-1/2 lg:w-1/3"
    >
        <h1 class="text-3xl font-semibold">Login</h1>
        <p class="mt-5 mb-12">
            Selamat datang, silahkan login untuk melanjutkan
        </p>
        <input
            v-model="form.email"
            type="email"
            class="px-3 py-2 my-2 rounded outline-primary border border-gray-300"
            placeholder="Masukan email anda"
        />
        <input
            v-model="form.password"
            type="password"
            class="px-3 py-2 my-2 rounded outline-primary border border-gray-300"
            placeholder="Masukan password"
        />
        <button
            type="submit"
            :disabled="isLoading"
            :class="{
                'bg-purple-300 cursor-not-allowed': isLoading,
            }"
            class="px-3 py-2 mb-4 mt-8 rounded text-white bg-primary"
        >
            {{ isLoading ? "Loading..." : "Login" }}
        </button>
    </form>
</template>
