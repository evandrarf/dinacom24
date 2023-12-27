<script>
export default {
    layout: AdminDashboardLayout,
};
</script>
<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { notify } from "notiwind";

import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import debounce from "@/composables/debounce";
import Loading from "@/components/Loading.vue";
import SearchInput from "@/components/SearchInput.vue";
import DataTable from "@/components/DataTable.vue";
import Pagination from "@/components/Pagination.vue";
import Empty from "@/components/icons/Empty.vue";

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

const heads = ["Nama Kelurahan", "Aksi"];

const getData = debounce(async (page) => {
    axios
        .get(route("admin.village.get-data"), {
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

onMounted(() => {
    getData(1);
});
</script>
<template>
    <div class="w-full flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Daftar Desa/Kelurahan</h1>
        <button class="text-white bg-primary rounded-md px-3 py-2">
            Tambah Data
        </button>
    </div>
    <div class="w-full mt-8">
        <div class="w-1/3">
            <SearchInput
                v-model="search"
                placeholder="Cari desa/kelurahan..."
                @update:modelValue="handleSearch"
            />
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-8">
        <DataTable
            :heads="heads"
            @select-all="handleSelectAllData"
            :isLoading="isLoading"
            :isEmpty="data.length === 0"
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
                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                >
                    {{ item.name }}
                </th>
                <td class="px-6 py-4">
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                    >
                        Edit
                    </span>
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
</template>
