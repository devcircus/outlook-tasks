<template>
    <layout title="Home">
        <div class="min-h-screen">
            <h1 class="mb-8 font-bold text-xl md:text-2xl">
                <inertia-link class="text-blue-500 hover:text-blue-800 uppercase" :href="route('dashboard')">Tasks</inertia-link>
            </h1>
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
