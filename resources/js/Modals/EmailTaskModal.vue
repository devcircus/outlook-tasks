<template>
    <div class="flex flex-col">
        <div class="flex flex-col bg-blue-900 w-full text-center py-4">
            <h1 class="text-white text-3xl uppercase mb-4">Email Task</h1>
        </div>
        <div class="bg-white mx-4 my-4">
            <div class="flex flex-col bg-white overflow-hidden w-full h-auto mb-8 p-8">
                <text-input v-model="form.to" class="w-full mb-1" label="Send To" />
                <span class="text-xs text-orange-600 mb-4">Separate addresses with a comma.</span>
                <text-input v-model="form.subject" class="w-full mb-4" label="Subject" />
                <span class="text-sm text-gray-800 font-semibold mb-4">Due: <span class="text-gray-600">{{ form.due_date }}</span></span>
                <textarea-input v-model="form.body" rows="8" class="w-full mb-4" label="Email body" />
                <div class="bg-gray-100 border-t border-gray-200 flex items-center">
                    <button class="btn-text text-gray-500" type="button" @click="resetForm()">Cancel</button>
                    <loading-button :loading="sending" class="btn-blue ml-auto" type="button" @clicked="sendEmail">Send Email</loading-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import LoadingButton from '@/Shared/LoadingButton';
import TextareaInput from '@/Shared/TextareaInput';
import WatchesForErrors from 'Mixins/WatchesForErrors';

export default {
    components: {
        TextInput,
        LoadingButton,
        TextareaInput,
    },
    mixins: [ WatchesForErrors ],
    props: {
        task: Object,
    },
    data () {
        return {
            sending: false,
            submitted: false,
            form: {
                to: null,
                due_date: this.task.display_due_date,
                subject: `Todo: ${this.task.title}`,
                body: this.task.description,
            },
        }
    },
    computed: {
        addresses () {
            return this.form.to.replace(/^\s+|\s+$/g, ' ').split(/[ ,]+/);
        },
    },
    methods: {
        sendEmail () {
            this.sending = true;
            this.submitted = true;
            this.$inertia.post(this.route('tasks.email'), {
                to: this.addresses,
                due_date: this.form.due_date,
                subject: this.form.subject,
                body: this.form.body,
            }).then( () => {
                this.sending = false;
                this.resetForm();
            });
        },
        resetForm () {
            this.setAllToNull(this.form);
            this.$modal.hide('emailTaskModal');
        },
    },
}
</script>
