<script setup lang="ts">
import { ref, watch, nextTick } from 'vue';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Pencil, X, Check } from 'lucide-vue-next';

const props = defineProps<{
    modelValue: string | null;
    placeholder?: string;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'save', value: string): void;
}>();

const editing = ref(false);
const value = ref(props.modelValue ?? ''); // Ensure always a string

const inputRef = ref<HTMLInputElement | null>(null);

watch(() => props.modelValue, (newVal) => {
    if (!editing.value) {
        value.value = newVal ?? '';
    }
});

function startEdit() {
    editing.value = true;
    value.value = props.modelValue ?? '';
    nextTick(() => {
        inputRef.value?.focus();
    });
}

function cancelEdit() {
    editing.value = false;
    value.value = props.modelValue ?? '';
}

function save() {
    emit('update:modelValue', value.value);
    emit('save', value.value);
    editing.value = false;
}

function handleKey(e: KeyboardEvent) {
    if (e.key === 'Enter') {
        e.preventDefault();
        save();
    } else if (e.key === 'Escape') {
        e.preventDefault();
        cancelEdit();
    }
}
</script>

<template>
    <div class="flex items-center gap-2">
        <template v-if="editing">
            <Input
                ref="inputRef"
                v-model="value"
                :placeholder="placeholder ?? 'Edit...'"
                @keyup="handleKey"
                @blur="save"
                class="max-w-xs"
            />
            <Button size="icon" variant="secondary" @click="save">
                <Check class="w-4 h-4" />
            </Button>
            <Button size="icon" variant="ghost" @click="cancelEdit">
                <X class="w-4 h-4" />
            </Button>
        </template>

        <template v-else>
            <span class="text-gray-800 dark:text-gray-200 cursor-pointer" @click="startEdit">
                {{ modelValue || placeholder || 'Click to add...' }}
            </span>
            <Button size="icon" variant="ghost" @click="startEdit">
                <Pencil class="w-4 h-4 text-gray-500 dark:text-gray-400" />
            </Button>
        </template>
    </div>
</template>
