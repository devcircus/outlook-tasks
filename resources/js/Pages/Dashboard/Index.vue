<template>
    <layout title="Home">
        <div class="min-h-screen">
            <h1 class="mb-8 font-bold text-xl md:text-2xl">
                <inertia-link class="text-blue-500 hover:text-blue-800 uppercase" :href="route('dashboard')">Tasks</inertia-link>
            </h1>
            <div v-if="token">
                <span class="text-sm text-gray-800">Display name: {{ displayName }}</span>
                <span class="text-sm text-gray-800">Token: {{ token }}</span>
            </div>
            <div v-else class="flex flex-col w-full mb-8">
                <span class="text-base text-gray-800 mb-4">Click the button below to authorize with Outlook.</span>
                <a :href="route('outlook.signin')" class="btn btn-blue w-200p text-center">Connect to Outlook</a>
            </div>
            <item-list :header-fields="headerFields" :data="tasks" sort-field="due_date" sort="asc" not-found-message="No tasks found." entity-name="tasks" row-action="edit" />
        </div>
    </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import ItemList from '@/Shared/ItemList';

export default {
    components: {
        Layout,
        ItemList,
    },
    props: {
        displayName: {
            type: String,
            default: () => 'Guest',
        },
        messages: {
            type: Array,
            default: () => [],
        },
        tasks: {
            type: Array,
            default: () => [],
        },
    },
    data () {
        return {
            token: this.$page.token,
            headerFields: [
                {
                    name: 'title',
                    label: 'Title',
                },
                {
                    name: 'due_date',
                    label: 'Due',
                },
                {
                    name: 'report_to',
                    label: 'Report To',
                },
            ],
        }
    },
    computed: {
        messagesAvailable () {
            return ! this.isObjectEmpty(this.messages);
        },
    },
}
</script>
