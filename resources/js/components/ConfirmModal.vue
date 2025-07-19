<script setup lang="ts">
import { ref, watch } from 'vue';
import { Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    modelValue: boolean;
    title?: string;
    message?: string;
    confirmText?: string;
    cancelText?: string;
}>();

const emit = defineEmits(['update:modelValue', 'confirm']);

function close() {
    emit('update:modelValue', false);
}

function confirm() {
    emit('confirm');
    close();
}
</script>

<template>
    <Dialog :open="modelValue" @close="close" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="fixed inset-0 bg-black/40" aria-hidden="true"></div>
        <DialogPanel class="bg-white dark:bg-gray-900 rounded-xl shadow-lg max-w-sm w-full p-6 space-y-4 z-50">
            <DialogTitle class="text-lg font-semibold">{{ title ?? 'Confirm Action' }}</DialogTitle>
            <p class="text-gray-600 dark:text-gray-300">{{ message ?? 'Are you sure you want to proceed?' }}</p>
            <div class="flex justify-end gap-2 mt-4">
                <Button variant="secondary" @click="close">{{ cancelText ?? 'Cancel' }}</Button>
                <Button variant="destructive" @click="confirm">{{ confirmText ?? 'Delete' }}</Button>
            </div>
        </DialogPanel>
    </Dialog>
</template>
