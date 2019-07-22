<template>
    <div>
        <h1 class="mb-8 font-bold text-xl text-gray-700 md:text-2xl uppercase">Email</h1>
        <vue-good-table v-if="windowWidth >= 768" class="mb-8" :columns="emailColumns" :rows="emailRows" :row-style-class="rowClasses">
            <div slot="table-actions">
                <dropdown class="mt-1 mr-1" placement="bottom-end">
                    <div class="flex items-center cursor-pointer select-none group">
                        <div class="text-blue-900 group-hover:text-blue-700 focus:text-blue-700 mr-1 whitespace-no-wrap">
                            <span class="inline text-sm">Options</span>
                        </div>
                        <icon class="w-5 h-5 group-hover:fill-blue-700 fill-blue-900 focus:fill-blue-700" name="cheveron-down" />
                    </div>
                    <div slot="dropdown" class="mt-2 p-2 shadow-lg bg-white rounded">
                        <checkbox v-model="showTrashed" class="mb-2" label="Include archived emails: " :width="4" :height="4" :checked="showTrashed" @input="hideDropdown()" />
                    </div>
                </dropdown>
            </div>
            <div slot="emptystate">
                No emails found.
            </div>
            <template slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'actions'" class="flex justify-between px-3">
                    <button v-if="props.row.deleted_at" class="text-red-500 hover:underline" tabindex="-1" type="button" @click="restoreEmail(props.row.id)">Restore</button>
                    <button v-else class="text-red-500 hover:underline" tabindex="-1" type="button" @click="destroyEmail(props.row.id)">Delete</button>
                    <button class="text-green-500 hover:underline" tabindex="-1" type="button" @click="newTask(null, props.row)">New Task</button>
                    <button class="text-blue-500 hover:underline" tabindex="-1" type="button" @click="showEmail(props.row.id)">View</button>
                </span>
                <span v-if="props.row.deleted_at">
                    <span class="text-red-500">{{ props.formattedRow[props.column.field] }}</span>
                </span>
                <span v-else>
                    {{ props.formattedRow[props.column.field] }}
                </span>
            </template>
        </vue-good-table>
        <template v-else>
            <div class="border border-gray-400 mb-8">
                <div class="flex flex-col">
                    <checkbox v-model="showTrashed" class="mb-2 mr-2 self-end" label="Include archived emails: " :width="4" :height="4" :checked="showTrashed" />
                    <div v-for="email in emailRows" :key="email.id" class="flex flex-col bg-white p-3 border-b">
                        <span class="font-semibold text-gray-700 mb-2">Subject: <span class="font-normal">{{ email.subject }}</span></span>
                        <span class="font-semibold text-gray-700 mb-2">From: <span class="font-normal">{{ email.from_address }}</span></span>
                        <span class="font-semibold text-gray-700 mb-3">Received: <span class="font-normal">{{ email.received_at }}</span></span>
                        <span class="flex justify-between">
                            <button v-if="email.deleted_at" class="text-red-500 hover:underline" tabindex="-1" type="button" @click="restoreEmail(email.id)">Restore</button>
                            <button v-else class="text-red-500 hover:underline" tabindex="-1" type="button" @click="destroyEmail(email.id)">Delete</button>
                            <button class="text-green-500 hover:underline" tabindex="-1" type="button" @click="newTask(null, email)">New Task</button>
                            <button class="text-blue-500 hover:underline" tabindex="-1" type="button" @click="showEmail(email.id)">View</button>
                        </span>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import { filter } from 'lodash';
import Icon from '@/Shared/Icon';
import Checkbox from '@/Shared/Checkbox';
import Dropdown from '@/Shared/Dropdown';
import { VueGoodTable } from 'vue-good-table';

export default {
    components: {
        Icon,
        Checkbox,
        Dropdown,
        VueGoodTable,
    },
    props: {
        emails: Array,
    },
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
                return filter(this.emails, e => e.deleted_at === null);
            }

            return this.emails;
        },
    },
    created () {
        this.emailColumns = this.emailFields;
    },
    methods: {
        showEmail (id) {
            this.$inertia.replace(this.route('emails.show', id));
        },
        destroyEmail (id) {
            this.$modal.show('deleteEmailDialog', {
                title: 'Caution!',
                text: 'Are you sure you want to delete this email?',
                buttons: [
                    {
                        title: 'Delete Email',
                        type: 'delete',
                        handler: () => {
                            this.$inertia.delete(this.route('emails.destroy', id), { replace: false, preserveScroll: true, preserveState: true });
                            this.$modal.hide('deleteEmailDialog');
                         },
                    },
                    {
                        title: 'Close',
                        type: 'close',
                        handler: () => { this.$modal.hide('deleteEmailDialog') },
                    },
                ],
            });
        },
        restoreEmail (id) {
            this.$modal.show('restoreEmailDialog', {
                title: 'Notice!',
                text: 'Are you sure you want to restore this email?',
                buttons: [
                    {
                        title: 'Restore Email',
                        type: 'restore',
                        handler: () => {
                            this.$inertia.put(this.route('emails.restore', id), null, { replace: false, preserveScroll: true, preserveState: true });
                            this.$modal.hide('restoreEmailDialog');
                         },
                    },
                    {
                        title: 'Close',
                        type: 'close',
                        handler: () => { this.$modal.hide('restoreEmailDialog') },
                    },
                ],
            });
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
    },
}
</script>

<style>

</style>
