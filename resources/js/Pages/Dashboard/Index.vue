<template>
    <layout title="Home">
        <div class="flex flex-col min-h-screen">
            <h1 class="mb-8 font-bold text-xl text-gray-700 md:text-2xl uppercase">Tasks</h1>
            <div class="flex flex-wrap w-full md:-mx-2">
                <div class="flex flex-col w-full md:w-1/3 md:px-2">
                    <h2 class="mb-2 font-semibold text-base text-gray-800 md:text-lg text-gray-800 uppercase">Prototypes</h2>
                    <vue-good-table ref="prototypeTable" class="mb-8" :columns="taskColumns" :rows="tasks.prototype" @on-row-click="taskClicked">
                        <div slot="emptystate">
                            No prototype tasks found.
                        </div>
                        <div slot="table-actions">
                            <button class="btn btn-text text-blue-500 uppercase btn-sm mr-2" @click="newTask('prototype')">New</button>
                        </div>
                    </vue-good-table>
                </div>
                <div class="flex flex-col w-full md:w-1/3 md:px-2">
                    <h2 class="mb-2 font-semibold text-base text-gray-800 md:text-lg text-gray-800 uppercase">Lettering</h2>
                    <vue-good-table ref="letteringTable" class="mb-8" :columns="taskColumns" :rows="tasks.lettering" @on-row-click="taskClicked">
                        <div slot="emptystate">
                            No lettering tasks found.
                        </div>
                        <div slot="table-actions">
                            <button class="btn btn-text text-blue-500 uppercase btn-sm mr-2" @click="newTask('lettering')">New</button>
                        </div>
                    </vue-good-table>
                </div>
                <div class="flex flex-col w-full md:w-1/3 md:px-2">
                    <h2 class="mb-2 font-semibold text-base text-gray-800 md:text-lg text-gray-800 uppercase">Swatches</h2>
                    <vue-good-table ref="swatchesTable" class="mb-8" :columns="taskColumns" :rows="tasks.swatch" @on-row-click="taskClicked">
                        <div slot="emptystate">
                            No swatch tasks found.
                        </div>
                        <div slot="table-actions">
                            <button class="btn btn-text text-blue-500 uppercase btn-sm mr-2" @click="newTask('swatch')">New</button>
                        </div>
                    </vue-good-table>
                </div>
                <div class="flex flex-col w-full md:w-1/3 md:px-2">
                    <h2 class="mb-2 font-semibold text-base text-gray-800 md:text-lg text-gray-800 uppercase">VSF</h2>
                    <vue-good-table ref="vsfTable" class="mb-8" :columns="taskColumns" :rows="tasks.vsf" @on-row-click="taskClicked">
                        <div slot="emptystate">
                            No VSF tasks found.
                        </div>
                        <div slot="table-actions">
                            <button class="btn btn-text text-blue-500 uppercase btn-sm mr-2" @click="newTask('vsf')">New</button>
                        </div>
                    </vue-good-table>
                </div>
                <div class="flex flex-col w-full md:w-1/3 md:px-2">
                    <h2 class="mb-2 font-semibold text-base text-gray-800 md:text-lg text-gray-800 uppercase">Ozone</h2>
                    <vue-good-table ref="ozoneTable" class="mb-8" :columns="taskColumns" :rows="tasks.ozone" @on-row-click="taskClicked">
                        <div slot="emptystate">
                            No Ozone tasks found.
                        </div>
                        <div slot="table-actions">
                            <button class="btn btn-text text-blue-500 uppercase btn-sm mr-2" @click="newTask('ozone')">New</button>
                        </div>
                    </vue-good-table>
                </div>
            </div>
            <h1 class="mb-2 font-bold text-xl text-gray-700 md:text-2xl uppercase">Email</h1>
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
    </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import { VueGoodTable } from 'vue-good-table';

export default {
    components: {
        Layout,
        VueGoodTable,
    },
    store: ['tables'],
    props: {
        displayName: {
            type: String,
            default: () => 'Guest',
        },
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
            taskColumns: null,
            emailColumns: null,
        }
    },
    created () {
        this.taskColumns = this.tables.fields.taskFields;
        this.emailColumns = this.tables.fields.emailFields;
        this.initializeListeners();
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
        taskClicked (params) {
            this.$inertia.replace(this.route('tasks.edit', params.row.id));
        },
        newTask (category, email) {
            this.$store.workingTask = email ? email : { category: category };
            this.$inertia.replace(this.route('tasks.create'));
        },
        initializeListeners () {
            window.addEventListener('resize', event => {
                this.$refs.prototypeTable.reset();
                this.$refs.letteringTable.reset();
                this.$refs.swatchesTable.reset();
                this.$refs.vsfTable.reset();
                this.$refs.ozoneTable.reset();
            });
        },
    },
}
</script>
