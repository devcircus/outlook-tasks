<template>
    <layout :title="`Show Task`">
        <h1 class="mb-8 font-bold text-xl md:text-3xl">
            <inertia-link class="text-blue-300 hover:text-blue-700" :href="route('dashboard')">Dashboard</inertia-link>
            <span class="text-blue-300 font-medium">/</span>
            <span class="text-blue-800 font-medium">{{ form.title }}</span>
        </h1>
        <trashed-message v-if="task.deleted_at" class="mb-6" @restore="restore">
            This task has been deleted.
        </trashed-message>
        <div class="bg-white rounded shadow overflow-hidden w-full md:w-3/5 mb-8">
            <form @submit.prevent="submit">
                <div class="p-8 -mr-6 -mb-8 flex flex-col md:flex-row w-full">
                    <div class="flex flex-col w-full md:w-1/2">
                        <text-input v-model="form.title" :errors="$page.errors.title" class="pr-6 pb-8 w-full" label="Title" />
                        <div class="mb-8 block font-semibold text-sm">
                            <span class="text-gray-800 mr-2">Category: </span>
                            <span class="text-blue-600 uppercase">{{ task.category.name }}</span>
                        </div>

                        <datepicker class="mb-6" :value="form.due_date" :errors="$page.errors.due_date" label="Due Date" position="datepicker-top" @input="setDate($event, 'due_date')" />
                        <checkbox v-model="form.complete" class="mb-8 md:mb-0" :errors="$page.errors.complete" label="Complete" :checked="form.complete" />
                    </div>
                    <div class="flex flex-col w-full md:w-1/2">
                        <textarea-input v-model="form.description" :errors="$page.errors.description" rows="8" class="pr-6 pb-8 w-full" label="Description" />
                        <text-input v-model="form.report_to" :errors="$page.errors.report_to" class="pr-6 pb-8 w-full" label="Report To" />
                    </div>
                </div>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
                    <button v-if="! task.deleted_at" class="text-red-500 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Task</button>
                    <loading-button :loading="sending" class="btn-blue ml-auto" type="submit">Update Task</loading-button>
                </div>
            </form>
        </div>
    </layout>
</template>

<script>
import moment from 'moment-timezone';
import Layout from '@/Shared/Layout';
import Checkbox from '@/Shared/Checkbox';
import TextInput from '@/Shared/TextInput';
import Datepicker from '@/Shared/Datepicker';
import LoadingButton from '@/Shared/LoadingButton';
import TextareaInput from '@/Shared/TextareaInput';
import TrashedMessage from '@/Shared/TrashedMessage';

export default {
    components: {
        Layout,
        Checkbox,
        TextInput,
        Datepicker,
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
            form: {
                id: this.task.id,
                title: this.task.title,
                due_date: this.task.due_date,
                description: this.task.description,
                report_to: this.task.report_to,
                complete: this.task.complete,
            },
        }
    },
    methods: {
        submit () {
            this.sending = true;
            this.$inertia.put(this.route('tasks.update', this.task.id), this.form)
            .then(() => {
                this.sending = false;
             });
        },
        destroy () {
            if (confirm('Are you sure you want to delete this task?')) {
                this.$inertia.delete(this.route('tasks.destroy', this.task.id))
            }
        },
        restore () {
            if (confirm('Are you sure you want to restore this task?')) {
                this.$inertia.put(this.route('tasks.restore', this.task.id))
            }
        },
        setDate (event, field) {
            this.form[field] = moment.utc(event).format('YYYY-MM-DD');
        },
    },
}
</script>
