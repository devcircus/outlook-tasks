<template>
    <layout title="Home">
        <div class="flex flex-col min-h-screen">
            <h1 class="mb-8 font-bold text-xl md:text-2xl">
                <inertia-link class="text-blue-500 hover:text-blue-800 uppercase" :href="route('dashboard')">Tasks</inertia-link>
            </h1>
            <div class="flex flex-wrap w-full -mx-2">
                <div class="flex flex-col w-full md:w-1/3 md:px-2">
                    <h2 class="mb-2 font-semibold text-lg md:text-xl text-gray-800">Prototypes</h2>
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
                    <h2 class="mb-2 font-semibold text-lg md:text-xl text-gray-800">Lettering</h2>
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
                    <h2 class="mb-2 font-semibold text-lg md:text-xl text-gray-800">Swatches</h2>
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
                    <h2 class="mb-2 font-semibold text-lg md:text-xl text-gray-800">VSF</h2>
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
                    <h2 class="mb-2 font-semibold text-lg md:text-xl text-gray-800">Ozone</h2>
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
            <h1 class="mb-2 font-bold text-xl md:text-2xl">
                <inertia-link class="text-blue-500 hover:text-blue-800 uppercase" :href="route('dashboard')">Email</inertia-link>
            </h1>
            <vue-good-table class="mb-8" :columns="emailColumns" :rows="emails" :row-style-class="rowClasses">
                <div slot="emptystate">
                    No emails found.
                </div>
                <template slot="table-row" slot-scope="props">
                    <span v-if="props.column.field == 'actions'" class="hidden md:flex justify-between px-3">
                        <button class="text-red-500 hover:underline" tabindex="-1" type="button" @click="destroyEmail(props.row.id)">Delete</button>
                        <button class="text-green-500 hover:underline" tabindex="-1" type="button" @click="newTask(null, props.row)">New Task</button>
                        <button class="text-blue-500 hover:underline" tabindex="-1" type="button" @click="showEmail(props.row.id)">View</button>
                    </span>
                    <span v-else>
                        {{ props.formattedRow[props.column.field] }}
                    </span>
                </template>
            </vue-good-table>
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
