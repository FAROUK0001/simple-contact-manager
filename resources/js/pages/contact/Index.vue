<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import Pagination from '@/components/Pagination.vue';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import ConfirmModal from '@/components/ConfirmModal.vue';
import ContactAvatar from '@/components/ContactAvatar.vue';

interface Contact {
    id: number;
    name: string;
    email: string;
    photo: string | null; // Assuming photo can be null if no photo is set
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedContacts {
    data: Contact[];
    links: PaginationLink[];
    meta: any; // refine later
}

const props = defineProps<{
    contacts: PaginatedContacts;
    search?: string;
}>();

const search = ref(props.search ?? '');


const selectedContact = ref<Contact | null>(null);
const showDeleteModal = ref(false);

function openDeleteModal(contact: Contact) {
    selectedContact.value = contact;
    showDeleteModal.value = true;
}

function confirmDelete() {
    if (!selectedContact.value) return;
    router.delete(route('contact.destroy', selectedContact.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            selectedContact.value = null;
        },
    });
}
function performSearch() {
    if (!search.value.trim()) {
        router.get(route('contact.index')); // fallback to index if search is empty
    } else {
        router.get(route('contact.index'), { search: search.value }, { preserveScroll: true });
    }
}


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Contacts', href: '/contact' },
];
</script>

<template>
    <Head title="Contacts" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <h1 class="text-2xl font-bold">Contacts</h1>
            <Link :href="route('contact.create')">
                <Button size="sm">Create New Contact</Button>
            </Link>


        </div>
        <form @submit.prevent="performSearch" class="mb-4 flex gap-2">
            <input
                v-model="search"
                type="text"
                placeholder="Search contacts..."
                @keyup.enter="performSearch"
                class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-transparent px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring focus:ring-primary/20"
            />
            <Button type="submit">Search</Button>
        </form>
        <div class="overflow-auto bg-white dark:bg-gray-900 rounded-xl shadow border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                <tr class="text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">
                    <th class="px-4 py-3">Photo</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="contact in props.contacts.data" :key="contact.id">
                    <td class="px-4 py-2"> <ContactAvatar :photo="contact.photo" size="w-12 h-12" /></td>
                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">{{ contact.name }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">{{ contact.email }}</td>
                    <td class="px-4 py-2 text-right">
                        <div class="flex justify-end space-x-2">
                            <Link :href="route('contact.show', contact.id)">
                                <Button size="sm" variant="secondary">Details</Button>
                            </Link>
                            <Link :href="route('contact.edit', contact.id)">
                                <Button size="sm" variant="secondary">Edit</Button>
                            </Link>
                            <Button size="sm" variant="destructive" @click="openDeleteModal(contact)">
                                Delete
                            </Button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <div v-if="props.contacts.links && props.contacts.links.length > 0"
                 class="border-t border-gray-200 dark:border-gray-700 p-4 flex justify-center">
                <Pagination :links="props.contacts.links" />
            </div>

            <div v-if="props.contacts.data.length === 0"
                 class="text-center p-6 text-gray-500 dark:text-gray-400">
                No contacts found.
            </div>
        </div>

        <!-- Confirm Modal (placed outside the table) -->
        <ConfirmModal
            v-model="showDeleteModal"
            :title="`Delete ${selectedContact?.name}?`"
            message="This action cannot be undone."
            confirm-text="Delete"
            cancel-text="Cancel"
            @confirm="confirmDelete"
        />
    </AppLayout>
</template>
