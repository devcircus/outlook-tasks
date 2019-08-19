<template>
    <layout title="Today's Tasks">
        <div class="flex flex-col min-h-screen w-full">
            <h1 class="mb-8 font-bold text-blue-800 uppercase text-xl md:text-2xl">Tasks for today</h1>
            <div class="flex flex-wrap w-3/5">
                <task-table :tasks="getTasks()" pdf-title="today" />
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import TaskTable from '@/Partials/Tasks/TaskTable';

export default {
    components: {
        Layout,
        TaskTable,
    },
    props: {
        tasks: {
            type: Array,
            default: () => ({}),
        },
    },
    methods: {
        rowClasses (row) {
            return 'cursor-pointer';
        },
        taskClicked (params) {
            this.$inertia.replace(this.route('tasks.edit', params.row.id));
        },
        getTasks () {
            return this.tasks;
        },
    },
}
</script>
