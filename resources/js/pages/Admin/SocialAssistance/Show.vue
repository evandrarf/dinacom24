<script>
export default {
    layout: AdminDashboardLayout,
};
</script>
<script setup>
import { ref, onMounted } from "vue";
import { integer } from "vue-types";
import axios from "axios";
import { notify } from "notiwind";
import dayjs from "dayjs";
import "dayjs/locale/id";
import localizedFormat from "dayjs/plugin/localizedFormat";
import { Link } from "@inertiajs/inertia-vue3";

import AdminDashboardLayout from "@/layouts/AdminDashboardLayout.vue";
import formatCurrency from "@/composables/formatCurrency.js";
import Loading from "@/components/Loading.vue";
import Empty from "@/components/icons/Empty.vue";
import EditIcon from "@/components/icons/EditIcon.vue";
import DataTable from "@/components/DataTable.vue";
import Pagination from "@/components/Pagination.vue";

dayjs.locale("id");
dayjs.extend(localizedFormat);

const props = defineProps({
    id: integer().required,
});

const heads = [
    "No. KK",
    "Nama Kepala Keluarga",
    "Pendapatan Perbulan",
    "Jumlah Tanggungan",
    "Skor Kesejahteraan",
];

const data = ref({});
const residents = ref([]);
const isLoading = ref(true);
const pagination = ref({});
const isResidentLoading = ref(true);

const getData = () => {
    axios
        .get(route("admin.social-assistance.get-detail-data", props.id))
        .then((res) => {
            data.value = res.data.data;
        })
        .catch((error) => {
            console.log(error.response);
            notify(
                {
                    type: "error",
                    group: "top",
                    text: error.response.data.meta.error,
                },
                2500
            );
        })
        .finally(() => (isLoading.value = false));
};

const getResidentData = (page = 1) => {
    axios
        .get(route("admin.social-assistance.get-resident-data", props.id), {
            params: {
                page,
            },
        })
        .then((res) => {
            residents.value = res.data.data;
            pagination.value = res.data.meta.pagination;
        })
        .catch((error) => {
            console.log(error);
            notify(
                {
                    type: "error",
                    group: "top",
                    text: error.response.data.meta.error,
                },
                2500
            );
        })
        .finally(() => (isResidentLoading.value = false));
};

const handleChangePage = (page) => {
    if (page === pagination.value.current_page) return;
    isResidentLoading.value = true;
    getResidentData(page);
};

onMounted(() => {
    getData();
    getResidentData();
});
</script>
<template>
    <div class="w-full flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Detail Data Bantuan Sosial</h1>
        <Link
            :href="route('admin.social-assistance.edit', props.id)"
            :disabled="isLoading || Object.keys(data).length === 0"
            class="flex text-sm disabled:cursor-not-allowed disabled:border-gray-400 disabled:text-gray-500 items-center gap-3 border border-primary px-4 py-2 rounded text-primary"
        >
            <EditIcon />
            <span>Ubah Data</span>
        </Link>
    </div>
    <div
        v-if="isLoading"
        class="w-full rounded bg-white min-h-56 text-sm mt-8 flex justify-center items-center p-4 mb-16"
    >
        <Loading />
    </div>
    <div
        v-else-if="!isLoading && Object.keys(data).length === 0"
        class="w-full rounded bg-white min-h-96 mt-8 flex flex-col gap-8 justify-center items-center p-4 mb-16"
    >
        <Empty />
        <span class="mt-6">Tidak ada data</span>
    </div>
    <div v-else class="w-full rounded bg-white min-h-56 text-sm mt-8 p-4 mb-16">
        <h2 class="text-black font-semibold text-xl mb-8">Data Umum</h2>
        <div class="grid grid-cols-3 gap-6">
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Nama Bantuan Sosial</h5>
                <p class="text-gray-600">{{ data.name }}</p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Tanggal Kegiatan</h5>
                <p class="text-gray-600">
                    {{ dayjs(data.start_date).format("LL") }} hingga
                    {{ dayjs(data.end_date).format("LL") }}
                </p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Waktu Pelayanan</h5>
                <p class="text-gray-600">
                    {{ data.start_time }} - {{ data.end_time }}
                </p>
            </div>
            <div class="flex-col col-span-3 flex gap-2">
                <h5 class="font-medium">Deskripsi Bantuan Sosial</h5>
                <p class="text-gray-600">{{ data.description }}</p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Bantuan Per Kepala Keluarga</h5>
                <p class="text-gray-600">
                    Rp{{ formatCurrency(data.amount_per_kk) }}
                </p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Jumlah Total Bantuan</h5>
                <p class="text-gray-600">
                    Rp{{ formatCurrency(data.total_amount) }}
                </p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Jumlah Penerima Bantuan</h5>
                <p class="text-gray-600">
                    {{ formatCurrency(data.recipient_count) }} orang
                </p>
            </div>
        </div>
        <h2 class="text-black font-semibold text-xl mb-8 mt-16">
            Daftar Penerima Bantuan
        </h2>
        <div class="">
            <DataTable
                :heads="heads"
                @select-all="handleSelectAllData"
                :isLoading="isResidentLoading"
                :isEmpty="residents.length === 0"
                class="text-sm h-12 overflow-y-auto"
                :isCheckedAll="isCheckedAll"
                :withSelect="false"
            >
                <tr
                    v-if="isResidentLoading"
                    class="bg-white border-b hover:bg-gray-50"
                >
                    <td :colspan="heads.length + 1" class="h-[100%]">
                        <Loading />
                    </td>
                </tr>
                <tr v-else-if="!isResidentLoading && residents.length === 0">
                    <td :colspan="heads.length + 1" class="w-full">
                        <div class="flex flex-col justify-center items-center">
                            <Empty />
                            <span class="mt-6">Tidak ada data</span>
                        </div>
                    </td>
                </tr>
                <tr
                    v-else
                    v-for="(item, index) in residents"
                    :key="index"
                    class="text-sm"
                >
                    <td class="px-6 py-4">
                        {{ item.family_card_number }}
                    </td>
                    <td class="px-6 py-4">
                        {{ item.head_of_family_name }}
                    </td>
                    <td class="px-6 py-4">
                        Rp{{ formatCurrency(item.monthly_income) }}
                    </td>
                    <td class="px-6 py-4">{{ item.dependent_count }} orang</td>
                    <td class="px-6 py-4">{{ item.score }}</td>
                </tr>
            </DataTable>
            <Pagination
                @update:pagination="handleChangePage"
                :pagination="pagination"
            />
        </div>
    </div>
</template>
