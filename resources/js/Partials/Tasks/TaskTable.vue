<template>
    <div class="w-full">
        <div class="w-full flex bg-blue-800 py-4 px-6">
            <h1 class="text-white text-lg md:text-xl font-semibold uppercase">{{ title }}</h1>
            <dropdown v-if="rows.length" class="ml-auto" placement="bottom-end">
                <div class="flex items-center cursor-pointer select-none group">
                    <div class="mr-1 whitespace-no-wrap">
                        <span class="inline text-white group-hover:text-blue-200 focus:text-blue-200 text-sm font-semibold">Options</span>
                    </div>
                    <icon-base icon-fill="fill-white" icon-name="cheveron-down" classes="group-hover:fill-blue-200 focus:fill-blue-200">
                        <cheveron-down />
                    </icon-base>
                </div>
                <div slot="dropdown" class="flex flex-col items-start mt-2 p-2 shadow-lg bg-white rounded">
                    <checkbox v-model="showTrashed"
                              class="text-sm text-red-600 hover:text-red-300 mb-1 w-full"
                              label="Include deleted tasks"
                              :width="4"
                              :height="4"
                              :checked="showTrashed"
                              @input="hideDropdown()"
                    />
                    <checkbox v-model="showCompleted"
                              class="text-sm text-green-600 hover:text-green-300 mb-3 w-full"
                              label="Include completed tasks"
                              :width="4"
                              :height="4"
                              :checked="showCompleted"
                              @input="hideDropdown()"
                    />
                    <a :href="route('tasks.list.pdf', { type: category.name })" class="text-sm text-blue-600 hover:text-blue-300 font-semibold mb-3" target="_blank">Printable [PDF]</a>
                    <span class="text-sm text-green-600 hover:text-green-300 font-semibold cursor-pointer" @click="newTask()">New Task</span>
                </div>
            </dropdown>
        </div>
        <div class="rounded shadow overflow-hidden w-full">
            <item-list v-if="windowWidth >= 768" :header-fields="taskColumns" :data="rows" not-found-message="No Tasks Found" entity-name="tasks" row-action="edit" :has-actions="true" :mapped-row-classes="mappedRowClasses()">
                <template slot-scope="props">
                    <div class="inline-flex">
                        <div v-if="props.item.deleted_at" class="group flex-initial">
                            <icon-base icon-fill="fill-green-500" icon-function="restore" classes="inline-block group-hover:fill-green-300 mr-1 cursor-pointer">
                                <restore />
                            </icon-base>
                            <button class="inline-block text-green-500 group-hover:text-green-300 font-semibold mr-8" tabindex="-1" type="button" @click="restoreTask(props.item.id)">Restore</button>
                        </div>
                        <div v-else class="group flex-initial">
                            <icon-base icon-fill="fill-red-500" icon-function="trash" classes="inline-block group-hover:fill-red-300 mr-1 cursor-pointer" :width="14" :height="14">
                                <trash />
                            </icon-base>
                            <button class="inline-block text-red-500 group-hover:text-red-300 font-semibold mr-8" tabindex="-1" type="button" @click="destroyTask(props.item.id)">Delete</button>
                        </div>
                        <div class="group flex-initial">
                            <icon-base icon-fill="fill-blue-500" icon-function="view" classes="inline-block group-hover:fill-blue-300 mr-1 cursor-pointer">
                                <view-eye />
                            </icon-base>
                            <button class="inline-block text-blue-500 group-hover:text-blue-300 font-semibold" tabindex="-1" type="button" @click="showTask(props.item.id)">View</button>
                        </div>
                    </div>
                </template>
            </item-list>

            <template v-else>
                <div class="flex flex-col">
                    <div v-for="task in rows" :key="task.id" class="flex flex-col p-6 border-b-2 border-blue-500" :class="backgroundColor(task)">
                        <span class="font-semibold text-gray-700 mb-4">Due Date: <span class="font-normal">{{ task.due_date }}</span></span>
                        <span class="font-semibold text-gray-700 mb-4">Title: <span class="font-normal">{{ task.title }}</span></span>
                        <span class="font-semibold text-gray-700 mb-8">Report To: <span class="font-normal">{{ task.report_to }}</span></span>
                        <div class="inline-flex">
                            <div v-if="task.deleted_at" class="group flex-initial">
                                <icon-base icon-fill="fill-green-500" icon-function="restore" classes="inline-block group-hover:fill-green-300 mr-1 cursor-pointer">
                                    <restore />
                                </icon-base>
                                <button class="inline-block text-green-500 group-hover:text-green-300 font-semibold mr-8" tabindex="-1" type="button" @click="restoreTask(task.id)">Restore</button>
                            </div>
                            <div v-else class="group flex-initial">
                                <icon-base icon-fill="fill-red-500" icon-function="trash" classes="inline-block group-hover:fill-red-300 mr-1 cursor-pointer" :width="14" :height="14">
                                    <trash />
                                </icon-base>
                                <button class="inline-block text-red-500 group-hover:text-red-300 font-semibold mr-8" tabindex="-1" type="button" @click="destroyTask(task.id)">Delete</button>
                            </div>
                            <div class="group flex-initial">
                                <icon-base icon-fill="fill-blue-500" icon-function="view" classes="inline-block group-hover:fill-blue-300 mr-1 cursor-pointer">
                                    <view-eye />
                                </icon-base>
                                <button class="inline-block text-blue-500 group-hover:text-blue-300 font-semibold" tabindex="-1" type="button" @click="showTask(task.id)">View</button>
                            </div>
                        </div>
                    </div>
                    <div v-if="! rows.length" class="p-6 mb-8">
                        <span class="font-semibold text-gray-800 text-base">No Tasks Found</span>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import IconBase from '@/Shared/IconBase';
