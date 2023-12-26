<script setup>
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import { object, array } from "vue-types";

const props = defineProps({
    user: object().def({}),
    user_role: array().def(null),
});
</script>

<template>
    <div class="h-full px-4">
        <Popover v-slot="{ open }" class="relative">
            <PopoverButton
                :class="open ? 'text-black' : 'text-black/90'"
                class="group h-full inline-flex items-center rounded-md px-3 py-2 text-base font-medium hover:text-black focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
            >
                <div class="h-full flex items-center gap-4">
                    <img
                        src="/assets/admin.png"
                        class="h-6 w-6 aspect-square"
                        alt="Admin Profile"
                    />
                    {{ user.name }}
                </div>
                <ChevronDownIcon
                    :class="open ? 'text-orange-300' : 'text-orange-300/70'"
                    class="ml-2 h-5 w-5 transition duration-150 ease-in-out group-hover:text-orange-300/80"
                    aria-hidden="true"
                />
            </PopoverButton>

            <transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="translate-y-1 opacity-0"
                enter-to-class="translate-y-0 opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="translate-y-0 opacity-100"
                leave-to-class="translate-y-1 opacity-0"
            >
                <PopoverPanel
                    class="absolute left-1/2 z-10 mt-3 w-max -translate-x-1/2 transform sm:px-0"
                >
                    <div
                        class="overflow-hidden rounded-md shadow-lg ring-1 ring-black/5"
                    >
                        <slot />
                    </div>
                </PopoverPanel>
            </transition>
        </Popover>
    </div>
</template>
