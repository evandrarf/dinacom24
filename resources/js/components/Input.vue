<script setup>
import { ref } from "vue";
import { oneOf, string, bool, any } from "vue-types";

import EyeOpenIcon from "@/components/icons/EyeOpenIcon.vue";
import EyeClosedIcon from "@/components/icons/EyeClosedIcon.vue";

const isShowPassword = ref(false);

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
        <div class="w-full relative flex items-center">
            <input
                :type="type === 'password' && isShowPassword ? 'text' : type"
                :value="modelValue"
                @input="updateModelValue"
                class="bg-gray-50 border outline-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 w-full p-2.5"
                :placeholder="placeholder"
                :required="required"
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
