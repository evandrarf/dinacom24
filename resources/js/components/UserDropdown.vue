<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import { object, array } from "vue-types";

const props = defineProps({
    user: object().def({}),
    user_role: array().def(null),
});
</script>

<template>
    <div class="h-full px-4">
        <div class="text-right">
            <Menu as="div" class="relative inline-block text-left">
                <div>
                    <MenuButton
                        class="flex w-full items-center justify-center rounded-md px-4 py-2 text-sm font-medium text-black focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                    >
                        <img
                            src="/assets/admin.png"
                            alt="Admin Profile"
                            class="w-8 h-8"
                        />
                        <span class="ml-4">
                            {{ user.name }}
                        </span>
                        <ChevronDownIcon
                            class="-mr-1 ml-2 h-5 w-5 text-violet-200 hover:text-violet-100"
                            aria-hidden="true"
                        />
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
                        class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                    >
                        <div class="px-1 py-1">
                            <div>
                                <div
                                    class="text-primary font-medium group flex w-full items-center rounded-md px-2 py-2 text-sm"
                                >
                                    {{ user_role[0] }}
                                </div>
                            </div>
                            <MenuItem v-slot="{ active }">
                                <slot name="logout-button" />
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>
        </div>
    </div>
</template>
