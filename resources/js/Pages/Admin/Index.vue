<template>
    <layout :title="`Admin`">
        <h1 class="mb-8 font-bold text-lg md:text-3xltext-gray-700">Manage Tasks</h1>
        <h2 class="mb-8 font-bold text-base md:text-xltext-gray-800">Categories</h2>
        <div class="flex flex-col md:flex-row md:-mx-1">
            <!-- New Category Form -->
            <div class="bg-white rounded shadow overflow-hidden w-full md:w-1/2 mb-8 md:mr-2">
                <form @submit.prevent="createCategory()">
                    <div class="p-8 -mr-6 -mb-8 flex flex-col md:flex-row md:flex-wrap w-full">
                        <text-input v-model="forms.category.name" :errors="getErrors('name')" class="md:pr-6 pb-8 w-full md:w-1/2" label="Name" />
                    </div>
                    <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
                        <loading-button :loading="categorySending" class="btn-blue ml-auto" type="submit">Create Category</loading-button>
                    </div>
                </form>
            </div>

            <!-- Categories Table -->
            <category-table />
        </div>
    </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import TextInput from '@/Shared/TextInput';
import LoadingButton from '@/Shared/LoadingButton';
import CategoryTable from '@/Partials/Categories/CategoryTable';

export default {
    components: {
        Layout,
        TextInput,
        LoadingButton,
        CategoryTable,
    },
    remember: 'form',
    data () {
        return {
            categorySending: false,
            forms: {
                category: {
                    name: null,
                },
            },
        }
    },
    methods: {
        createCategory () {
            this.categorySending = true;
            this.$inertia.post(this.route('categories.store'), this.forms.category)
            .then(() => {
                this.forms.category.name = null;
                this.categorySending = false;
             });
        },
    },
}
</script>
