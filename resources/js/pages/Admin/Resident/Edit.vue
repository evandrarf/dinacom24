<script>
export default {
    layout: AdminDashboardLayout,
};
</script>
<script setup>
import { ref, onMounted } from "vue";
import { integer, object } from "vue-types";
import axios from "axios";
import { notify } from "notiwind";

import AdminDashboardLayout from "@/layouts/AdminDashboardLayout.vue";
import Input from "@/components/Input.vue";
import Select from "@/components/Select.vue";
import formatCurrency from "@/composables/formatCurrency.js";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    id: integer().required,
    additional: object().def({}),
});

const form = ref({});
const isLoading = ref(false);

const getData = () => {
    axios
        .get(route("admin.resident.get-detail-data", props.id))
        .then((res) => {
            form.value = {
                ...res.data.data,
                monthly_income: formatCurrency(res.data.data.monthly_income),
            };
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

const editData = () => {
    isLoading.value = true;
    axios
        .put(route("admin.resident.update", props.id), {
            ...form.value,
            city: "Kota Semarang",
            province: "Jawa Tengah",
            monthly_income: Number(
                form.value.monthly_income?.replaceAll(".", "")
            ),
            dependent_count: Number(form.value.dependent_count),
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
            Inertia.visit(route("admin.resident.show", props.id));
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

onMounted(() => {
    getData();
});
</script>
<template>
    <div class="w-full flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Ubah Data Warga</h1>
    </div>
    <div>
        <form
            v-if="getLoading || Object.keys(form).length > 0"
            @submit.prevent="editData"
            class="w-full rounded bg-white min-h-56 mt-8 grid grid-cols-2 gap-4 p-4 mb-16"
        >
            <Input
                label="No. Kartu Keluarga"
                placeholder="No KK (16 digit)"
                v-model="form.family_card_number"
                type="numeric"
            />
            <Input
                label="No. Induk Kependudukan (Kepala Keluarga)"
                placeholder="NIK (16 digit)"
                type="numeric"
                v-model="form.head_of_family_nik"
            />
            <Input
                label="Nama Kepala Keluarga"
                placeholder="Teguh Supriyanto"
                v-model="form.head_of_family_name"
            />
            <Input
                label="Alamat"
                placeholder="JL. Jendral Sudirman No. 67"
                v-model="form.address"
            />
            <div class="flex gap-3">
                <Input
                    label="RT"
                    min="0"
                    max="99"
                    class="w-full"
                    placeholder="1"
                    v-model="form.rt"
                    type="number"
                />
                <Input
                    label="RW"
                    min="0"
                    max="99"
                    class="w-full"
                    placeholder="12"
                    v-model="form.rw"
                    type="number"
                />
            </div>
            <Input
                label="Kode Pos"
                placeholder="80901 (5 digit)"
                v-model="form.postal_code"
                type="numeric"
            />
            <Select
                label="Kelurahan/Desa"
                placeholder="Pilih kelurahan/desa"
                v-model="form.village_id"
                :options="additional.villages"
                :clearable="false"
                class="w-full"
            />
            <Select
                label="Kecamatan"
                placeholder="Pilih Kecamatan"
                v-model="form.district"
                :options="additional.districts"
                :clearable="false"
                class="w-full"
            />
            <Input
                label="Kota"
                placeholder="Kota Semarang"
                :disabled="true"
                v-model="form.city"
            />
            <Input
                label="Provinsi"
                placeholder="Jawa Tengah"
                :disabled="true"
                v-model="form.province"
            />
            <div class="flex gap-3">
                <Input
                    label="Jumlah Tanggungan"
                    max="999"
                    min="0"
                    class="w-full"
                    placeholder="1"
                    v-model="form.dependent_count"
                    type="number"
                />
                <Input
                    label="Pendapatan Perbulan"
                    placeholder="1.000.000"
                    class="w-full"
                    v-model="form.monthly_income"
                    type="currency"
                />
            </div>
            <Input
                label="No. Handphone"
                placeholder="081234567890"
                v-model="form.phone_number"
                type="numeric"
            />
            <Select
                label="Kepemilikan Rumah"
                placeholder="Pilih Kepemilikan Rumah"
                v-model="form.house_ownership"
                :options="{
                    owned: 'Milik Sendiri',
                    join: 'Menumpang',
                    rented: 'Sewa',
                }"
                :clearable="false"
                class="w-full"
            />
            <Select
                label="Jenis Rumah"
                placeholder="Pilih Jenis Rumah"
                v-model="form.house_type"
                :options="{
                    permanent: 'Permanen',
                    semi_permanent: 'Semi Permanen',
                }"
                :clearable="false"
                class="w-full"
            />
            <div class="col-span-2 flex justify-end mt-8">
                <button
                    type="submit"
                    :disabled="isLoading"
                    class="text-white text-sm bg-primary rounded-md px-4 py-2 disabled:cursor-not-allowed disabled:bg-purple-300"
                >
                    {{ isLoading ? "Mengubah..." : "Ubah" }}
                </button>
            </div>
        </form>
        <div
            v-else
            class="w-full justify-center items-center font-medium text-2xl rounded bg-white min-h-56 mt-8 flex gap-4 p-4 mb-16"
        >
            <h2>Tidak ada data</h2>
        </div>
    </div>
</template>
