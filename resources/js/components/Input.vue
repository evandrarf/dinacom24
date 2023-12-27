<script setup>
import { oneOf, string, bool, any } from "vue-types";

const props = defineProps({
    label: string().def(""),
    placeholder: string().def(""),
    type: oneOf(["text", "email", "password", "number", "tel", "url"]).def(
        "text"
    ),
    required: bool().def(false),
    modelValue: any().def(""),
});

const emit = defineEmits(["update:modelValue"]);

const updateModelValue = (e) => {
    emit("update:modelValue", e.target.value);
};
</script>
<template>
    <label class="flex flex-col">
        <p class="text-gray-500 text-sm mb-3" v-if="label">
            {{ label }} <span class="text-rose-500" v-if="required">*</span>
        </p>
        <input
            :type="type"
            :value="modelValue"
            @input="updateModelValue"
            class="bg-gray-50 border outline-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 w-full p-2.5"
            :placeholder="placeholder"
            :required="required"
        />
    </label>
</template>
