<template>
    <div>
        <div class="flex">
            <h2 class="mb-2 mr-2 font-semibold text-base text-gray-800 md:text-lg text-gray-800 uppercase">{{ category.display_name }}</h2>
            <a :href="route('tasks.list.pdf', { type: category.name })" class="btn btn-text text-xs text-blue-500 font-semibold px-0 pt-1" target="_blank">[PDF]</a>
        </div>
        <vue-good-table ref="table" class="mb-8" :columns="taskColumns" :rows="rows" @on-row-click="taskClicked">
            <div slot="emptystate">
                No {{ category.name }} tasks found.
            </div>
            <div slot="table-actions">
                <dropdown class="mt-1 mr-1" placement="bottom-end">
                    <div class="flex items-center cursor-pointer select-none group">
                        <div class="text-blue-900 group-hover:text-blue-700 focus:text-blue-700 mr-1 whitespace-no-wrap">
                            <span class="inline text-sm">Options</span>
                        </div>
                        <icon class="w-5 h-5 group-hover:fill-blue-700 fill-blue-900 focus:fill-blue-700" name="cheveron-down" />
                    </div>
                    <div slot="dropdown" class="mt-2 p-2 shadow-lg bg-white rounded">
                        <checkbox v-model="showActive" class="mb-2" label="Include active tasks: " :width="4" :height="4" :checked="showActive" @input="hideDropdown()" />
                        <checkbox v-model="showCompleted" class="mb-2" label="Include completed tasks: " :width="4" :height="4" :checked="showCompleted" @input="hideDropdown()" />
                        <checkbox v-model="showTrashed" class="mb-2" label="Include archived tasks: " :width="4" :height="4" :checked="showTrashed" @input="hideDropdown()" />
                        <span class="text-blue-500 font-semibold text-xs uppercase py-2 cursor-pointer" @click="newTask(category)">New Task</span>
                    </div>
                </dropdown>
            </div>
            <template slot="table-row" slot-scope="props">
                <span v-if="props.row.deleted_at">
                    <span class="text-red-500">{{ props.formattedRow[props.column.field] }}</span>
                </span>
                <span v-else-if="props.row.complete">
                    <span class="text-green-500">{{ props.formattedRow[props.column.field] }}</span>
                </span>
                <span v-else>
                    {{ props.formattedRow[props.column.field] }}
                </span>
            </template>
        </vue-good-table>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon';
import { filter, concat, orderBy } from 'lodash';
import Dropdown from '@/Shared/Dropdown';
import Checkbox from '@/Shared/Checkbox.js';
import { VueGoodTable } from 'vue-good-table';

export default {
    components: {
        Icon,
        Checkbox,
        Dropdown,
        VueGoodTable,
    },
    props: {
        category: {
            type: Object,
            default: () => ({}),
        },
        tasks: Array,
    },
    data () {
        return {
            taskColumns: null,
            showActive: true,
            showCompleted: false,
            showTrashed: false,
        }
    },
    computed: {
        rows () {
            const active = filter(this.tasks, task => {
                return task.deleted_at === null && ! task.complete;
            });
            const trashed = filter(this.tasks, t => t.deleted_at != null);
            const completed = filter(this.tasks, t => t.complete === true);

            if (this.showActive) {
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
            }
            if (this.showTrashed) {
                if (this.showCompleted) {
                    let results = concat(trashed, completed);

                    return orderBy(results, ({ due_date }) => due_date || '', ['asc']);
                }
                return trashed;
            }
            if (this.showCompleted) {
                return completed;
            }

            return [];
        }
    },
    store: ['tables'],
    created () {
        this.taskColumns = this.tables.fields.taskFields;
        this.initializeListeners();
    },
    methods: {
        taskClicked (params) {
            this.$inertia.replace(this.route('tasks.edit', params.row.id));
        },
        newTask (category) {
            this.$store.workingTask = { category: category };
            this.$inertia.replace(this.route('tasks.create'));
        },
        initializeListeners () {
            window.addEventListener('resize', event => {
                this.$refs.table.reset();
            });
        },
        hideDropdown () {
            this.$dispatch('dropdown-should-close');
        },
    },
}
</script>

<style>

</style>
