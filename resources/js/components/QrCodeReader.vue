<script setup>
import { ref } from "vue";
import { bool } from "vue-types";

import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from "vue-qrcode-reader";

const props = defineProps({
    showCamera: bool().def(false),
});

const error = ref("");

const emit = defineEmits(["send-data", "error"]);

const detect = (detectedCodes) => {
    for (const detectedCode of detectedCodes) {
        const { rawValue } = detectedCode;

        emit("send-data", rawValue);
    }
};

const onError = (err) => {
    error.value = `[${err.name}]: `;

    if (err.name === "NotAllowedError") {
        error.value += "you need to grant camera access permission";
    } else if (err.name === "NotFoundError") {
        error.value += "no camera on this device";
    } else if (err.name === "NotSupportedError") {
        error.value += "secure context required (HTTPS, localhost)";
    } else if (err.name === "NotReadableError") {
        error.value += "is the camera already in use?";
    } else if (err.name === "OverconstrainedError") {
        error.value += "installed cameras are not suitable";
    } else if (err.name === "StreamApiNotSupportedError") {
        error.value += "Stream API is not supported in this browser";
    } else if (err.name === "InsecureContextError") {
        error.value +=
            "Camera access is only permitted in secure context. Use HTTPS or localhost rather than HTTP.";
    } else {
        error.value += err.message;
    }

    emit("error", error.value);
};
</script>
<template>
    <QrcodeStream
        v-if="showCamera"
        @error="onError"
        class="h-full"
        @detect="detect"
    />
</template>
