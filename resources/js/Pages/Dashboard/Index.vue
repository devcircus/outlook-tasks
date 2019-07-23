<template>
    <layout title="Home">
        <div class="flex flex-col min-h-screen">
            <div class="flex flex-col">
                <!-- Task Tables -->
                <div class="flex">
                    <h1 class="mb-8 mr-2 font-bold text-xl text-gray-700 md:text-2xl uppercase">Tasks</h1>
                    <a :href="route('tasks.list.pdf')" class="btn btn-text text-xs text-blue-500 font-semibold px-0 pt-2" target="_blank">[PDF]</a>
                </div>
                <div v-if="categoriesReady" class="flex flex-wrap w-full md:-mx-2">
                    <div v-for="category in taskCategories" :key="category.id" class="flex flex-col w-full md:w-1/3 md:px-2">
                        <task-table :category="category" :tasks="getTasksForCategory(category.name)" />
                    </div>
                </div>
                <div v-else class="flex flex-wrap w-full mb-8">
                    <h2 class="mb-2 font-semibold text-base text-gray-800 md:text-lg text-gray-800 uppercase">No categories defined.</h2>
                </div>
            </div>

            <!-- Email Table -->
            <div class="flex flex-col">
                <email-table :emails="emails" />
            </div>
        </div>
    </layout>
</template>

<script>
import { filter } from 'lodash';
import Layout from '@/Shared/Layout';
import TaskTable from '@/Partials/Tasks/TaskTable';
import EmailTable from '@/Partials/Emails/EmailTable';

export default {
    components: {
        Layout,
        TaskTable,
        EmailTable,
    },
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
    computed: {
        categoriesReady () {
            return this.$page.categories.ready;
        },
        taskCategories () {
            return filter(this.$page.categories.data, category => {
                return category.deleted_at === null && category.has_definition;
            });
        },
    },
    methods: {
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
        hideDropdown () {
            this.$dispatch('dropdown-should-close');
        },
    },
}
</script>
