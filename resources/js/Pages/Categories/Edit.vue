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
        <div class="flex flex-col md:flex-row -px-2">
            <div class="bg-white rounded shadow overflow-hidden w-full md:w-3/5 mb-8 md:mr-2 p-8">
                <form @submit.prevent="updateCategory()">
                    <div class="-mr-6 -mb-8 flex flex-col md:flex-row md:flex-wrap w-full">
                        <text-input v-model="forms.category.name" :errors="$page.errors.name" class="md:pr-6 pb-8 w-full md:w-1/2" label="Name" />
                    </div>
                    <div class="bg-gray-100 border-t border-gray-200 flex items-center">
                        <button v-if="! category.deleted_at" class="text-red-500 hover:underline" tabindex="-1" type="button" @click="destroyCategory">Delete Category</button>
                        <loading-button :loading="sendingCategory" class="btn-blue ml-auto" type="submit">Update Category</loading-button>
                    </div>
                </form>
            </div>
            <category-definition :category="category" />
        </div>
    </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import TextInput from '@/Shared/TextInput';
import LoadingButton from '@/Shared/LoadingButton';
import TrashedMessage from '@/Shared/TrashedMessage';
import CategoryDefinition from '@/Partials/Categories/CategoryDefinition';

export default {
    components: {
        Layout,
        TextInput,
        LoadingButton,
        TrashedMessage,
        CategoryDefinition,
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
                definitions: {
                    type: null,
                    definition: null,
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
