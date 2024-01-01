<script setup>
import { ref, computed } from "vue";
import { oneOf, string, bool, any, number } from "vue-types";

import EyeOpenIcon from "@/components/icons/EyeOpenIcon.vue";
import EyeClosedIcon from "@/components/icons/EyeClosedIcon.vue";
import formatCurrency from "@/composables/formatCurrency";

const isShowPassword = ref(false);

const props = defineProps({
    label: string().def(""),
    placeholder: string().def(""),
    type: oneOf([
        "text",
        "email",
        "password",
        "number",
        "tel",
        "url",
        "numeric",
        "currency",
    ]).def("text"),
    required: bool().def(false),
    modelValue: any().def(""),
    min: number().def(-999999999999999),
    max: number().def(999999999999999),
    disabled: bool().def(false),
});

const realType = computed(() => {
    switch (props.type) {
        case "numeric":
            return "text";
        case "currency":
            return "text";
        default:
            return props.type;
    }
});

const inputRef = ref(null);

const emit = defineEmits(["update:modelValue"]);

const handleNumeric = (e) => {
    const regex = /^[0-9]*$/;
    if (!regex.test(e.target.value)) {
        inputRef.value.value = e.target.value.replace(/[^0-9]/g, "");
        return;
    }
    emit("update:modelValue", e.target.value);
};

const handleNumer = (e) => {
    if (Number(e.target.value) < props.min) {
        inputRef.value.value = props.min;
        emit("update:modelValue", props.min);
        return;
    } else if (Number(e.target.value) > props.max) {
        inputRef.value.value = props.max;
        emit("update:modelValue", props.max);
        return;
    }

    emit("update:modelValue", e.target.value);
};

const handleCurrency = (e) => {
    if (String(props.modelValue).startsWith("0") || e.target.value === "") {
        if (!e.target.value.substring(1).includes("0")) {
            emit("update:modelValue", e.target.value.substring(1));
            return;
        } else {
            inputRef.value.value = "0";
            emit("update:modelValue", "0");
            return;
        }
    }

    const regex = /^[0-9.]*$/;
    if (!regex.test(e.target.value)) {
        inputRef.value.value = e.target.value.replace(/[^0-9.]/g, "");
        return;
    }

    const value = formatCurrency(e.target.value);

    emit("update:modelValue", value);
};

const updateModelValue = (e) => {
    switch (props.type) {
        case "numeric":
            handleNumeric(e);
            break;
        case "number":
            handleNumer(e);
            break;
        case "currency":
            handleCurrency(e);
            break;
        default:
            emit("update:modelValue", e.target.value);
            break;
    }
};
</script>
<template>
    <label class="flex flex-col">
        <p class="text-gray-500 text-sm mb-3" v-if="label">
            {{ label }} <span class="text-rose-500" v-if="required">*</span>
        </p>
        <div
            class="w-full relative rounded-lg flex items-center overflow-hidden"
        >
            <div
                v-if="type === 'currency'"
                class="absolute text-gray-600 text-sm left-2 cursor-pointer"
            >
                Rp.
            </div>
            <input
                ref="inputRef"
                :type="
                    realType === 'password' && isShowPassword
                        ? 'text'
                        : realType
                "
                :value="modelValue"
                :min="min"
                :max="max"
                @input="updateModelValue"
                :class="{
                    'pl-8': type === 'currency',
                    'pl-2.5': type !== 'currency',
                }"
                class="bg-gray-50 border disabled:cursor-not-allowed outline-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 w-full p-2.5"
                :placeholder="placeholder"
                :required="required"
                :disabled="disabled"
            />
            <div
                v-if="type === 'password'"
                @click="isShowPassword = !isShowPassword"
                class="bg-transparent absolute text-gray-400 text-sm right-4 cursor-pointer"
            >
                <EyeClosedIcon v-if="!isShowPassword" />
                <EyeOpenIcon v-else />
            </div>
        </div>
    </label>
</template>
