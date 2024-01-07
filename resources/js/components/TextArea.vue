<script setup>
import { bool, string, oneOf } from "vue-types";

const props = defineProps({
    label: string().def(""),
    required: bool().def(false),
    disabled: bool().def(false),
    placeholder: string().def(""),
    rows: string().def("4"),
    resize: oneOf(["vertical", "horizontal", "none"]).def("vertical"),
    modelValue: string().def(""),
});

const emit = defineEmits(["input"]);

const updateValue = (e) => {
    emit("input", e.target.value);
};
</script>
<template>
    <p class="text-gray-500 text-sm mb-3" v-if="label">
        {{ label }} <span class="text-rose-500" v-if="required">*</span>
    </p>
    <textarea
        :rows="rows"
        class="block p-2.5 w-full text-sm outline-none text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
        :value="modelValue"
        :class="{
            'cursor-not-allowed bg-gray-100': disabled,
            'resize-y': resize === 'vertical',
            'resize-x': resize === 'horizontal',
            'resize-none': resize === 'none',
        }"
        @input="updateValue"
        :placeholder="placeholder"
    ></textarea>
</template>
