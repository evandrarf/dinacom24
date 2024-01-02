<script setup>
import { ref, computed } from "vue";
import { object } from "vue-types";

import Select from "@/components/Select.vue";
import Filter from "@/components/Filter.vue";

const props = defineProps({
    additional: object().def({}),
});

const filter = ref({
    village_id: null,
    status: "",
    eligibility_status: "",
});

const filterVillage = ref(null);
const filterStatus = ref(null);
const filterEligibilityStatus = ref(null);

const emit = defineEmits(["apply", "clear"]);

const applyFilter = () => {
    emit("apply", filter.value);
};

const clearFilter = () => {
    filter.value = {};
    filterVillage.value.clearSelected();
    filterStatus.value.clearSelected();
    filterEligibilityStatus.value.clearSelected();
    emit("clear");
};
</script>
<template>
    <Filter @apply="applyFilter" @clear="clearFilter" :additional="additional">
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
            <span class="mb-4 text-primary">Filter berdasar status akun</span>
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
        <div
            class="font-medium group flex flex-col w-full justify-center rounded-md px-2 py-2 text-sm"
        >
            <span class="mb-4 text-primary"
                >Filter berdasar status kelayakan</span
            >
            <Select
                placeholder="Pilih status kelayakan"
                v-model="filter.eligibility_status"
                :options="{
                    all: 'Semua',
                    eligible: 'Layak',
                    not_eligible: 'Tidak Layak',
                }"
                :clearable="false"
                class="w-full"
                ref="filterEligibilityStatus"
            />
        </div>
    </Filter>
</template>
