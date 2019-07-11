<template>
    <layout title="Home">
        <div class="flex flex-col min-h-screen">
            <h1 class="mb-8 font-bold text-blue-800 uppercase text-xl md:text-2xl">Tasks</h1>
            <div class="flex flex-wrap w-full">
                <vue-good-table class="mb-8" :columns="taskColumns" :rows="tasks" @on-row-click="taskClicked">
                    <div slot="emptystate">
                        No tasks found.
                    </div>
                </vue-good-table>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import { truncate } from '@/Helpers';
import { VueGoodTable } from 'vue-good-table';

export default {
    components: {
        Layout,
        VueGoodTable,
    },
    props: {
        displayName: {
            type: String,
            default: () => 'Guest',
        },
        tasks: {
            type: Array,
            default: () => ({}),
        },
    },
    data () {
        return {
            taskColumns: [
                {
                    field: 'due_date',
                    label: 'Due',
                },
                {
                    field: 'title',
                    label: 'Title',
                    formatFn: value => truncate(value, 50),
                },
                {
                    field: 'category_name',
                    label: 'Category',
                },
            ],
        }
    },
    methods: {
        rowClasses (row) {
            return 'cursor-pointer';
        },
        taskClicked (params) {
            this.$inertia.replace(this.route('tasks.edit', params.row.id));
        },
        newTask (email) {
            this.$store.workingTask = email;
            this.$inertia.replace(this.route('tasks.create'));
        },
    },
}
</script>
