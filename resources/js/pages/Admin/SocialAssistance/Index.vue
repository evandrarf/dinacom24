<script>
export default {
    layout: AdminDashboardLayout,
};
</script>
<script setup>
import { ref, onMounted, reactive } from "vue";
import axios from "axios";
import { notify } from "notiwind";
import { object } from "vue-types";
import { Link } from "@inertiajs/inertia-vue3";
import dayjs from "dayjs";
import "dayjs/locale/id";
import localizedFormat from "dayjs/plugin/localizedFormat";

import AdminDashboardLayout from "@/layouts/AdminDashboardLayout.vue";
import debounce from "@/composables/debounce";
import Loading from "@/components/Loading.vue";
import SearchInput from "@/components/SearchInput.vue";
import DataTable from "@/components/DataTable.vue";
import Pagination from "@/components/Pagination.vue";
import Empty from "@/components/icons/Empty.vue";
import TrashCanIcon from "@/components/icons/TrashCanIcon.vue";
import EditIcon from "@/components/icons/EditIcon.vue";
import DropdownEditMenu from "@/components/DropdownEditMenu.vue";
import TriangleExclamationIcon from "@/components/icons/TriangleExclamationIcon.vue";
import Alert from "@/components/Alert.vue";
import Filter from "./Filter.vue";
import EyeOpen from "@/components/icons/EyeOpenIcon.vue";
import { Inertia } from "@inertiajs/inertia";
import formatCurrency from "@/composables/formatCurrency.js";

dayjs.locale("id");
dayjs.extend(localizedFormat);

const data = ref([]);
const isLoading = ref(true);
const search = ref("");
const pagination = ref({
    count: "",
    current_page: "",
    per_page: "",
    total: 0,
    total_pages: 1,
});
const selectedData = ref([]);
const itemSelected = ref({});
const openAlert = ref(false);
const openAlertMany = ref(false);
const filter = ref({});

const alertData = reactive({
    headerLabel: "",
    contentLabel: "",
    closeLabel: "",
    submitLabel: "",
});

const props = defineProps({
    additional: object().def({}),
});

const heads = [
    "Nama",
    "Jumlah Bantuan Per Keluarga",
    "Tanggal & Waktu",
    "Total Bantuan",
    "Total Penerima",
    "Status",
    "Aksi",
];

