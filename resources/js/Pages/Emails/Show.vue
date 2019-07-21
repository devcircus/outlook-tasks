<template>
    <layout :title="`Show Email`">
        <h1 class="mb-8 font-bold text-lg md:text-3xl">
            <inertia-link class="text-blue-300 hover:text-blue-700" :href="route('dashboard')">Dashboard</inertia-link>
            <span class="text-blue-300 font-medium">/</span>
            <span class="text-blue-800 font-medium">{{ email.subject }}</span>
        </h1>
        <trashed-message v-if="email.deleted_at" class="mb-6" @restore="restore">
            This email has been deleted.
        </trashed-message>
        <div class="bg-white rounded shadow overflow-hidden w-full md:w-3/5 mb-8">
            <div class="p-8 -mr-6 -mb-8 flex flex-col md:flex-row w-full">
                <div class="flex flex-col w-full md:w-1/3">
                    <div class="flex flex-col">
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 text-base font-semibold align-middle mr-4">Received:</span>
                            <span class="text-gray-900 text-sm align-middle">{{ email.received_at }}</span>
                        </div>
                        <div class="flex items-center mb-4 md:mb-0">
                            <span class="text-gray-700 text-base font-semibold align-middle mr-4">From:</span>
                            <span class="text-gray-900 text-sm align-middle">{{ email.from_name }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full md:w-2/3 mb-4">
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
            if (confirm('Are you sure you want to delete this email?')) {
                this.$inertia.delete(this.route('emails.destroy', this.email.id));
            }
        },
        restore () {
            if (confirm('Are you sure you want to restore this email?')) {
                this.$inertia.put(this.route('emails.restore', this.email.id));
            }
        },
    },
}
</script>
