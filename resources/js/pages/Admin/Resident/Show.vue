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

import AdminDashboardLayout from "@/layouts/AdminDashboardLayout.vue";
import formatCurrency from "@/composables/formatCurrency.js";
import Loading from "@/components/Loading.vue";
import Empty from "@/components/icons/Empty.vue";
import EditIcon from "@/components/icons/EditIcon.vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    id: integer().required,
});

const data = ref({});
const isLoading = ref(true);

const dataMapping = {
    houseOwnership: {
        owned: "Pribadi",
        rented: "Sewa",
        join: "Menumpang",
    },
    houseType: {
        semi_permanent: "Semi Permanen",
        permanent: "Permanen",
    },
};

const getData = () => {
    axios
        .get(route("admin.resident.get-detail-data", props.id))
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

onMounted(() => {
    getData();
});
</script>
<template>
    <div class="w-full flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Detail Data Warga</h1>
        <Link
            :href="route('admin.resident.edit', props.id)"
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
                <h5 class="font-medium">No. KK</h5>
                <p class="text-gray-600">{{ data.family_card_number }}</p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">NIK Kepala Keluarga</h5>
                <p class="text-gray-600">{{ data.head_of_family_nik }}</p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Nama Kepala Keluarga</h5>
                <p class="text-gray-600">{{ data.head_of_family_name }}</p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Alamat Lengkap</h5>
                <p class="text-gray-600">{{ data.full_address }}</p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Status Kelayakan</h5>
                <p
                    :class="
                        data.eligibility_status?.toLowerCase() === 'layak'
                            ? 'text-green-500'
                            : 'text-red-600'
                    "
                >
                    {{ data.eligibility_status }}
                </p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Skor Kesejahteraan</h5>
                <p class="text-gray-600">
                    {{ data.score }}
                </p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Status Akun</h5>
                <p
                    :class="
                        data.status === 'active'
                            ? 'text-green-500'
                            : 'text-red-600'
                    "
                >
                    {{ data.status === "active" ? "Aktif" : "Belum Aktif" }}
                </p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Kepemilikan Rumah</h5>
                <p class="text-gray-600">
                    {{ dataMapping.houseOwnership[data.house_ownership] }}
                </p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Tipe Rumah</h5>
                <p class="text-gray-600">
                    {{ dataMapping.houseType[data.house_type] }}
                </p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Penghasilan Perbulan</h5>
                <p class="text-gray-600">
                    Rp {{ formatCurrency(data.monthly_income) }}
                </p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Jumlah Tanggungan Keluarga</h5>
                <p class="text-gray-600">{{ data.dependent_count }} orang</p>
            </div>
            <div class="flex-col flex gap-2">
                <h5 class="font-medium">Nomor HP</h5>
                <p class="text-gray-600">{{ data.phone_number ?? "-" }}</p>
            </div>
        </div>
        <h2 class="text-black font-semibold text-xl my-8">Lampiran</h2>
        <div class="grid grid-cols-2 gap-6">
            <div class="flex-col flex gap-4">
                <h5 class="font-medium">Foto KK</h5>
                <a
                    class="h-96"
                    target="_blank"
                    rel="noopener noreferrer"
                    :href="data.family_card_file"
                    v-if="data.family_card_file"
                >
                    <img
                        :src="data.family_card_file"
                        alt="Kartu Keluarga"
                        class="object-contain h-4/5"
                    />
                </a>
                <p v-else>Belum Upload Foto KK</p>
            </div>
            <div class="flex-col flex gap-4 h-96">
                <h5 class="font-medium">Foto KTP</h5>
                <a
                    class="h-96"
                    target="_blank"
                    rel="noopener noreferrer"
                    :href="data.identity_card_file"
                    v-if="data.identity_card_file"
                >
                    <img
                        :src="data.identity_card_file"
                        alt="Kartu Keluarga"
                        class="object-contain h-4/5"
                    />
                </a>
                <p v-else>Belum Upload Foto KTP</p>
            </div>
        </div>
    </div>
</template>
