<template>
    <div>
        <div class="w-full flex items-center bg-blue-800 py-4 px-6">
            <h1 class="text-white text-lg md:text-xl font-semibold uppercase">Email</h1>
            <dropdown v-if="emailRows.length" class="ml-auto" placement="bottom-end">
                <div class="flex items-center cursor-pointer select-none group">
                    <div class="mr-1 whitespace-no-wrap">
                        <span class="inline text-white group-hover:text-blue-200 focus:text-blue-200 text-sm font-semibold">Options</span>
                    </div>
                    <icon-base icon-fill="fill-white" icon-name="cheveron-down" classes="group-hover:fill-blue-200 focus:fill-blue-200">
                        <cheveron-down />
                    </icon-base>
                </div>
                <div slot="dropdown" class="flex flex-col mt-2 p-2 shadow-lg bg-white rounded">
                    <checkbox v-model="showTrashed" class="text-xs text-red-600 hover:text-red-300 mb-2 ml-auto" label="Include deleted email" :width="4" :height="4" :checked="showTrashed" @input="hideDropdown()" />
                    <span class="text-xs text-red-800 hover:text-red-300 font-semibold ml-auto cursor-pointer" @click="deleteAll()">Delete All</span>
                </div>
            </dropdown>
        </div>
        <item-list v-if="windowWidth >= 768" :header-fields="emailFields" :data="emailRows" not-found-message="No Emails Found" entity-name="emails" row-action="show" :has-actions="true">
            <template slot-scope="props">
                <div class="group flex w-full items-center">
                    <span class="btn btn-text text-xs text-gray-300 group-hover:text-gray-500 mr-2 uppercase" @click="destroyEmail(props.item.id)">Delete</span>
                    <span class="text-gray-300 group-hover:text-gray-500">|</span>
                    <span class="btn btn-text text-xs text-gray-300 group-hover:text-gray-500 mr-2 uppercase cursor-pointer" :href="route('tasks.create', props.item.id)" @click="newTask(null, props.item)">New Task</span>
                </div>
            </template>
        </item-list>
        <template v-else>
            <div class="flex flex-col">
                <div v-for="item in emailRows" :key="item.id" class="flex flex-col p-6 border-b-2 border-blue-500" :class="backgroundColor(item)">
                    <span class="font-semibold text-gray-700 mb-4">Subject: <span class="font-normal">{{ item.subject }}</span></span>
                    <span class="font-semibold text-gray-700 mb-4">From: <span class="font-normal">{{ item.from_address }}</span></span>
                    <span class="font-semibold text-gray-700 mb-8">Received: <span class="font-normal">{{ item.received_at }}</span></span>
                    <div class="inline-flex">
                        <div v-if="item.deleted_at" class="group flex-initial">
                            <icon-base icon-fill="fill-green-500" icon-function="restore" classes="inline-block group-hover:fill-green-300 mr-1 cursor-pointer" view-box="1000 1000">
                                <restore />
                            </icon-base>
                            <button class="inline-block text-green-500 group-hover:text-green-300 font-semibold mr-8" tabindex="-1" type="button" @click="restoreEmail(item.id)">Restore</button>
                        </div>
                        <div v-else class="group flex-initial">
                            <icon-base icon-fill="fill-red-500" icon-function="trash" classes="inline-block group-hover:fill-red-300 mr-1 cursor-pointer" :width="14" :height="14">
                                <trash />
                            </icon-base>
                            <button class="inline-block text-red-500 group-hover:text-red-300 font-semibold mr-8" tabindex="-1" type="button" @click="destroyEmail(item.id)">Delete</button>
                        </div>
                        <div class="group flex-initial">
                            <icon-base icon-fill="fill-green-500" icon-function="add" classes="inline-block group-hover:fill-green-300 mr-1 cursor-pointer">
                                <list-add />
                            </icon-base>
                            <button class="inline-block text-green-500 group-hover:text-green-300 font-semibold" tabindex="-1" type="button" @click="newTask(null, item)">New Task</button>
                        </div>
                        <div class="group flex-initial">
                            <icon-base icon-fill="fill-blue-500" icon-function="view" classes="inline-block group-hover:fill-blue-300 mr-1 cursor-pointer">
                                <view-eye />
                            </icon-base>
                            <button class="inline-block text-blue-500 group-hover:text-blue-300 font-semibold" tabindex="-1" type="button" @click="showEmail(item.id)">View</button>
                        </div>
                    </div>
                </div>
                <div v-if="! emailRows.length" class="p-6 mb-8">
                    <span class="font-semibold text-gray-800 text-base">No Emails Found</span>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import { filter } from 'lodash';
import IconBase from '@/Shared/IconBase';
import Checkbox from '@/Shared/Checkbox';
import Dropdown from '@/Shared/Dropdown';
import ItemList from '@/Shared/ItemList';
import Trash from '@/Shared/Icons/Trash';
import Restore from '@/Shared/Icons/Restore';
import ViewEye from '@/Shared/Icons/ViewEye';
import ListAdd from '@/Shared/Icons/ListAdd';
import CheveronDown from '@/Shared/Icons/CheveronDown';

export default {
    components: {
        Trash,
        Restore,
        ViewEye,
        ListAdd,
        IconBase,
        Checkbox,
        Dropdown,
        ItemList,
        CheveronDown,
    },
    props: ['email'],
    data () {
        return {
            showTrashed: false,
            emailColumns: null,
        }
    },
    store: {
        emailFields: 'tables.fields.emailFields',
    },
    computed: {
        emailRows () {
            if (! this.showTrashed) {
                return filter(this.email, e => e.deleted_at === null);
            }

            return this.email;
        },
    },
    created () {
        this.emailColumns = this.emailFields;
    },
    methods: {
        showEmail (id) {
            this.$inertia.replace(this.route('emails.show', id));
        },
        restoreEmail (id) {
            this.$showDialog('warning', 'email', 'restore', () => {
                    this.$inertia.put(this.route('emails.restore', id), null, { replace: false, preserveScroll: true, preserveState: true });
                    this.$modal.hide('dialogModal');
                }
            );
        },
        destroyEmail (id) {
            this.$showDialog('warning', 'email', 'delete', () => {
                    this.$inertia.delete(this.route('emails.destroy', id), { replace: false, preserveScroll: true, preserveState: true });
                    this.$modal.hide('dialogModal');
                }
            );
        },
        deleteAll () {
            this.hideDropdown();
            this.$showDialog('warning', 'emails', 'delete', () => {
                    this.$inertia.delete(this.route('emails.destroy.all'), { replace: false, preserveScroll: true, preserveState: true });
                    this.$modal.hide('dialogModal');
                }, true
            );
        },
        rowClasses (row) {
            return 'cursor-pointer';
        },
        newTask (category, email) {
            this.$store.workingTask = email;
            this.$inertia.replace(this.route('tasks.create'));
        },
        hideDropdown () {
            this.$dispatch('dropdown-should-close');
        },
        backgroundColor (email) {
            if (this.showTrashed && email.deleted_at) {
                return 'bg-red-200';
            }

            return 'bg-gray-200 odd:bg-white';
        },
    },
}
</script>

<style>

</style>
