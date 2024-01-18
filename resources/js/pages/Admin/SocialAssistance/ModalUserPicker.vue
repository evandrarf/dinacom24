<script setup>
import { array, bool, number, object } from "vue-types";
import axios from "axios";
import { notify } from "notiwind";
import { ref, watch, onMounted, computed } from "vue";

import Dialog from "@/components/Dialog.vue";
import Input from "@/components/Input.vue";
import Select from "@/components/Select.vue";
import DataTable from "@/components/DataTable.vue";
import formatCurrency from "@/composables/formatCurrency.js";
import Pagination from "@/components/Pagination.vue";
import Loading from "@/components/Loading.vue";

const props = defineProps({
    openDialog: bool().def(false),
    updateAction: bool().def(false),
    user_village: object().def({}),
    modelValue: array().def([]),
    social_assistance_id: number().def(null),
    isEdit: bool().def(false),
});

const emit = defineEmits(["close", "save"]);

const heads = [
    "No. KK",
    "Pendapatan Perbulan",
    "Jumlah Tanggungan",
    "Skor Kesejahteraan",
];

const isLoading = ref(true);
const data = ref({});
const selectedData = ref([]);
const formRef = ref(null);
const isCheckedAll = ref(false);
const pagination = ref({});

const getData = (page) => {
    axios
        .get(route("admin.resident.get-data"), {
            params: {
                page,
                eligibility_status: "eligible",
                per_page: 6,
                village_id: props.user_village?.id ?? null,
                sort_by: props.isEdit ? "selected" : null,
                social_assistance_id: props.social_assistance_id ?? null,
            },
        })
        .then((res) => {
            data.value = res.data.data;
            pagination.value = res.data.meta.pagination;
            isCheckedAll.value = data.value.every((item) =>
                selectedData.value.includes(item.id)
            );
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
        .finally(() => (isLoading.value = false));
};

const handleChangePage = (page) => {
    if (page === pagination.value.current_page) return;
    isLoading.value = true;
    getData(page);
};

const handleSelectAllData = (e) => {
    if (e) {
        selectedData.value = [
            ...selectedData.value,
            ...data.value.map((item) => item.id),
        ];
        isCheckedAll.value = true;
    } else {
        selectedData.value = selectedData.value.filter(
            (item) => !data.value.map((item) => item.id).includes(item)
        );
        isCheckedAll.value = false;
    }
};

const handleSelectData = (id, e) => {
    if (e) {
        selectedData.value = [...selectedData.value, id];
    } else {
        selectedData.value = selectedData.value.filter((item) => item !== id);
    }
    isCheckedAll.value = data.value.every((item) =>
        selectedData.value.includes(item.id)
    );
};

const closeForm = () => {
    selectedData.value = [];
    isCheckedAll.value = false;
    emit("close");
};

const clearSelectedData = () => {
    selectedData.value = [];
    isCheckedAll.value = false;
};

const saveData = () => {
    emit("save", selectedData.value);
};

onMounted(() => {
    getData(1);
});

watch(
    () => props.modelValue,
    (value) => {
        if (value.length !== 0) {
            selectedData.value = value;
            isCheckedAll.value = value.every(
                (item) => !selectedData.value.includes(item.id)
            );
        }
    }
);
</script>
<template>
    <Dialog
        :showModal="props.openDialog"
        title="Pilih Penerima Bantuan"
        size="5xl"
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
            <DataTable
                :heads="heads"
                @select-all="handleSelectAllData"
                :isLoading="isLoading"
                :isEmpty="data.length === 0"
                class="text-sm h-12 overflow-y-auto"
                :isCheckedAll="isCheckedAll"
            >
                <tr v-if="isLoading" class="bg-white border-b hover:bg-gray-50">
                    <td :colspan="heads.length + 1" class="h-[100%]">
                        <Loading />
                    </td>
                </tr>
                <tr v-else-if="!isLoading && data.length === 0">
                    <td :colspan="heads.length + 1" class="w-full">
                        <div class="flex flex-col justify-center items-center">
                            <Empty />
                            <span class="mt-6">Tidak ada data</span>
                        </div>
                    </td>
                </tr>
                <tr
                    v-else
                    v-for="(item, index) in data"
                    :key="index"
                    class="text-sm"
                >
                    <td class="w-4 p-4">
                        <div class="flex items-center">
                            <input
                                id="checkbox-table-search-1"
                                type="checkbox"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                :checked="selectedData.includes(item.id)"
                                @change="
                                    (e) =>
                                        handleSelectData(
                                            item.id,
                                            e.target.checked
                                        )
                                "
                            />
                            <label for="checkbox-table-search-1" class="sr-only"
                                >checkbox</label
                            >
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        {{ item.family_card_number }}
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
                    @click="clearSelectedData"
                    :disabled="isLoading"
                    class="bg-transparent text-sm border rounded border-red-400 text-red-500 px-4 py-2"
                >
                    Kosongkan
                </button>
                <button
                    @click="saveData"
                    type="button"
                    class="px-4 text-sm bg-primary py-2 rounded text-white"
                >
                    Simpan
                </button>
            </div>
        </template>
    </Dialog>
</template>
