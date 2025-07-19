<script setup lang="ts">
const props = defineProps<{
    photo?: string | null;
    size?: string; // e.g., 'w-24 h-24'
}>();

const sizeClass = props.size ?? 'w-28 h-28';

function photoUrl() {
    if (!props.photo) return '/storage/default-avatar.svg';
    if (props.photo.startsWith('http') || props.photo.startsWith('/storage')) return props.photo;
    return `/storage/${props.photo}`;
}
</script>

<template>
    <div :class="`relative ${sizeClass} rounded-full overflow-hidden border border-gray-300 dark:border-gray-700 shadow`">
        <img
            :src="photoUrl()"
            alt="Contact Photo"
            class="w-full h-full object-cover"
        />
    </div>
</template>
