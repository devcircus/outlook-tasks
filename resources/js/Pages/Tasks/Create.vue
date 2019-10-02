<template>
    <layout :title="`New Task`">
        <div class="w-full flex bg-blue-800 p-4">
            <inertia-link class="text-lg md:text-xl text-blue-300 hover:text-white uppercase mr-1" :href="route('dashboard')">Dashboard</inertia-link>
            <span class="text-lg md:text-xl text-blue-300 font-medium mr-1">></span>
            <h1 class="text-white text-lg md:text-xl font-semibold uppercase">{{ form.title ? form.title : 'New Task' }}</h1>
        </div>
        <div class="bg-white rounded shadow overflow-hidden w-full">
            <form @submit.prevent="submit">
                <div class="p-8 -mr-6 -mb-8 flex flex-col md:flex-row md:flex-wrap w-full">
                    <text-input v-model="form.title" :errors="getErrors('title')" class="md:pr-6 pb-8 w-full md:w-1/2" label="Title" />
                    <select-input v-model="form.category" class="md:pr-6 pb-8 w-full md:w-1/2" :errors="getErrors('category')" label="Category">
                        <option v-for="type in categories" :key="type.id" :value="type.name" :selected="type.name === form.category ? 'selected' : ''">{{ type.name|capitalize }}</option>
                    </select-input>
                    <div class="w-full md:w-1/2 md:pr-6">
                        <datepicker class="mb-6 w-full" :value="form.due_date" :errors="getErrors('due_date')" label="Due Date" @input="setDate($event, 'due_date')" />
                        <checkbox v-model="form.complete" class="mb-6 md:mb-0" :errors="getErrors('complete')" label="Complete" :checked="form.complete" />
                    </div>
                    <textarea-input v-model="form.description" :errors="getErrors('description')" rows="8" class="md:pr-6 pb-8 w-full md:w-1/2" label="Description" />
                    <text-input v-model="form.report_to" :errors="getErrors('report_to')" class="md:pr-6 pb-8 w-full md:w-1/2 md:ml-auto" label="Report To" />
                </div>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
                    <loading-button :loading="sending" class="btn-blue ml-auto" type="submit">Create Task</loading-button>
                </div>
            </form>
        </div>
    </layout>
</template>

<script>
import { filter } from 'lodash';
import moment from 'moment-timezone';
import Layout from '@/Shared/Layout';
import IconBase from '@/Shared/IconBase';
import Checkbox from '@/Shared/Checkbox';
import TextInput from '@/Shared/TextInput';
import Datepicker from '@/Shared/Datepicker';
import SelectInput from '@/Shared/SelectInput';
import Activities from '@/Shared/Icons/Activities';
import LoadingButton from '@/Shared/LoadingButton';
import TextareaInput from '@/Shared/TextareaInput';

export default {
    components: {
        Layout,
        Checkbox,
        IconBase,
        TextInput,
        Activities,
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
    computed: {
        categories () {
            return filter(this.$page.categories.data, t => t.deleted_at === null);
        },
    },
    mounted () {
        this.$nextTick(() => {
            this.form.title = this.workingTask.subject;
            this.form.description = this.workingTask.body;
            this.form.report_to = this.workingTask.from_name;
            this.form.email_id = this.workingTask.id;
            this.form.category = this.workingTask.category ? this.workingTask.category.name : null;
        });
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
