<script>
export default {
    layout: AdminDashboardLayout,
};
</script>
<script setup>
import { ref } from "vue";
import { object } from "vue-types";
import axios from "axios";
import { notify } from "notiwind";
import { addMonths, addWeeks } from "date-fns";

import AdminDashboardLayout from "@/layouts/AdminDashboardLayout.vue";
import Input from "@/components/Input.vue";
import Select from "@/components/Select.vue";
import TextArea from "@/components/TextArea.vue";
import ModalUserPicker from "./ModalUserPicker.vue";

const presetDates = ref([
    { label: "1 Hari", value: [new Date(), new Date()] },

    {
        label: "1 Minggu",
        value: [new Date(), addWeeks(new Date(), 1)],
    },
    {
        label: "1 Bulan",
        value: [new Date(), addMonths(new Date(), 1)],
    },
]);

const props = defineProps({
    additional: object().def({}),
    user_village: object().def({}),
});

const form = ref({});
const isLoading = ref(false);
const date = ref([new Date(), new Date()]);
const openDialog = ref(false);
const selectedRecipient = ref([]);

const createData = () => {
    isLoading.value = true;
    axios
        .post(route("admin.social-assistance.store"), {
            ...form.value,
            start_datetime: date.value[0],
            end_datetime: date.value[1],
            amount_per_kk: Number(form.value.amount_per_kk.replaceAll(".", "")),
        })
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

const handleOpenModal = () => {
    openDialog.value = true;
    selectedRecipient.value = form.value.resident_ids;
};

const handleSaveRecipient = (data) => {
    form.value.resident_ids = data;
    openDialog.value = false;
    selectedRecipient.value = [];
};

const handleCloseUserPicker = (data) => {
    openDialog.value = false;
    selectedRecipient.value = [];
};
</script>
<template>
    <div class="w-full flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Tambah Data Bantuan Sosial</h1>
    </div>
    <div>
        <form
            @submit.prevent="createData"
            class="w-full rounded bg-white min-h-56 mt-8 grid grid-cols-2 gap-4 p-4 mb-16"
        >
            <Input
                label="Nama/Judul Bantuan Sosial"
                placeholder="Isikan nama/judul bantuan sosial"
                v-model="form.name"
                :required="true"
                type="text"
            />
            <Input
                label="Jumlah Bantuan Per Keluarga"
                placeholder="100.000"
                type="currency"
                :required="true"
                v-model="form.amount_per_kk"
            />
            <div class="col-span-2">
                <TextArea
                    @input="(val) => (form.description = val)"
                    label="Deskripsi Bantuan Sosial"
                    placeholder="Isikan deskripsi bantuan sosial"
                    v-model="form.description"
                    resize="vertical"
                />
            </div>
            <div>
                <p class="text-gray-500 text-sm mb-4">
                    Jadwal Bantuan Sosial (Tanggal & Waktu)
                    <span class="text-rose-500">*</span>
                </p>
                <VueDatePicker
                    v-model="date"
                    range
                    :preset-dates="presetDates"
                    :enable-time-picker="true"
                    :clearable="false"
                >
                    <template
                        #preset-date-range-button="{ label, value, presetDate }"
                    >
                        <span
                            role="button"
                            :tabindex="0"
                            @click="presetDate(value)"
                            @keyup.enter.prevent="presetDate(value)"
                            @keyup.space.prevent="presetDate(value)"
                        >
                            {{ label }}
                        </span>
                    </template>
                </VueDatePicker>
            </div>
            <Select
                label="Status Bantuan Sosial"
                placeholder="Pilih Status Bantuan Sosial"
                v-model="form.status"
                :required="true"
                :options="{
                    active: 'Publikasikan',
                    draft: 'Simpan (draft)',
                }"
                :clearable="false"
                class="w-full"
            />
            <div class="mt-4 flex flex-col gap-4">
                <h3 class="font-semibold">Pilih Penerima Bantuan</h3>
                <div class="flex items-center gap-8">
                    <button
                        type="button"
                        @click="handleOpenModal"
                        class="text-primary border border-primary rounded font-medium px-6 py-2"
                    >
                        Pilih
                    </button>
                    <div>
                        <p>
                            {{
                                form.resident_ids ? form.resident_ids.length : 0
                            }}
                            Orang dipilih
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-span-2 flex justify-end mt-8">
                <button
                    type="submit"
                    :disabled="isLoading"
                    class="text-white text-sm bg-primary rounded-md px-4 py-2 disabled:cursor-not-allowed disabled:bg-purple-300"
                >
                    {{ isLoading ? "Menyimpan..." : "Simpan" }}
                </button>
            </div>
        </form>
    </div>
    <ModalUserPicker
        @save="handleSaveRecipient"
        @close="handleCloseUserPicker"
        :modelValue="selectedRecipient"
        :user_village="user_village"
        :openDialog="openDialog"
    />
</template>
