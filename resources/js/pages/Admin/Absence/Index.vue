<script>
export default {
    layout: AdminDashboardLayout,
};
</script>
<script setup>
import { ref } from "vue";
import axios from "axios";
import { notify } from "notiwind";

import AdminDashboardLayout from "@/layouts/AdminDashboardLayout.vue";
import QrCodeIcon from "@/components/icons/QrCodeIcon.vue";
import CameraModal from "./CameraModal.vue";
import SearchInput from "@/components/SearchInput.vue";
import Loading from "@/components/Loading.vue";
import formatCurrency from "@/composables/formatCurrency.js";

const openDialog = ref(false);
const data = ref({});
const isLoading = ref(false);
const search = ref("");
const confirmLoading = ref(false);

const closeModal = () => {
    openDialog.value = false;
};

const getDataDetail = (val) => {
    closeModal();
    isLoading.value = true;
    axios
        .post(route("admin.absence.get-detail-recipient"), {
            ticket_number: val,
        })
        .then((res) => {
            data.value = res.data.data;
        })
        .catch((error) => {
            if (error.response.data.message) {
                notify(
                    {
                        type: "error",
                        group: "top",
                        text: error.response.data.message,
                    },
                    2500
                );
            } else {
                notify(
                    {
                        type: "error",
                        group: "top",
                        text: error.response.data.meta.error,
                    },
                    2500
                );
            }
        })
        .finally(() => {
            isLoading.value = false;
        });
};

const absence = () => {
    confirmLoading.value = true;
    axios
        .post(route("admin.absence.confirm-absence"), {
            ticket_number: data.value.ticket_number,
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
            data.value = {};
        })
        .catch((error) => {
            if (error.response.data.message) {
                notify(
                    {
                        type: "error",
                        group: "top",
                        text: error.response.data.message,
                    },
                    2500
                );
            } else {
                notify(
                    {
                        type: "error",
                        group: "top",
                        text: error.response.data.meta.error,
                    },
                    2500
                );
            }
        })
        .finally(() => {
            confirmLoading.value = false;
        });
};

const searchData = () => {
    data.value = {};
    getDataDetail(search.value);
};
</script>
<template>
    <div class="w-full flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Absen Penerimaan Bantuan Sosial</h1>
        <button
            @click="openDialog = true"
            class="text-white text-sm bg-primary flex gap-2 items-center rounded-md px-4 py-2"
        >
            <QrCodeIcon />
            <span> Scan QR code </span>
        </button>
    </div>
    <div class="w-full mt-8 flex gap-4 justify-start items-center">
        <div class="w-1/2">
            <SearchInput
                v-model="search"
                placeholder="Cari dengan nomor tiket"
                @update:modelValue="handleSearch"
            />
        </div>
        <button
            @click="searchData"
            class="text-sm bg-primary text-white px-6 py-2.5 rounded"
        >
            Cari
        </button>
    </div>
    <div
        class="w-full min-h-64 text-sm bg-white my-8 overflow-y-auto flex justify-center items-center"
    >
        <div
            class="w-full h-full grid grid-cols-3 gap-12 p-4"
            v-if="Object.keys(data).length !== 0 && !isLoading"
        >
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Nama Bantuan Sosial</h5>
                <p class="text-gray-600">
                    {{ data.social_assistance?.name }}
                </p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Jumlah Yang Diterima</h5>
                <p class="text-gray-600">
                    Rp{{
                        formatCurrency(data.social_assistance?.amount_per_kk)
                    }}
                </p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">No. KK</h5>
                <p class="text-gray-600">
                    {{ data.resident?.family_card_number }}
                </p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">NIK Kepala Keluarga</h5>
                <p class="text-gray-600">
                    {{ data.resident?.head_of_family_nik }}
                </p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Nama Kepala Keluarga</h5>
                <p class="text-gray-600">
                    {{ data.resident?.head_of_family_name }}
                </p>
            </div>
            <div></div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">File KK</h5>
                <a
                    class="h-64"
                    target="_blank"
                    rel="noopener noreferrer"
                    :href="data.resident?.family_card_file"
                    v-if="data.resident?.family_card_file"
                >
                    <img
                        :src="data.resident?.family_card_file"
                        alt="Kartu Keluarga"
                        class="object-contain h-4/5"
                    />
                </a>
                <p v-else>Belum Upload Foto KK</p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">File KTP</h5>
                <a
                    class="h-64"
                    target="_blank"
                    rel="noopener noreferrer"
                    :href="data.resident?.identity_card_file"
                    v-if="data.resident?.identity_card_file"
                >
                    <img
                        :src="data.resident?.identity_card_file"
                        alt="Kartu Tanda Penduduk"
                        class="object-contain h-4/5"
                    />
                </a>
                <p v-else>Belum Upload Foto KTP</p>
            </div>
            <div
                class="w-full p-4 gap-4 flex justify-end col-span-3"
                v-if="Object.keys(data).length !== 0 && !isLoading"
            >
                <button
                    @click="data = {}"
                    class="border border-red-500 rounded text-red-600 px-6 py-2"
                >
                    Batal
                </button>
                <button
                    @click="absence"
                    :disabled="confirmLoading"
                    class="border border-primary rounded text-primary px-6 py-2 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    Konfirmasi Absensi
                </button>
            </div>
        </div>
        <div
            v-else-if="isLoading"
            class="flex justify-center items-center h-full"
        >
            <Loading />
        </div>
        <div v-else class="flex justify-center items-center h-full">
            <p class="font-medium text-lg">
                Silahkan scan dengan kamera atau cari pada kolom pencarian
            </p>
        </div>
    </div>
    <CameraModal
        @send-data="getDataDetail"
        @close="closeModal"
        :openDialog="openDialog"
    />
</template>
