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

const filterStatus = ref(null);

const emit = defineEmits(["apply", "clear"]);

const applyFilter = () => {
    emit("apply", filter.value);
};

const clearFilter = () => {
    filter.value = {};
    filterStatus.value.clearSelected();
    emit("clear");
};
</script>
<template>
    <Filter @apply="applyFilter" @clear="clearFilter" :additional="additional">
        <div
            class="font-medium group flex flex-col w-full justify-center rounded-md px-2 py-2 text-sm"
        >
            <span class="mb-4 text-primary"
                >Filter berdasar status publikasi</span
            >
            <Select
                placeholder="Pilih status publikasi"
                v-model="filter.status"
                :options="{
                    all: 'Semua',
                    draft: 'Draft',
                    active: 'Sudah dipublikasikan',
                    finished: 'Selesai',
                }"
                :clearable="false"
                class="w-full"
                ref="filterStatus"
            />
        </div>
    </Filter>
</template>
