<template>
    <layout title="Home">
        <div class="min-h-screen">
            <h1 class="mb-8 font-bold text-xl md:text-2xl">
                <inertia-link class="text-blue-500 hover:text-blue-800 uppercase" :href="route('dashboard')">Tasks</inertia-link>
            </h1>
            <item-list :header-fields="taskHeaderFields" :data="tasks" sort-field="due_date" sort="asc" not-found-message="No tasks found." entity-name="tasks" row-action="edit" />
            <h1 class="mb-8 font-bold text-xl md:text-2xl">
                <inertia-link class="text-blue-500 hover:text-blue-800 uppercase" :href="route('dashboard')">Email</inertia-link>
            </h1>
            <item-list :header-fields="emailHeaderFields" :data="emails" sort-field="received_at" sort="desc" not-found-message="No emails found." entity-name="emails" row-action="edit" />
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
        emails: {
            type: Array,
            default: () => [],
        },
    },
    data () {
        return {
            taskHeaderFields: [
                {
                    name: 'due_date',
                    label: 'Due',
                },
                {
                    name: 'title',
                    label: 'Title',
                },
                {
                    name: 'report_to',
                    label: 'Report To',
                },
            ],
            emailHeaderFields: [
                {
                    name: 'received_at',
                    label: 'Received',
                },
                {
                    name: 'subject',
                    label: 'Subject',
                },
                {
                    name: 'from_address',
                    label: 'From',
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
