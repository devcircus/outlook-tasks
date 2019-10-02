<template>
    <div class="bg-white rounded shadow overflow-hidden w-full lg:w-1/2 mb-8">
        <div class="w-full flex items-center bg-blue-800 py-4 px-6">
            <h1 class="text-white text-lg md:text-xl font-semibold uppercase">Categories</h1>
            <dropdown class="ml-auto" placement="bottom-end">
                <div class="flex items-center cursor-pointer select-none group">
                    <div class="mr-1 whitespace-no-wrap">
                        <span class="inline text-white group-hover:text-blue-200 focus:text-blue-200 text-sm font-semibold">Options</span>
                    </div>
                    <icon-base icon-fill="fill-white" icon-name="cheveron-down" classes="group-hover:fill-blue-200 focus:fill-blue-200">
                        <cheveron-down />
                    </icon-base>
                </div>
                <div slot="dropdown" class="flex flex-col mt-2 p-2 shadow-lg bg-white rounded">
                    <checkbox v-model="showTrashed" class="text-xs text-red-600 hover:text-red-300 mb-1 ml-auto" label="Include deleted categories" :width="4" :height="4" :checked="showTrashed" @input="hideDropdown()" />
                </div>
            </dropdown>
        </div>
        <item-list v-if="windowWidth >= 768" :header-fields="categoryColumns" :data="rows" not-found-message="No Categories Found" entity-name="categories" row-action="show" :has-actions="true">
            <template slot-scope="props">
                <div class="group flex w-full items-center">
                    <button v-if="props.item.deleted_at" class="btn btn-text text-xs text-gray-300 group-hover:text-gray-500 mr-2 uppercase cursor-pointer" tabindex="-1" type="button" @click="restoreCategory(props.item.id)">Restore</button>
                    <span v-else class="btn btn-text text-xs text-gray-300 group-hover:text-gray-500 mr-2 uppercase cursor-pointer" @click="destroyCategory(props.item.id)">Delete</span>
                    <span class="text-gray-300 group-hover:text-gray-500">|</span>
                    <span class="btn btn-text text-xs text-gray-300 group-hover:text-gray-500 mr-2 uppercase cursor-pointer" @click="viewCategory(props.item.id)">View</span>
                </div>
            </template>
        </item-list>
    </div>
</template>

<script>
import { filter } from 'lodash';
import IconBase from '@/Shared/IconBase';
import Dropdown from '@/Shared/Dropdown';
import Checkbox from '@/Shared/Checkbox';
import ItemList from '@/Shared/ItemList';
import CheveronDown from '@/Shared/Icons/CheveronDown';

export default {
    components: {
        IconBase,
        Dropdown,
        Checkbox,
        ItemList,
        CheveronDown,
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
        restoreCategory (id) {
            this.$showDialog('warning', 'category', 'restore', () => {
                    this.$inertia.put(this.route('categories.restore', id), null, { replace: false, preserveScroll: true, preserveState: true });
                    this.$modal.hide('dialogModal');
                }
            );
        },
        destroyCategory (id) {
            this.$showDialog('warning', 'category', 'delete', () => {
                    this.$inertia.delete(this.route('categories.destroy', id), { replace: false, preserveScroll: true, preserveState: true });
                    this.$modal.hide('dialogModal');
                }
            );
        },
        hideDropdown () {
            this.$dispatch('dropdown-should-close');
        },
    },

}
</script>
