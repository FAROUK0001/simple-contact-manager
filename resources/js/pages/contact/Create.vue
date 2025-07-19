<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { LoaderCircle } from 'lucide-vue-next';
import type { BreadcrumbItem } from '@/types';
import { ref } from 'vue';

// Form setup
const form = useForm({
    name: '',
    email: '',
    phone: '',
    notes: '',
    photo: null as File | null,
});

// Preview setup
const photoPreview = ref<string>('/storage/default-avatar.svg');

function handleFileChange(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        form.photo = file;
        photoPreview.value = URL.createObjectURL(file);
    }
}

function submit() {
    form.post(route('contact.store'), {
        forceFormData: true,
        preserveScroll: true,
    });
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Contacts', href: '/contact' },
    { title: 'Create', href: route('contact.create') },
];
</script>

<template>
    <Head title="Create Contact" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-lg w-full mx-auto bg-white dark:bg-gray-900 rounded-2xl shadow p-6 mt-6">
            <h1 class="text-lg font-semibold mb-4">Create Contact</h1>

            <form @submit.prevent="submit" class="flex flex-col gap-4">
                <!-- Avatar Preview -->
                <div class="flex justify-center mb-4">
                    <div class="relative w-24 h-24 rounded-full overflow-hidden border border-gray-300 dark:border-gray-700 shadow">
                        <img :src="photoPreview" alt="Contact Photo" class="w-full h-full object-cover" />
                    </div>
                </div>

                <!-- Photo Input -->
                <div class="grid gap-1.5">
                    <Label for="photo">Photo</Label>
                    <Input
                        id="photo"
                        type="file"
                        accept="image/*"
                        @change="handleFileChange"
                    />
                    <InputError :message="form.errors.photo" />
                </div>

                <!-- Name -->
                <div class="grid gap-1.5">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        placeholder="John Doe"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <!-- Email -->
                <div class="grid gap-1.5">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        placeholder="john@example.com"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <!-- Phone -->
                <div class="grid gap-1.5">
                    <Label for="phone">Phone</Label>
                    <Input
                        id="phone"
                        v-model="form.phone"
                        type="text"
                        placeholder="+213..."
                    />
                    <InputError :message="form.errors.phone" />
                </div>

                <!-- Notes -->
                <div class="grid gap-1.5">
                    <Label for="notes">Notes</Label>
                    <textarea
                        id="notes"
                        v-model="form.notes"
                        rows="3"
                        placeholder="Some notes..."
                        class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-transparent px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring focus:ring-primary/20 disabled:cursor-not-allowed disabled:opacity-50"
                    ></textarea>
                    <InputError :message="form.errors.notes" />
                </div>

                <!-- Submit Button -->
                <Button type="submit" class="mt-4 w-full" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="w-4 h-4 animate-spin mr-2" />
                    Create Contact
                </Button>
            </form>
        </div>
    </AppLayout>
</template>
