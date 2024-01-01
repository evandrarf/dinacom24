<script setup>
import { ref, computed } from "vue";
import { object } from "vue-types";
import { Menu, MenuButton, MenuItems } from "@headlessui/vue";

import Select from "@/components/Select.vue";
import FilterIcon from "@/components/icons/FilterIcon.vue";

const filter = ref({
    village_id: null,
    status: "",
});

const props = defineProps({
    additional: object().def({}),
});

const filterVillage = ref(null);
const filterStatus = ref(null);

const emit = defineEmits(["apply", "clear"]);

const applyFilter = () => {
    emit("apply", filter.value);
};

const clearFilter = () => {
    filter.value = ref({});
    filterVillage.value.clearSelected();
    filterStatus.value.clearSelected();
    emit("clear", filter.value);
};
</script>
<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton
                class="flex w-full gap-2 items-center justify-center rounded px-4 py-2 text-sm font-medium text-gray-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 border-gray-400 border"
            >
                <FilterIcon />
                <span>Filter</span>
            </MenuButton>
        </div>
        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <MenuItems
                class="absolute z-30 right-8 mt-2 w-[500px] origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
            >
                <div class="px-1 py-1">
                    <div class="grid grid-cols-2">
                        <div
                            class="font-medium group flex flex-col w-full justify-center rounded-md px-2 py-2 text-sm"
                        >
                            <span class="mb-4 text-primary"
                                >Filter berdasar kelurahan/desa</span
                            >
                            <Select
                                placeholder="Pilih kelurahan/desa"
                                v-model="filter.village_id"
                                :options="additional.villages"
                                :clearable="false"
                                class="w-full"
                                ref="filterVillage"
                            />
                        </div>
                        <div
                            class="font-medium group flex flex-col w-full justify-center rounded-md px-2 py-2 text-sm"
                        >
                            <span class="mb-4 text-primary"
                                >Filter berdasar status akun</span
                            >
                            <Select
                                placeholder="Pilih status akun"
                                v-model="filter.status"
                                :options="{
                                    active: 'Aktif',
                                    inactive: 'Belum Aktif',
                                }"
                                :clearable="false"
                                class="w-full"
                                ref="filterStatus"
                            />
                        </div>
                        <div class="col-span-2 flex p-2 justify-end gap-4">
                            <button
                                @click="clearFilter"
                                class="text-black border border-gray-400 text-sm bg-white rounded-md px-4 py-2"
                            >
                                Hapus filter
                            </button>
                            <button
                                @click="applyFilter"
                                class="text-white text-sm bg-primary rounded-md px-4 py-2"
                            >
                                Terapkan filter
                            </button>
                        </div>
                    </div>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>
