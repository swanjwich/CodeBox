<script setup>
import Sidebar from '@/Pages/Partials/Sidebar.vue';
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const isExpanded = ref(false);
const toggleSidebar = () => {
    isExpanded.value = !isExpanded.value;
};

const page = usePage();
const flash = ref(page.props.flash || { message: null, type: 'info' });

watch(() => page.props.flash, (newValue) => {
    if (newValue?.message) {
        flash.value = newValue;
        setTimeout(() => {
            flash.value.message = null;
        }, 3000);
    }
}, { immediate: true });

const flashStyles = {
    success: "bg-green-500 text-white",
    error: "bg-red-500 text-white",
    warning: "bg-yellow-500 text-black",
    info: "bg-blue-500 text-white",
    notif: "bg-blue-500 text-white",
};

const flashIcons = {
    success: "ri-checkbox-circle-line",
    error: "ri-close-circle-line",
    warning: "ri-error-warning-line",
    info: "ri-information-line",
    notif: "ri-notification-2-line",
};

</script>

<template>
    <div class="flex h-screen bg-[#e9e5e6] p-3">
        <Sidebar :isExpanded="isExpanded" @toggle="toggleSidebar" />
        <div :class="isExpanded ? 'ms-60' : 'ms-20'" class="p-5 bg-white flex-1 overflow-auto rounded-2xl">
            <transition name="fade">
                <div v-if="flash.message"
                    :class="[flashStyles[flash.type] || flashStyles.info, 'fixed top-5 right-5 px-6 py-3 rounded-lg shadow-lg text-sm flex items-center space-x-2 z-50']">
                    <i :class="[flashIcons[flash.type] || flashIcons.info, 'text-lg']"></i>
                    <span>{{ flash.message }}</span>
                </div>
            </transition>
            <slot></slot>
        </div>
    </div>
</template>
<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease, transform 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}
</style>
