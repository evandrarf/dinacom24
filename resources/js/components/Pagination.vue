<script setup>
import { object } from "vue-types";

const props = defineProps({
    pagination: object().def({
        count: "",
        current_page: "",
        per_page: "",
        total: 0,
        total_pages: 1,
    }),
});

const getPageRange = () => {
    const startPage = Math.max(1, props.pagination.current_page - 2);
    const endPage = Math.min(startPage + 4, props.pagination.total_pages);
    return Array.from(
        { length: endPage - startPage + 1 },
        (_, i) => startPage + i
    );
};

const emit = defineEmits(["update:pagination"]);

const handleUpdatePage = (page) => {
    if (page < 1 || page > props.pagination.total_pages) {
        return;
    }
    emit("update:pagination", page);
};
</script>
<template>
    <nav
        class="flex items-center flex-column flex-wrap md:flex-row justify-between p-4"
        aria-label="Table navigation"
    >
        <span
            class="text-sm font-normal text-gray-500 mb-4 md:mb-0 block w-full md:inline md:w-auto"
            >Showing
            <span class="font-semibold text-gray-900">{{
                pagination.per_page * (pagination.current_page - 1) +
                pagination.count
            }}</span>
            of
            <span class="font-semibold text-gray-900">{{
                pagination.total
            }}</span></span
        >
        <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
            <li>
                <button
                    @click="handleUpdatePage(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1"
                    class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-s-lg disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed hover:bg-gray-100 hover:text-gray-700"
                >
                    Sebelumnya
                </button>
            </li>
            <li v-for="page in getPageRange()">
                <button
                    @click="handleUpdatePage(page)"
                    href="#"
                    :disabled="pagination.total === 0"
                    :class="{
                        'bg-primary text-white':
                            pagination.current_page === page,
                        'text-gray-500  bg-white border border-gray-300  hover:text-gray-700 hover:bg-gray-100':
                            pagination.current_page !== page,
                    }"
                    class="flex items-center justify-center px-3 h-8 leading-tight disabled:text-gray-500 disabled:cursor-not-allowed disabled:bg-gray-200 disabled:border-gray-200 disabled:border"
                >
                    {{ page }}
                </button>
            </li>

            <li>
                <button
                    @click="handleUpdatePage(pagination.current_page + 1)"
                    :disabled="
                        pagination.current_page === pagination.total_pages
                    "
                    class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed hover:bg-gray-100 hover:text-gray-700"
                >
                    Selanjutnya
                </button>
            </li>
        </ul>
    </nav>
</template>
