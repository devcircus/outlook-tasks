<template>
    <layout title="Home">
        <div class="flex flex-col min-h-screen">
            <div class="flex flex-col">
                <h1 class="mb-8 font-bold text-xl text-gray-700 md:text-2xl uppercase">Tasks</h1>
                <div v-if="categoriesReady" class="flex flex-wrap w-full md:-mx-2">
                    <div v-for="category in $page.categories.data" :key="category.id" class="flex flex-col w-full md:w-1/3 md:px-2">
                        <task-table :category="category" :tasks="getTasksForCategory(category.name)"/>
                    </div>
                </div>
                <div v-else class="flex flex-wrap w-full mb-8">
                    <h2 class="mb-2 font-semibold text-base text-gray-800 md:text-lg text-gray-800 uppercase">No categories defined.</h2>
                </div>
            </div>
            <div class="flex flex-col">
                <h1 class="mb-8 font-bold text-xl text-gray-700 md:text-2xl uppercase">Email</h1>
                <vue-good-table v-if="windowWidth >= 768" class="mb-8" :columns="emailColumns" :rows="emails" :row-style-class="rowClasses">
                    <div slot="emptystate">
                        No emails found.
                    </div>
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'actions'" class="flex justify-between px-3">
                            <button class="text-red-500 hover:underline" tabindex="-1" type="button" @click="destroyEmail(props.row.id)">Delete</button>
                            <button class="text-green-500 hover:underline" tabindex="-1" type="button" @click="newTask(null, props.row)">New Task</button>
                            <button class="text-blue-500 hover:underline" tabindex="-1" type="button" @click="showEmail(props.row.id)">View</button>
                        </span>
                        <span v-else>
                            {{ props.formattedRow[props.column.field] }}
                        </span>
                    </template>
                </vue-good-table>
                <template v-else>
                    <div class="border border-gray-400 mb-8">
                        <div v-for="email in emails" :key="email.id" class="flex flex-col bg-white p-3 border-b">
                            <span class="font-semibold text-gray-700 mb-2">Subject: <span class="font-normal">{{ email.subject }}</span></span>
                            <span class="font-semibold text-gray-700 mb-2">From: <span class="font-normal">{{ email.from_address }}</span></span>
                            <span class="font-semibold text-gray-700 mb-3">Received: <span class="font-normal">{{ email.received_at }}</span></span>
                            <span class="flex justify-between">
                                <button class="text-red-500 hover:underline" tabindex="-1" type="button" @click="destroyEmail(email.id)">Delete</button>
                                <button class="text-green-500 hover:underline" tabindex="-1" type="button" @click="newTask(null, email)">New Task</button>
                                <button class="text-blue-500 hover:underline" tabindex="-1" type="button" @click="showEmail(email.id)">View</button>
                            </span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import { VueGoodTable } from 'vue-good-table';
import TaskTable from '@/Partials/Tasks/TaskTable';

export default {
    components: {
        Layout,
        TaskTable,
        VueGoodTable,
    },
    store: ['tables'],
    props: {
        tasks: {
            type: Object,
            default: () => ({}),
        },
        emails: {
            type: Array,
            default: () => [],
        },
    },
    data () {
        return {
            emailColumns: null,
        }
    },
    computed: {
        categoriesReady () {
            return this.$page.categories.ready;
        },
    },
    created () {
        this.emailColumns = this.tables.fields.emailFields;
    },
    methods: {
        destroyEmail (id) {
            this.$inertia.delete(this.route('emails.destroy', id), { replace: false, preserveScroll: true, preserveState: true });
        },
        showEmail (id) {
            this.$inertia.replace(this.route('emails.show', id));
        },
        rowClasses (row) {
            return 'cursor-pointer';
        },
        newTask (category, email) {
            this.$store.workingTask = email;
            this.$inertia.replace(this.route('tasks.create'));
        },
        getTasksForCategory (category) {
            if (category) {
                return this.tasks[category];
            }

            return [];
        },
    },
}
</script>
