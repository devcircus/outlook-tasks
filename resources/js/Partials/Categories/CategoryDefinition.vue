<template>
    <div class="bg-white rounded shadow overflow-hidden w-full md:w-2/5 mb-8 p-8">
        <div class="flex content-center mb-4">
            <h1 class="text-lg text-gray-800 font-semibold uppercase">Category Definition</h1>
            <dropdown v-if="showAddDefinitions" class="mr-1 ml-auto" placement="bottom-end">
                <div class="flex items-center cursor-pointer select-none group">
                    <div class="text-blue-900 group-hover:text-blue-700 focus:text-blue-700 mr-1 whitespace-no-wrap">
                        <span class="inline text-sm">Options</span>
                    </div>
                    <icon class="w-5 h-5 group-hover:fill-blue-700 fill-blue-900 focus:fill-blue-700" name="cheveron-down" />
                </div>
                <div slot="dropdown" class="mt-2 p-2 shadow-lg bg-white rounded">
                    <p v-if="! category.has_from_definition" class="text-blue-500 font-semibold text-xs uppercase py-2 cursor-pointer" @click="addRule(category, 'from')">Add "From" Rule</p>
                    <p v-if="! category.has_subject_definition" class="text-blue-500 font-semibold text-xs uppercase py-2 cursor-pointer" @click="addRule(category, 'subject')">Add "Subject" Rule</p>
                    <p v-if="! category.has_body_definition" class="text-blue-500 font-semibold text-xs uppercase py-2 cursor-pointer" @click="addRule(category, 'body')">Add "Body" Rule</p>
                </div>
            </dropdown>
        </div>
        <div>
            <span v-if="! category.definitions.length" class="text-lg text-gray-700">No definition for this category.</span>
            <div v-for="definition in category.definitions" :key="definition.id" class="bg-gray-200 flex flex-col rounded p-4 mb-4">
                <div class="flex content-center mb-4">
                    <span class="text:xl text-blue-800 font-semibold uppercase">{{ definition.definition_type|capitalize }}</span>
                    <dropdown class="mr-1 ml-auto" placement="bottom-end">
                        <div class="flex items-center cursor-pointer select-none group">
                            <icon class="w-5 h-5 group-hover:fill-blue-700 fill-blue-900 focus:fill-blue-700" name="hamburger" />
                        </div>
                        <div slot="dropdown" class="mt-2 p-2 shadow-lg bg-white rounded">
                            <p><button class="btn-text text-blue-500" @click="editRule(definition)">Edit</button></p>
                            <p><button class="btn-text text-red-500" @click="deleteRule(definition.id)">Delete</button></p>
                        </div>
                    </dropdown>
                </div>
                <div class="mb-4">
                    <span class="text:lg text-gray-800 font-semibold">Rule type: </span>
                    <span class="text-lg text-gray-700">{{ displayRuleType(definition.rule_type) }}</span>
                </div>
                <div class="mb-4">
                    <span class="block text:lg text-gray-800 font-semibold mb-2">Definition: </span>
                    <span class="text-lg text-gray-700 leading-normal" :class="definition.rule_type === 'words' ? 'whitespace-pre-wrap' : ''" v-text="displayDefinition(definition.definition)" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon';
import Dropdown from '@/Shared/Dropdown';

export default {
    components: {
        Icon,
        Dropdown,
    },
    props: {
        category: {
            type: Object,
            default: () => ({}),
        },
    },
    data () {
        return {
            deleteSending: false,
        }
    },
    computed: {
        showAddDefinitions () {
            return ! (this.category.has_from_definition && this.category.has_subject_definition && this.category.has_body_definition);
        },
    },
    methods: {
        addRule (category, type) {
            this.$modal.show('addRuleModal', {
                category: category,
                type: type,
            });
            this.hideDropdown();
        },
        editRule (rule) {
            this.$modal.show('editRuleModal', {
                rule: rule,
            });
            this.hideDropdown();
        },
        deleteRule (id) {
            this.hideDropdown();
            this.$inertia.delete(this.route('definitions.delete', id));
        },
        hideDropdown () {
            this.$dispatch('dropdown-should-close');
        },
        displayRuleType (ruleType) {
            return ruleType;
        },
        displayDefinition (definition) {
            return definition;
        },
    },
}
</script>
