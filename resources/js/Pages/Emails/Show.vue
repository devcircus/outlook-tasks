<template>
    <layout :title="`Show Email`">
        <div class="w-full flex bg-blue-800 p-4">
            <inertia-link class="text-lg md:text-xl text-blue-300 hover:text-white uppercase mr-1" :href="route('dashboard')">Dashboard</inertia-link>
            <span class="text-lg md:text-xl text-blue-300 font-medium mr-1">></span>
            <h1 class="text-white text-lg md:text-xl font-semibold uppercase">{{ email.subject }}</h1>
        </div>
        <trashed-message v-if="email.deleted_at" class="mb-6" @restore="restore">
            This email has been deleted.
        </trashed-message>
        <div class="bg-white rounded shadow overflow-hidden w-full">
            <div class="p-8 -mr-6 -mb-8 flex flex-col md:flex-row w-full md:w-4/5">
                <div class="flex flex-col w-full mb-4">
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 text-base font-semibold align-middle mr-4">Received:</span>
                        <span class="text-gray-900 text-sm align-middle">{{ email.received_at }}</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 text-base font-semibold align-middle mr-4">From:</span>
                        <span class="text-gray-900 text-sm align-middle">{{ email.from_name }}</span>
                    </div>
                    <p class="bg-gray-200 p-6 overflow-wrap">
                        <pre class="font-sans text-gray-800 text-sm overflow-x-auto whitespace-pre-wrap break-words">{{ email.body }}</pre>
                    </p>
                </div>
            </div>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
                <button v-if="! email.deleted_at" class="text-red-500 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Email</button>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import TrashedMessage from '@/Shared/TrashedMessage';

export default {
    components: {
        Layout,
        TrashedMessage,
    },
    props: {
        email: Object,
    },
    methods: {
        destroy () {
            this.$modal.show('deleteEmailDialog', {
                title: 'Caution!',
                text: 'Are you sure you want to delete this email?',
                buttons: [
                    {
                        title: 'Delete Email',
                        type: 'delete',
                        handler: () => {
                            this.$inertia.delete(this.route('emails.destroy', this.email.id), { replace: false, preserveScroll: true, preserveState: true });
                            this.$modal.hide('deleteEmailDialog');
                         },
                    },
                    {
                        title: 'Close',
                        type: 'close',
                        handler: () => { this.$modal.hide('deleteEmailDialog') },
                    },
                ],
            });
        },
        restore () {
            this.$modal.show('restoreEmailDialog', {
                title: 'Notice!',
                text: 'Are you sure you want to restore this email?',
                buttons: [
                    {
                        title: 'Restore Email',
                        type: 'restore',
                        handler: () => {
                            this.$inertia.put(this.route('emails.restore', this.email.id), null, { replace: false, preserveScroll: true, preserveState: true });
                            this.$modal.hide('restoreEmailDialog');
                         },
                    },
                    {
                        title: 'Close',
                        type: 'close',
                        handler: () => { this.$modal.hide('restoreEmailDialog') },
                    },
                ],
            });
        },
    },
}
</script>
