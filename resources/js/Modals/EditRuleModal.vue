<template>
    <div class="flex flex-col">
        <div class="flex flex-col bg-blue-900 w-full text-center py-4">
            <h1 class="text-white text-3xl uppercase mb-4">{{ shortDefinitionType }} Rule</h1>
            <span class="text-white text-sm italic">{{ explanation }}</span>
        </div>
        <div class="bg-white mx-4 my-4">
            <div class="bg-white overflow-hidden w-full h-auto mb-8 md:mr-2 p-8">
                <form @submit.prevent="submit()">
                    <select-input v-model="form.rule_type" class="md:pr-6 pb-8 w-full" :errors="$page.errors.rule_type" label="Rule type">
                        <option :value="null" />
                        <option value="exact">Exact Match</option>
                        <option value="words">These words or phrases.</option>
                        <option value="regex">Regex Match (Advanced)</option>
                    </select-input>
                    <p v-if="form.rule_type === 'words'" class="text-sm text-blue-500 mb-4 w-full mx-auto">Separate words/phrases with a new line.</p>
                    <textarea-input v-if="form.rule_type === 'words'" v-model="form.definition" :errors="$page.errors.definition" rows="8" class="md:pr-6 pb-8 w-full" :label="label" />
                    <text-input v-if="form.rule_type != 'words' && form.rule_type != null" v-model="form.definition" :errors="$page.errors.definition" rows="8" class="md:pr-6 pb-8 w-full" :label="label" />
                    <div class="bg-gray-100 border-t border-gray-200 flex items-center">
                        <loading-button :loading="sending" class="btn-blue ml-auto" type="submit">Update Rule</loading-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import SelectInput from '@/Shared/SelectInput';
import TextareaInput from '@/Shared/TextareaInput';
import LoadingButton from '@/Shared/LoadingButton';

export default {
    components: {
        TextInput,
        SelectInput,
        TextareaInput,
        LoadingButton,
    },
    props: {
        rule: Object,

    },
    data () {
        return {
            sending: false,
            form: {
                definition_type: this.rule.definition_type,
                rule_type: this.rule.rule_type,
                definition: this.rule.definition,
            },
        }
    },
    computed: {
        explanation () {
            if (this.form.definition_type === 'fromDefinition') {
                return 'Define a rule for the "from" email adress.';
            }
            if (this.form.definition_type === 'subjectDefinition') {
                return 'Define a rule for the email subject.';
            }

            return 'Define a rule for the body of the email.';
        },
        label () {
            switch(this.form.rule_type){
                case 'exact': return 'The exact word(s) or phrase to search for';
                case 'words': return 'Any word(s) or phrases to search for';
                case 'regex': return 'A valid regular expression search';
                default: return 'Label';
            }
        },
        shortDefinitionType () {
            return this.rule.definition_type.slice(0, this.rule.definition_type.indexOf('Definition'));
        },
    },
    methods: {
        submit () {
            this.$inertia.put(this.route('definitions.update', this.rule.id), this.form).then( () => {
                this.sending = false;
                this.$modal.hide('editRuleModal');
            });
        },
    },
}
</script>
