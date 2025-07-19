<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { computed } from 'vue';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

const props = defineProps<{
    links: PaginationLink[];
}>();

const numericLinks = computed(() =>
    props.links.filter(link => !isNaN(Number(link.label.toString())))
);

const currentIndex = computed(() =>
    numericLinks.value.findIndex(link => link.active)
);

const visiblePageLinks = computed(() => {
    if (numericLinks.value.length <= 5) {
        return numericLinks.value;
    }
    let start = currentIndex.value - 2;
    let end = currentIndex.value + 3;
    if (start < 0) {
        start = 0;
        end = 5;
    }
    if (end > numericLinks.value.length) {
        end = numericLinks.value.length;
        start = end - 5;
    }
    return numericLinks.value.slice(start, end);
});

const prevLink = computed(() =>
    props.links.find(link => link.label.toLowerCase().includes('previous'))
);
const nextLink = computed(() =>
    props.links.find(link => link.label.toLowerCase().includes('next'))
);

function goTo(link: PaginationLink) {
    if (link.url) {
        router.visit(link.url, { preserveScroll: true });
    }
}
</script>

<template>
    <div v-if="props.links && props.links.length > 0" class="flex flex-wrap justify-center mt-6 gap-2">
        <!-- Prev Button -->
        <Button
            :disabled="!prevLink?.url"
            @click.prevent="prevLink && goTo(prevLink)"
            variant="secondary"
            size="sm"
        >
            Prev
        </Button>

        <!-- Optional: Leading ellipsis -->
        <span v-if="numericLinks.length > 5 && currentIndex > 2" class="px-2 text-gray-500">...</span>

        <!-- Numbered Pages -->
        <Button
            v-for="link in visiblePageLinks"
            :key="link.label"
            :variant="link.active ? 'default' : 'secondary'"
            :class="link.active ? 'ring-2 ring-primary' : ''"
            @click.prevent="goTo(link)"
            size="sm"
        >
            {{ link.label }}
        </Button>

        <!-- Optional: Trailing ellipsis -->
        <span
            v-if="numericLinks.length > 5 && currentIndex < numericLinks.length - 3"
            class="px-2 text-gray-500"
        >...</span>

        <!-- Next Button -->
        <Button
            :disabled="!nextLink?.url"
            @click.prevent="nextLink && goTo(nextLink)"
            variant="secondary"
            size="sm"
        >
            Next
        </Button>
    </div>
</template>
