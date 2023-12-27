<script setup>
import { bool } from "vue-types";
import axios from "axios";
import { notify } from "notiwind";
import { ref } from "vue";

import Dialog from "@/components/Dialog.vue";
import Input from "@/components/Input.vue";

const props = defineProps({
    openDialog: bool().def(false),
    updateAction: bool().def(false),
});

const emit = defineEmits(["close", "success"]);

const closeForm = () => {
    emit("close");
};

const isLoading = ref(false);
const form = ref({});
const formRef = ref(null);

const createData = () => {
    isLoading.value = true;
    axios
        .post(route("admin.village.store"), form.value)
        .then((res) => {
            notify(
                {
                    type: "success",
                    group: "top",
                    text: res.data.meta.message,
                },
                2500
            );
            form.value = {};
            emit("success");
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
    <Dialog
        :showModal="props.openDialog"
        :title="
            updateAction
                ? 'Ubah data desa/kelurahan'
                : 'Tambahkan data desa/kelurahan'
        "
        size="xl"
        @closed="closeForm"
    >
        <template v-slot:close>
            <button
                class="text-slate-400 hover:text-slate-500"
                @click="$emit('close')"
            >
                <div class="sr-only">Close</div>
                <svg class="w-4 h-4 fill-current">
                    <path
                        d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z"
                    />
                </svg>
            </button>
        </template>
        <template v-slot:content>
            <form @submit.prevent="createData" ref="formRef">
                <div>
                    <Input
                        v-model="form.name"
                        label="Nama desa/kelurahan"
                        placeholder="Masukkan nama desa/kelurahan"
                        :required="true"
                    />
                </div>
            </form>
        </template>
        <template v-slot:footer>
            <div class="flex flex-wrap justify-end space-x-4">
                <button
                    @click="closeForm"
                    :disabled="isLoading"
                    class="bg-transparent text-sm border rounded border-gray-400 text-gray-500 px-4 py-2"
                >
                    Batal
                </button>
                <button
                    @click="createData"
                    type="button"
                    :disabled="isLoading"
                    :class="{
                        'bg-purple-300': isLoading,
                    }"
                    class="px-4 text-sm bg-primary py-2 rounded text-white"
                >
                    {{ isLoading ? "Menambahkan..." : "Tambahkan" }}
                </button>
            </div>
        </template>
    </Dialog>
</template>