import ItemList from '@/Shared/ItemList';
import Dropdown from '@/Shared/Dropdown';
import Trash from '@/Shared/Icons/Trash';
import Checkbox from '@/Shared/Checkbox.js';
import ViewEye from '@/Shared/Icons/ViewEye';
import Restore from '@/Shared/Icons/Restore';
import { filter, concat, orderBy } from 'lodash';
import CheveronDown from '@/Shared/Icons/CheveronDown';

export default {
    components: {
        Trash,
        ViewEye,
        Restore,
        IconBase,
        Checkbox,
        Dropdown,
        ItemList,
        CheveronDown,
    },
    props: ['tasks'],
    store: ['tables', 'activeCategory', 'activeCalendar'],
    data () {
        return {
            taskColumns: null,
            showCompleted: false,
            showTrashed: false,
        }
    },
    computed: {
        category () {
            return this.activeCategory === 'all' ? 'all' : this.$page.categories.data[this.activeCategory];
        },
        pdfTitle () {
            if (this.category === 'all') {
                return 'All ' + this.getFormattedCalendar();
            }

            return this.category.name + ' ' + this.getFormattedCalendar();
        },
        title () {
            return this.pdfTitle ? this.pdfTitle : this.category.name + ' ' + this.getFormattedCalendar();
        },
        rows () {
            const active = filter(this.tasks, task => {
                return task.deleted_at === null && ! task.complete;
            });
            const trashed = filter(this.tasks, task => {
                return task.deleted_at != null;
            });
            const completed = filter(this.tasks, task => {
                return task.complete === true && task.deleted_at === null;
            });

            if (this.showTrashed) {
                if (this.showCompleted) {
                    let results = concat(active, trashed, completed);

                    return orderBy(results, ({ due_date }) => due_date || '', ['asc']);
                }
                let results = concat(active, trashed);

                return orderBy(results, ({ due_date }) => due_date || '', ['asc']);
            }

            if (this.showCompleted) {
                let results = concat(active, completed);

                return orderBy(results, ({ due_date }) => due_date || '', ['asc']);
            }

            return active;
        },
    },
    created () {
        this.setTaskColumns();
    },
    methods: {
        setTaskColumns () {
            if (this.activeCategory === 'all') {
                return this.taskColumns = [...this.tables.fields.taskFields, { name: 'category_name', label: 'Category' }];
            }
            return this.taskColumns = this.tables.fields.taskFields;
        },
        showTask (id) {
            this.$inertia.replace(this.route('tasks.edit', id));
        },
        newTask () {
            this.$inertia.visit(this.route('tasks.create.from_category', this.activeCategory));
        },
        hideDropdown () {
            this.$dispatch('dropdown-should-close');
        },
        restoreTask (id) {
            this.$showDialog('warning', 'task', 'restore', () => {
                    this.$inertia.put(this.route('tasks.restore', id), null, { replace: false, preserveScroll: true, preserveState: true });
                    this.$modal.hide('dialogModal');
                }
            );
        },
        destroyTask (id) {
            this.$showDialog('warning', 'task', 'delete', () => {
                    this.$inertia.delete(this.route('tasks.destroy', id), { replace: false, preserveScroll: true, preserveState: true });
                    this.$modal.hide('dialogModal');
                }
            );
        },
        getFormattedCalendar () {
            var calendar;

            switch (this.activeCalendar) {
                case 'none':
                    calendar = '';
                    break;

                case 'today':
                    calendar = 'Due Today';
                    break;

                case 'overdue':
                    calendar = 'Overdue';
                    break;

                case 'week':
                    calendar = 'Due This Week';
                    break;

                default:
                    calendar = '';
                    break;
            }

            return calendar;
        },
        backgroundColor (task) {
            if (this.showTrashed && task.deleted_at) {
                return 'bg-red-200';
            }
            if (this.showCompleted && task.complete) {
                return 'bg-green-200';
            }

            return 'bg-gray-200 odd:bg-white';
        },
        mappedRowClasses () {
            return {
                complete: 'text-green-500',
                'deleted_at': 'text-red-500',
            };
        },
    },
}
</script>

<style>

</style>
