<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { ArrowLeft, Mail, Phone, StickyNote, Calendar, Trash } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import ConfirmModal from '@/components/ConfirmModal.vue';
import InlineEdit from '@/components/InlineEdit.vue';
import type { BreadcrumbItem } from '@/types';
import ContactAvatar from '@/components/ContactAvatar.vue';

interface Contact {
    id: number;
    name: string;
    email: string;
    phone?: string | null;
    notes?: string | null;
    photo?: string | null;
    created_at: string;
}

const props = defineProps<{
    contact: Contact;
}>();

const contact = ref(props.contact);

const showDeleteModal = ref(false);

function openDeleteModal() {
    showDeleteModal.value = true;
}

function confirmDelete() {
    router.delete(route('contact.destroy', contact.value.id), {
        onFinish: () => router.visit(route('contact.index')),
    });
}

function updateField(field: 'phone' | 'notes', value: string) {
    contact.value[field] = value;
    router.patch(route('contact.inline-update', contact.value.id), {
        [field]: value,
    }, {
        preserveScroll: true,
    });
}


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Contacts', href: '/contact' },
    { title: contact.value.name, href: `/contact/${contact.value.id}` },
];
</script>

<template>
    <Head :title="contact.name" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-2xl mx-auto p-4 space-y-6">

            <!-- Back -->
            <Link :href="route('contact.index')" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <ArrowLeft class="w-4 h-4 mr-1" /> Back to Contacts
            </Link>
            <div class="max-w-lg mx-auto p-4">
                <!-- Avatar -->
                <ContactAvatar :photo="contact.photo" class="mx-auto mb-6" />

                <!-- Rest of your form here -->
            </div>

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h1 class="text-2xl font-bold">{{ contact.name }}</h1>
                <Link :href="route('contact.edit', contact.id)">
                    <Button size="sm" variant="secondary">Edit</Button>
                </Link>
            </div>

            <!-- Details Card -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-6 space-y-4">

                <!-- Email -->
                <div class="flex items-center gap-3">
                    <Mail class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                    <div>
                        <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Email</h2>
                        <p class="text-gray-800 dark:text-gray-200">{{ contact.email }}</p>
                    </div>
                </div>

<!--                 Phone with inline edit-->
                <div class="flex items-center gap-3">
                    <Phone class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                    <div class="flex-1">
                        <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Phone</h2>
                        <InlineEdit
                            v-model="contact.phone"
                            placeholder="Add phone..."
                            @save="value => updateField('phone', value)"
                        />
                    </div>
                </div>

                <!-- Notes with inline edit -->
                <div class="flex items-center gap-3">
                    <StickyNote class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                    <div class="flex-1">
                        <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Notes</h2>
                        <InlineEdit
                            v-model="contact.notes"
                            placeholder="Add notes..."
                            @save="value => updateField('notes', value)"
                        />
                    </div>
                </div>

                <!-- Created At -->
                <div class="flex items-center gap-3">
                    <Calendar class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                    <div>
                        <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Created At</h2>
                        <p class="text-gray-800 dark:text-gray-200">
                            {{ new Date(contact.created_at).toLocaleString() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-red-50 dark:bg-red-900/30 rounded-xl shadow border border-red-200 dark:border-red-700 p-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <Trash class="w-5 h-5 text-red-600 dark:text-red-400" />
                    <p class="text-sm text-red-700 dark:text-red-300">Danger Zone: Delete this contact permanently</p>
                </div>
                <Button size="sm" variant="destructive" @click="openDeleteModal">
                    Delete
                </Button>
            </div>

            <!-- Confirm Modal -->
            <ConfirmModal
                v-model="showDeleteModal"
                :title="`Delete ${contact.name}?`"
                message="This action cannot be undone."
                confirm-text="Delete"
                cancel-text="Cancel"
                @confirm="confirmDelete"
            />
        </div>
    </AppLayout>
</template>
