<template>
    <div class="bg-white rounded shadow overflow-hidden w-full md:w-1/2 mb-8 md:mr-2">
        <vue-good-table ref="categoryTable" class="mb-8" :columns="categoryColumns" :rows="rows">
            <div slot="table-actions">
                <dropdown class="mt-1 mr-1" placement="bottom-end">
                    <div class="flex items-center cursor-pointer select-none group">
                        <div class="text-blue-900 group-hover:text-blue-700 focus:text-blue-700 mr-1 whitespace-no-wrap">
                            <span class="inline text-sm">Options</span>
                        </div>
                        <icon class="w-5 h-5 group-hover:fill-blue-700 fill-blue-900 focus:fill-blue-700" name="cheveron-down" />
                    </div>
                    <div slot="dropdown" class="mt-2 p-2 shadow-lg bg-white rounded">
                        <checkbox v-model="showTrashed" class="mb-2" label="Include archived categories: " :width="4" :height="4" :checked="showTrashed" @input="hideDropdown()" />
                    </div>
                </dropdown>
            </div>
            <div slot="emptystate">
                No categories found.
            </div>
            <template slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'actions'" class="flex justify-between px-3">
                    <button class="text-blue-500 hover:underline" tabindex="-1" type="button" @click="viewCategory(props.row.id)">View</button>
                    <button v-if="props.row.deleted_at" class="text-red-500 hover:underline" tabindex="-1" type="button" @click="restoreCategory(props.row.id)">Restore</button>
                    <button v-else class="text-red-500 hover:underline" tabindex="-1" type="button" @click="destroyCategory(props.row.id)">Delete</button>
                </span>
                <span v-else>
                    {{ props.formattedRow[props.column.field] }}
                </span>
            </template>
        </vue-good-table>
    </div>
</template>

<script>
import { filter } from 'lodash';
import Icon from '@/Shared/Icon';
import Dropdown from '@/Shared/Dropdown';
import Checkbox from '@/Shared/Checkbox';
import { VueGoodTable } from 'vue-good-table';

export default {
    components: {
        Icon,
        Dropdown,
        Checkbox,
        VueGoodTable,
    },
    store: ['tables'],
    data () {
        return {
            categoryColumns: null,
            showTrashed: false,
        }
    },
    computed: {
        rows () {
            if (! this.showTrashed) {
                return filter(this.$page.categories.data, t => t.deleted_at === null);
            }

            return this.$page.categories.data;
        },
    },
    created () {
        this.categoryColumns = this.tables.fields.categoryFields;
    },
    methods: {
        viewCategory (id) {
            this.$inertia.replace(this.route('categories.show', id));
        },
        destroyCategory (id) {
            this.$modal.show('deleteCategoryDialog', {
                title: 'Caution!',
                text: 'Are you sure you want to delete this category? All tasks and emails with this category will also be deleted.',
                buttons: [
                    {
                        title: 'Delete Category',
                        type: 'delete',
                        handler: () => {
                            this.$inertia.delete(this.route('categories.destroy', id), { replace: false, preserveScroll: true, preserveState: true });
                            this.$modal.hide('deleteCategoryDialog');
                         },
                    },
                    {
                        title: 'Close',
                        type: 'close',
                        handler: () => { this.$modal.hide('deleteCategoryDialog') },
                    },
                ],
            });
        },
        restoreCategory (id) {
            this.$modal.show('restoreCategoryDialog', {
                title: 'Notice!',
                text: 'Are you sure you want to restore this category?',
                buttons: [
                    {
                        title: 'Restore Category',
                        type: 'restore',
                        handler: () => {
                            this.$inertia.put(this.route('categories.restore', id));
                            this.$modal.hide('restoreCategoryDialog');
                         },
                    },
                    {
                        title: 'Close',
                        type: 'close',
                        handler: () => { this.$modal.hide('restoreCategoryDialog') },
                    },
                ],
            });
        },
        hideDropdown () {
            this.$dispatch('dropdown-should-close');
        },
    },

}
</script>
