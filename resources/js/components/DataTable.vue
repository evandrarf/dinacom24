<script setup>
import { array, bool } from "vue-types";

const props = defineProps({
    heads: array().def([]),
    isLoading: bool().def(true),
    isEmpty: bool().def(false),
    isCheckedAll: bool().def(false),
});

const emit = defineEmits(["select-all"]);

const handleSelectAll = (event) => {
    emit("select-all", event.target.checked);
};
</script>
<template>
    <table
        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
        :class="{
            'min-h-96': isLoading || isEmpty,
        }"
    >
        <thead
            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
        >
            <tr>
                <th scope="col" class="p-4">
                    <div class="flex items-center">
                        <input
                            :disabled="isLoading || isEmpty"
                            id="checkbox-all-search"
                            type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2 disabled:cursor-not-allowed"
                            @click="handleSelectAll"
                            :checked="isCheckedAll"
                        />
                        <label for="checkbox-all-search" class="sr-only"
                            >checkbox</label
                        >
                    </div>
                </th>
                <th
                    scope="col"
                    class="px-6 py-3"
                    v-for="(head, index) in heads"
                    :key="index"
                >
                    {{ head }}
                </th>
            </tr>
        </thead>
        <tbody>
            <slot />
        </tbody>
    </table>
</template>