const getData = debounce(async (page = 1) => {
    axios
        .get(route("admin.social-assistance.get-data"), {
            params: {
                page: page,
                status: filter.value.status,
                search: search.value,
            },
        })
        .then((res) => {
            data.value = res.data.data;
            pagination.value = res.data.meta.pagination;
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
}, 250);

const handleSearch = (value) => {
    search.value = value;
    isLoading.value = true;
    getData(1);
};

const applyFilter = (val) => {
    filter.value = {
        status: val.status,
    };
    isLoading.value = true;
    getData(1);
};

const clearFilter = () => {
    filter.value = {};
    isLoading.value = true;
    getData(1);
};

const handleSelectAllData = (value) => {
    if (value) {
        selectedData.value = data.value.map((item) => item.id);
    } else {
        selectedData.value = [];
    }
};

const handleSelectData = (id, value) => {
    if (value) {
        selectedData.value.push(id);
    } else {
        selectedData.value = selectedData.value.filter((item) => item !== id);
    }
};

const handleChangePage = (page) => {
    isLoading.value = true;
    getData(page);
};

const handleEdit = (val) => {
    Inertia.visit(route("admin.social-assistance.edit", val.id));
};

const handleDetail = (val) => {
    Inertia.visit(route("admin.social-assistance.show", val.id));
};

const handleAlertDeleteMany = () => {
    openAlertMany.value = true;
    alertData.headerLabel = "Menghapus data bantuan sosial";
    alertData.contentLabel = `Anda yakin ingin menghapus data bantuan sosial terpilih?`;
    alertData.closeLabel = "Batal";
    alertData.submitLabel = "Hapus";
};

const handleAlertDelete = (data) => {
    openAlert.value = true;
    alertData.headerLabel = "Menghapus data bantuan sosial";
    alertData.contentLabel = `Anda yakin ingin menghapus data bantuan sosial ${data.family_card_number}?`;
    alertData.closeLabel = "Batal";
    alertData.submitLabel = "Hapus";
    itemSelected.value = { ...data };
};

const closeAlert = () => {
    openAlert.value = false;
};

const closeAlertMany = () => {
    openAlertMany.value = false;
};

const deleteManyData = () => {
    axios
        .delete(route("admin.social-assistance.destroy-many"), {
            data: {
                ids: selectedData.value,
            },
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
            openAlertMany.value = false;
            isLoading.value = true;
            getData(1);
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
            openAlert.value = false;
            selectedData.value = [];
        });
};

const deleteData = () => {
    axios
        .delete(route("admin.social-assistance.destroy", itemSelected.value.id))
        .then((res) => {
            notify(
                {
                    type: "success",
                    group: "top",
                    text: res.data.meta.message,
                },
                2500
            );
            openAlert.value = false;
            isLoading.value = true;
            getData(1);
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
            openAlert.value = false;
            itemSelected.value = {};
        });
};

onMounted(() => {
    getData(1);
});
</script>
<template>
    <div class="w-full flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Daftar Bantuan Sosial</h1>
        <Link
            :href="route('admin.social-assistance.create')"
            class="text-white text-sm bg-primary rounded-md px-4 py-2"
        >
            Tambah Data
        </Link>
    </div>
    <div class="w-full mt-8 flex justify-between items-center">
        <div class="w-1/3">
            <SearchInput
                v-model="search"
                placeholder="Cari nama bantuan sosial"
                @update:modelValue="handleSearch"
            />
        </div>
        <div class="flex items-center gap-6">
            <button
                @click="handleAlertDeleteMany"
                :disabled="selectedData.length === 0"
                class="text-sm disabled:cursor-not-allowed disabled:text-gray-400 disabled:border-gray-300 text-red-500 border border-red-600 flex space-x-2 items-center rounded px-4 py-2"
            >
                <TriangleExclamationIcon />
                <p>Hapus data terpilih</p>
            </button>
            <Filter
                @apply="applyFilter"
                @clear="clearFilter"
                :additional="props.additional"
            />
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-8">
        <DataTable
            :heads="heads"
            @select-all="handleSelectAllData"
            :isLoading="isLoading"
            :isEmpty="data.length === 0"
            :isCheckedAll="
                selectedData.length === data.length && data.length !== 0
            "
        >
            <tr
                v-if="!isLoading && data.length !== 0"
                class="bg-white border-b hover:bg-gray-50"
                v-for="(item, index) in data"
                :key="index"
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
                                    handleSelectData(item.id, e.target.checked)
                            "
                        />
                        <label for="checkbox-table-search-1" class="sr-only"
                            >checkbox</label
                        >
                    </div>
                </td>
                <th
                    scope="row"
                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                >
                    {{ item.name }}
                </th>
                <td class="px-6 py-4">
                    Rp{{ formatCurrency(item.amount_per_kk) }}
                </td>
                <td class="px-6 py-4">
                    {{ dayjs(item.start_date).format("LL") }} -
                    {{ dayjs(item.end_date).format("LL") }} ({{
                        item.start_time
                    }}
                    - {{ item.end_time }})
                </td>
                <td class="px-6 py-4">
                    Rp{{ formatCurrency(item.total_amount) }}
                </td>
                <td class="px-6 py-4">
                    {{ formatCurrency(item.recipient_count) }}
                </td>
                <td
                    class="px-6 py-4"
                    :class="{
                        'text-green-600':
                            item.status.toLowerCase() === 'active',
                        'text-blue-600':
                            item.status.toLowerCase() === 'finished',
                        'text-yellow-600':
                            item.status.toLowerCase() === 'draft',
                    }"
                >
                    {{ item.status }}
                </td>
                <td class="px-6 py-4">
                    <DropdownEditMenu
                        class="relative inline-flex r-0"
                        :align="'right'"
                        :last="index === data.length - 1 ? true : false"
                    >
                        <li
                            @click="() => handleEdit(item)"
                            class="cursor-pointer hover:bg-slate-100 py-3 px-4"
                        >
                            <div
                                class="flex items-center space-x-2 cursor-pointer text-primary"
                            >
                                <EditIcon />
                                <p>Ubah</p>
                            </div>
                        </li>
                        <li
                            @click="() => handleDetail(item)"
                            class="cursor-pointer hover:bg-slate-100 py-3 px-4"
                        >
                            <div
                                class="flex items-center space-x-2 cursor-pointer text-primary"
                            >
                                <EyeOpen />
                                <p>Lihat</p>
                            </div>
                        </li>
                        <li
                            class="cursor-pointer hover:bg-slate-100 py-3 px-4"
                            @click="handleAlertDelete(item)"
                        >
                            <div
                                class="flex items-center space-x-2 cursor-pointer text-red-600"
                            >
                                <TrashCanIcon />
                                <p>Hapus</p>
                            </div>
                        </li>
                    </DropdownEditMenu>
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
            <tr v-else class="bg-white border-b hover:bg-gray-50">
                <td :colspan="heads.length + 1" class="h-[100%]">
                    <Loading />
                </td>
            </tr>
        </DataTable>
        <Pagination
            @update:pagination="handleChangePage"
            :pagination="pagination"
        />
    </div>
    <Alert
        :open-dialog="openAlert"
        @closeAlert="closeAlert"
        @submitAlert="deleteData"
        type="danger"
        :headerLabel="alertData.headerLabel"
        :contentLabel="alertData.contentLabel"
        :closeLabel="alertData.closeLabel"
        :submitLabel="alertData.submitLabel"
    />
    <Alert
        :open-dialog="openAlertMany"
        @closeAlert="closeAlertMany"
        @submitAlert="deleteManyData"
        type="danger"
        :headerLabel="alertData.headerLabel"
        :contentLabel="alertData.contentLabel"
        :closeLabel="alertData.closeLabel"
        :submitLabel="alertData.submitLabel"
    />
</template>
