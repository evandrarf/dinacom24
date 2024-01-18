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

import AdminDashboardLayout from "@/layouts/AdminDashboardLayout.vue";
import debounce from "@/composables/debounce";
import Loading from "@/components/Loading.vue";
import SearchInput from "@/components/SearchInput.vue";
import DataTable from "@/components/DataTable.vue";
import Pagination from "@/components/Pagination.vue";
import Empty from "@/components/icons/Empty.vue";
import ModalForm from "./ModalForm.vue";
import TrashCanIcon from "@/components/icons/TrashCanIcon.vue";
import EditIcon from "@/components/icons/EditIcon.vue";
import DropdownEditMenu from "@/components/DropdownEditMenu.vue";
import TriangleExclamationIcon from "@/components/icons/TriangleExclamationIcon.vue";
import Alert from "@/components/Alert.vue";

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
const modalFormOpen = ref(false);
const updateAction = ref(false);
const openAlert = ref(false);
const openAlertMany = ref(false);

const alertData = reactive({
    headerLabel: "",
    contentLabel: "",
    closeLabel: "",
    submitLabel: "",
});

const props = defineProps({
    additional: object().def({}),
});

const heads = ["Nama", "Email", "Desa/Kelurahan", "Aksi"];

const getData = debounce(async (page) => {
    axios
        .get(route("admin.user-management.get-data"), {
            params: {
                page: page,
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

const openModalForm = () => {
    modalFormOpen.value = true;
};

const handleCloseModalForm = () => {
    modalFormOpen.value = false;
    updateAction.value = false;
    itemSelected.value = {};
};

const handleSuccess = () => {
    handleCloseModalForm();
    isLoading.value = true;
    getData(1);
};

const handleEdit = (val) => {
    modalFormOpen.value = true;
    itemSelected.value = { ...val };
    updateAction.value = true;
};

const handleAlertDeleteMany = () => {
    openAlertMany.value = true;
    alertData.headerLabel = "Menghapus data petugas admin";
    alertData.contentLabel = `Anda yakin ingin menghapus data petugas admin terpilih?`;
    alertData.closeLabel = "Batal";
    alertData.submitLabel = "Hapus";
};

const handleAlertDelete = (data) => {
    openAlert.value = true;
    alertData.headerLabel = "Menghapus data petugas admin";
    alertData.contentLabel = `Anda yakin ingin menghapus data petugas admin ${data.name}?`;
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
        .delete(route("admin.user-management.destroy-many"), {
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
        .delete(route("admin.user-management.destroy", itemSelected.value.id))
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
        <h1 class="text-2xl font-semibold">Daftar Petugas Admin</h1>
        <button
            @click="openModalForm"
            class="text-white text-sm bg-primary rounded-md px-4 py-2"
        >
            Tambah Data
        </button>
    </div>
    <div class="w-full mt-8 flex justify-between items-center">
        <div class="w-1/3">
            <SearchInput
                v-model="search"
                placeholder="Cari nama petugas/desa/kelurahan"
                @update:modelValue="handleSearch"
            />
        </div>
        <div>
            <button
                @click="handleAlertDeleteMany"
                :disabled="selectedData.length === 0"
                class="text-sm disabled:cursor-not-allowed disabled:text-gray-400 disabled:border-gray-300 text-red-500 border border-red-600 flex space-x-2 items-center rounded px-4 py-2"
            >
                <TriangleExclamationIcon />
                <p>Hapus data terpilih</p>
            </button>
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
                    {{ item.email }}
                </td>
                <td class="px-6 py-4">
                    {{ item.village.name }}
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
    <ModalForm
        :openDialog="modalFormOpen"
        :updateAction="updateAction"
        :itemSelected="itemSelected"
        :additional="props.additional"
        @success="handleSuccess"
        @close="handleCloseModalForm"
    />
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
