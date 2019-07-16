<template>
    <layout :title="`Define Category`">
        <h1 class="mb-8 font-bold text-lg md:text-3xl">
            <inertia-link class="text-blue-300 hover:text-blue-700" :href="route('dashboard')">Dashboard</inertia-link>
            <span class="text-blue-300 font-medium">/</span>
            <inertia-link class="text-blue-300 hover:text-blue-700" :href="route('admin.index')">Categories</inertia-link>
            <span class="text-blue-300 font-medium">/</span>
            <span class="text-blue-800 font-medium">{{ forms.category.name }}</span>
        </h1>
        <trashed-message v-if="category.deleted_at" class="mb-6" @restore="restoreCategory">
            This category has been deleted.
        </trashed-message>
        <div class="bg-white rounded shadow overflow-hidden w-full md:w-3/5 mb-8">
            <form @submit.prevent="updateCategory()">
                <div class="p-8 -mr-6 -mb-8 flex flex-col md:flex-row md:flex-wrap w-full">
                    <text-input v-model="forms.category.name" :errors="$page.errors.name" class="md:pr-6 pb-8 w-full md:w-1/2" label="Name" />
                </div>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
                    <button v-if="! category.deleted_at" class="text-red-500 hover:underline" tabindex="-1" type="button" @click="destroyCategory">Delete Category</button>
                    <loading-button :loading="sendingCategory" class="btn-blue ml-auto" type="submit">Update Category</loading-button>
                </div>
            </form>
        </div>
        <div class="flex flex-col">
            <span class="text-blue-800 font-medium text-lg md:text-2xl uppercase mb-4">Definition</span>
            <div class="bg-white rounded shadow overflow-hidden w-full md:w-3/5 mb-8">
                <form @submit.prevent="submitDefinition">
                    <div class="p-8 -mr-6 -mb-8 flex flex-col md:flex-row md:flex-wrap w-full">
                        <select-input v-model="forms.definition.type" class="md:pr-6 pb-8 w-full md:w-1/2" :errors="$page.errors.type" label="Definition Type">
                            <option v-for="type in $page.definitionTypes" :key="type.id" :value="type.name">{{ type.display_name }}</option>
                        </select-input>
                        <textarea-input v-model="forms.definition.words" :errors="$page.errors.words" rows="8" class="md:pr-6 pb-8 w-full md:w-1/2" label="Included Words" />
                        <textarea-input v-model="forms.definition.exact" :errors="$page.errors.exact" rows="8" class="md:pr-6 pb-8 w-full md:w-1/2" label="Exact Match" />
                        <textarea-input v-model="forms.definition.regex" :errors="$page.errors.regex" rows="8" class="md:pr-6 pb-8 w-full md:w-1/2" label="Regular Expression" />
                    </div>
                    <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
                        <loading-button :loading="sendingDefinition" class="btn-blue ml-auto" type="submit">Create Definition</loading-button>
                    </div>
                </form>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import TextInput from '@/Shared/TextInput';
import SelectInput from '@/Shared/SelectInput';
import LoadingButton from '@/Shared/LoadingButton';
import TextareaInput from '@/Shared/TextareaInput';
import TrashedMessage from '@/Shared/TrashedMessage';

export default {
    components: {
        Layout,
        TextInput,
        SelectInput,
        TextareaInput,
        LoadingButton,
        TrashedMessage,
    },
    props: {
        category: Object,
    },
    remember: 'forms.category',
    data () {
        return {
            sendingCategory: false,
            sendingDefinition: false,
            forms: {
                category: {
                    id: this.category.id,
                    name: this.category.name,
                },
                definition: {
                    words: null,
                    regex: null,
                    exact: null,
                    type: null,
                },
            },
        }
    },
    methods: {
        updateCategory () {
            this.$inertia.put(this.route('categories.update', this.category.id), this.forms.category)
            .then(() => {
                this.sendingCategory = false;
             });
        },
        submitDefinition () {
            this.$inertia.post(this.route('definitions.store', this.category.id), this.forms.definition)
            .then(() => {
                this.sendingDefinition = false;
             });
        },
        destroyCategory () {
            this.$modal.show('deleteCategoryDialog', {
                title: 'Caution!',
                text: 'Are you sure you want to delete this category? All tasks and emails with this category will also be deleted.',
                buttons: [
                    {
                        title: 'Delete Category',
                        type: 'delete',
                        handler: () => {
                            this.$inertia.delete(this.route('categories.destroy', this.category.id), { replace: false, preserveScroll: true, preserveState: true });
                            this.$modal.hide('deleteCategoryDialog');
                         },
                    },
                    {
                        title: 'Close',
                        type: 'close',
                        handler: () => { this.$modal.hide('deleteCategoryDialog') },
                    },
                ],
            });
        },
        restoreCategory () {
            this.$modal.show('restoreCategoryDialog', {
                title: 'Notice!',
                text: 'Are you sure you want to restore this category?',
                buttons: [
                    {
                        title: 'Restore Category',
                        type: 'restore',
                        handler: () => {
                            this.$inertia.put(this.route('categories.restore', this.category.id));
                            this.$modal.hide('restoreCategoryDialog');
                         },
                    },
                    {
                        title: 'Close',
                        type: 'close',
                        handler: () => { this.$modal.hide('restoreCategoryDialog') },
                    },
                ],
            });
        },
    },
}
</script>
