<template>
    <layout :title="`Show Task`">
        <div class="w-full flex bg-blue-800 p-4">
            <inertia-link class="text-lg md:text-xl text-blue-300 hover:text-white uppercase mr-1" :href="route('dashboard')">Dashboard</inertia-link>
            <span class="text-lg md:text-xl text-blue-300 font-medium mr-1">></span>
            <h1 class="text-white text-lg md:text-xl font-semibold uppercase">{{ form.title }}</h1>
        </div>
        <trashed-message v-if="task.deleted_at" class="mb-6" @restore="restore">
            This task has been deleted.
        </trashed-message>
        <div class="flex flex-col bg-white rounded shadow overflow-hidden w-full md:w-3/5 mb-8">
            <div class="w-full flex">
                <a :href="route('tasks.pdf', task.id)" class="btn btn-text text-blue-500 font-semibold ml-auto" target="_blank">PDF</a>
            </div>
            <form @submit.prevent="submit">
                <div class="p-8 -mr-6 -mb-8 flex flex-col md:flex-row md:flex-wrap w-full">
                    <text-input v-model="form.title" :errors="getErrors('title')" class="md:pr-6 pb-8 w-full md:w-1/2" label="Title" />
                    <select-input v-model="form.category" class="md:pr-6 pb-8 w-full md:w-1/2" :errors="getErrors('category')" label="Category">
                        <option v-for="type in categories" :key="type.id" :value="type.name" :selected="type.name === form.category ? 'selected' : ''">{{ type.name|capitalize }}</option>
                    </select-input>
                    <div class="w-full md:w-1/2 md:pr-6">
                        <datepicker class="mb-6 w-full" :value="form.due_date" :errors="getErrors('due_date')" label="Due Date" @input="setDate($event, 'due_date')" />
                        <checkbox v-model="form.complete" class="mb-8 md:mb-0" :errors="getErrors('complete')" label="Complete" :checked="form.complete" />
                    </div>
                    <textarea-input v-model="form.description" :errors="getErrors('description')" rows="8" class="md:pr-6 pb-8 w-full md:w-1/2" label="Description" />
                    <text-input v-model="form.report_to" :errors="getErrors('report_to')" class="md:pr-6 pb-8 w-full md:w-1/2" label="Report To" />
                </div>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
                    <button v-if="! task.deleted_at" class="text-red-500 hover:underline mr-8" tabindex="-1" type="button" @click="destroy">Delete Task</button>
                    <button class="text-blue-500 hover:underline" tabindex="-1" type="button" @click="email">Email Task</button>
                    <loading-button :loading="sending" class="btn-blue ml-auto" type="submit">Update Task</loading-button>
                </div>
            </form>
        </div>
    </layout>
</template>

<script>
import { filter } from 'lodash';
import moment from 'moment-timezone';
import Layout from '@/Shared/Layout';
import Checkbox from '@/Shared/Checkbox';
import TextInput from '@/Shared/TextInput';
import Datepicker from '@/Shared/Datepicker';
import SelectInput from '@/Shared/SelectInput';
import LoadingButton from '@/Shared/LoadingButton';
import TextareaInput from '@/Shared/TextareaInput';
import TrashedMessage from '@/Shared/TrashedMessage';

export default {
    components: {
        Layout,
        Checkbox,
        TextInput,
        Datepicker,
        SelectInput,
        TextareaInput,
        LoadingButton,
        TrashedMessage,
    },
    props: {
        task: Object,
    },
    remember: 'form',
    data () {
        return {
            sending: false,
            submitted: false,
            form: {
                id: this.task.id,
                title: this.task.title,
                category: this.task.category.name,
                due_date: this.formatDate(this.task.due_date),
                description: this.task.description,
                report_to: this.task.report_to,
                complete: this.task.complete,
            },
        }
    },
    computed: {
        categories () {
            return filter(this.$page.categories.data, t => t.deleted_at === null);
        },
    },
    methods: {
        submit () {
            this.sending = true;
            this.submitted = true;
            this.$inertia.put(this.route('tasks.update', this.task.id), this.form)
            .then(() => {
                this.sending = false;
             });
        },
        email () {
            this.$modal.show('emailTaskModal', {
                task: this.task,
            });
        },
        restore () {
            this.$showDialog('warning', 'task', 'restore', () => {
                    this.$inertia.put(this.route('tasks.restore', this.task.id), null, { replace: false, preserveScroll: true, preserveState: true });
                    this.$modal.hide('dialogModal');
                }
            );
        },
        destroy () {
            this.$showDialog('warning', 'task', 'delete', () => {
                    this.$inertia.delete(this.route('tasks.destroy', this.task.id), { replace: false, preserveScroll: true, preserveState: true });
                    this.$modal.hide('dialogModal');
                }
            );
        },
        setDate (event, field) {
            this.form[field] = moment.utc(event).format('YYYY-MM-DD');
        },
        formatDate (date) {
            if (date) {
                return moment.utc(date).format('YYYY-MM-DD');
            }

            return null;
        },
    },
}
</script>
