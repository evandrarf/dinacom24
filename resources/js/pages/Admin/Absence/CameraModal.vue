<script setup>
import { bool, object } from "vue-types";
import axios from "axios";
import { notify } from "notiwind";
import { ref, watch } from "vue";

import Dialog from "@/components/CameraDialog.vue";
import QrCodeReader from "@/components/QrCodeReader.vue";

const props = defineProps({
    openDialog: bool().def(false),
    updateAction: bool().def(false),
    itemSelected: object().def({}),
    additional: object().def({}),
});

const emit = defineEmits(["close", "send-data"]);

const closeForm = () => {
    emit("close");
};

const error = ref("");

const validate = (value) => {
    return !isNaN(value) && value.length === 17;
};

const sendData = (data) => {
    if (validate(data)) {
        emit("send-data", data);
    }
};
</script>
<template>
    <Dialog
        :showModal="props.openDialog"
        title="Scan QR Code"
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
            <QrCodeReader
                v-if="!error"
                @send-data="sendData"
                @error="(data) => (error = data)"
                :showCamera="openDialog"
            />
            <p v-else>{{ error }}</p>
        </template>
        <template v-slot:footer> </template>
    </Dialog>
</template>
