<template>
    <layout title="Home">
        <div class="flex flex-col min-h-screen">
            <div class="flex flex-col mb-6">
                <div v-if="categoriesReady" class="flex flex-wrap w-full">
                    <div class="flex flex-wrap w-full">
                        <task-table :tasks="tasks.data" />
                    </div>
                </div>
                <div v-else class="flex flex-wrap w-full mb-8">
                    <h2 class="mb-2 font-semibold text-base text-gray-800 md:text-lg text-gray-800 uppercase">No categories defined.</h2>
                </div>
            </div>

            <!-- Email Table -->
            <div class="flex flex-col">
                <email-table :email="email" />
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
    props: ['tasks', 'email', 'filters'],
    store: ['activeCategory', 'activeCalendar', 'quantities'],
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
    created () {
        this.setGlobalFilters();
    },
    methods: {
        getTasksForCategory (category) {
            if (category) {
                return this.tasks.data[category].data;
            }

            return [];
        },
        setGlobalFilters () {
            this.$store.activeCategory = this.filters.category;
            this.$store.activeCalendar = this.filters.calendar;
        },
    },
}
</script>
