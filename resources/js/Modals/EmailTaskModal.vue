<template>
    <div class="flex flex-col">
        <div class="flex flex-col bg-blue-900 w-full text-center py-4">
            <h1 class="text-white text-3xl uppercase mb-4">Email Task</h1>
        </div>
        <div class="bg-white mx-4 my-4">
            <div class="flex flex-col bg-white overflow-hidden w-full h-auto mb-8 p-8">
                <text-input v-model="to" class="w-full mb-1" label="Send To" />
                <span class="text-xs text-orange-600 mb-4">Separate addresses with a comma.</span>
                <h1 class="text-xl text-gray-700 font-semibold mb-4">{{ task.title }}</h1>
                <span class="text-sm text-gray-800 font-semibold mb-4">{{ task.display_due_date }}</span>
                <p class="text-base text-gray-600 mb-4">{{ task.description }}</p>
                <textarea-input v-model="notes" rows="8" class="w-full mb-4" label="Additional notes:" />
                <div class="bg-gray-100 border-t border-gray-200 flex items-center">
                    <button class="btn-text text-gray-500" type="button" @click="cancel()">Cancel</button>
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

export default {
    components: {
        TextInput,
        LoadingButton,
        TextareaInput,
    },
    props: {
        task: Object,
    },
    data () {
        return {
            sending: false,
            submitted: false,
            to: null,
            notes: null,
        }
    },
    computed: {
        addresses () {
            return this.to.replace(/^\s+|\s+$/g, ' ').split(/[ ,]+/);
        },
    },
    methods: {
        sendEmail () {
            this.sending = true;
            this.submitted = true;
            this.$inertia.post(this.route('tasks.email', this.task.id), {
                to: this.addresses,
                notes: this.notes,
            }).then( () => {
                this.sending = false;
                this.cancel();
            });
        },
        cancel () {
            this.$modal.hide('emailTaskModal');
        },
    },
}
</script>
