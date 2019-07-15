<template>
    <div>
        <h2 class="mb-2 font-semibold text-base text-gray-800 md:text-lg text-gray-800 uppercase">{{ category.display_name }}</h2>
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
                        <checkbox v-model="showTrashed" class="mb-2" label="Show trashed" :width="4" :height="4" :checked="showTrashed" @input="hideDropdown()" />
                        <span class="text-blue-500 font-semibold text-xs uppercase py-2 " @click="newTask(category.name)">New Task</span>
                    </div>
                </dropdown>
            </div>
        </vue-good-table>
    </div>
</template>

<script>
import { filter } from 'lodash';
import Icon from '@/Shared/Icon';
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
            showTrashed: false,
        }
    },
    computed: {
        rows () {
            if (! this.showTrashed) {
                return filter(this.tasks, t => t.deleted_at === null);
            }

            return this.tasks;
        },
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
