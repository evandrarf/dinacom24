<script setup>
import Notification from "@/components/Notification.vue";
</script>
<template>
    <Notification></Notification>
    <div
        class="w-screen h-screen overflow-hidden flex justify-center items-center bg-gray-100"
    >
        <slot />
    </div>
</template>
