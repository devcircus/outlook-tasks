<template>
    <layout :title="`New Task`">
        <h1 class="mb-8 font-bold text-3xl">
            <inertia-link class="text-blue-300 hover:text-blue-700" :href="route('dashboard')">Tasks</inertia-link>
            <span class="text-blue-300 font-medium">/</span>
            <span class="text-blue-800 font-medium">{{ form.title }}</span>
        </h1>
        <div class="bg-white rounded shadow overflow-hidden w-3/5 mb-8">
            <form @submit.prevent="submit">
                <div class="p-8 -mr-6 -mb-8 flex w-full">
                    <div class="flex flex-col w-1/2">
                        <text-input v-model="form.title" :errors="$page.errors.title" class="pr-6 pb-8 w-full" label="Title" />
                        <select-input v-model="form.category" class="pr-6 pb-8 w-full lg:w-1/2" :error="$page.errors.category" label="Category">
                            <option value="none">None</option>
                            <option value="swatch">Swatch</option>
                            <option value="prototype">Prototype</option>
                            <option value="vsf">VSF</option>
                            <option value="ozone">Ozone</option>
                            <option value="lettering">Lettering</option>
                        </select-input>

                        <datepicker class="mb-6" :value="form.due_date" :errors="$page.errors.due_date" label="Due Date" position="datepicker-top" @input="setDate($event, 'due_date')" />
                        <checkbox v-model="form.complete" :errors="$page.errors.complete" label="Complete" :checked="form.complete" />
                    </div>
                    <div class="flex flex-col w-1/2">
                        <textarea-input v-model="form.description" :errors="$page.errors.description" rows="8" class="pr-6 pb-8 w-full" label="Description" />
                        <text-input v-model="form.report_to" :errors="$page.errors.report_to" class="pr-6 pb-8 w-full" label="Report To" />
                    </div>
                </div>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
                    <loading-button :loading="sending" class="btn-blue ml-auto" type="submit">Create Task</loading-button>
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
import SelectInput from '@/Shared/SelectInput';
import LoadingButton from '@/Shared/LoadingButton';
import TextareaInput from '@/Shared/TextareaInput';

export default {
    components: {
        Layout,
        Checkbox,
        TextInput,
        Datepicker,
        SelectInput,
        TextareaInput,
        LoadingButton,
    },
    store: ['workingTask'],
    remember: 'form',
    data () {
        return {
            sending: false,
            form: {
                title: null,
                description: null,
                due_date: null,
                report_to: null,
                complete: null,
                category: null,
                email_id: null,
            },
        }
    },
    created () {
        this.form.title = this.workingTask.subject;
        this.form.description = this.workingTask.body;
        this.form.report_to = this.workingTask.from_name;
        this.form.email_id = this.workingTask.id;
    },
    methods: {
        submit () {
            this.sending = true;
            this.$inertia.post(this.route('tasks.store'), this.form)
            .then(() => {
                this.sending = false;
                this.$store.workingTask = null;
             });
        },
        setDate (event, field) {
            this.form[field] = moment.utc(event).format('YYYY-MM-DD');
        },
    },
}
</script>
