<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { watch } from 'vue';


const props = defineProps({
    contact: {
        type: Object,
        default: () => ({
            name: '',
            email: '',
            phone: '',
        }),
    },
    submitUrl: {
        type: String,
        required: true,
    },
    method: {
        type: String,
        default: 'post',
    },
    submitLabel: {
        type: String,
        default: 'Save',
    },
    title: {
        type: String,
        default: 'Contact Form',
    },
});

const form = useForm({
    name: props.contact.name,
    email: props.contact.email,
    phone: props.contact.phone,
});

function submit() {
    if (props.method === 'post') {
        form.post(props.submitUrl);
    } else if (props.method === 'put' || props.method === 'patch') {
        form.put(props.submitUrl);
    }
}

watch(
    () => props.contact,
    (newContact) => {
        form.name = newContact.name;
        form.email = newContact.email;
        form.phone = newContact.phone;
    },
    { immediate: true }
);

</script>
<template>
    <form @submit.prevent="submit" class="max-w-lg mx-auto bg-white border border-gray-200 p-8 rounded-2xl shadow-sm space-y-6">
        <h2 class="text-2xl font-semibold text-gray-800 text-center">
            {{ title }}
        </h2>

        <!-- Name -->
        <div class="space-y-1">
            <label class="block text-gray-700 font-medium">Name</label>
            <input
                v-model="form.name"
                type="text"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                placeholder="John Doe"
            />
            <p v-if="form.errors.name" class="text-red-500 text-sm">
                {{ form.errors.name }}
            </p>
        </div>

        <!-- Email -->
        <div class="space-y-1">
            <label class="block text-gray-700 font-medium">Email</label>
            <input
                v-model="form.email"
                type="email"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                placeholder="john@example.com"
            />
            <p v-if="form.errors.email" class="text-red-500 text-sm">
                {{ form.errors.email }}
            </p>
        </div>

        <!-- Phone -->
        <div class="space-y-1">
            <label class="block text-gray-700 font-medium">Phone</label>
            <input
                v-model="form.phone"
                type="text"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                placeholder="0555 555 555"
            />
            <p v-if="form.errors.phone" class="text-red-500 text-sm">
                {{ form.errors.phone }}
            </p>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button
                type="submit"
                :disabled="form.processing"
                class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium px-5 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-transform transform hover:scale-105"
            >
                <span v-if="!form.processing">{{ submitLabel }}</span>
                <span v-else>Processing...</span>
            </button>
        </div>
    </form>
</template>
