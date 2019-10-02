<template>
    <layout :title="`Admin`">
        <div class="w-full flex bg-blue-800 p-4 mb-8">
            <inertia-link class="text-lg md:text-xl text-blue-300 hover:text-white uppercase mr-1" :href="route('dashboard')">Dashboard</inertia-link>
            <span class="text-lg md:text-xl text-blue-300 font-medium mr-1">></span>
            <h1 class="text-white text-lg md:text-xl font-semibold uppercase">Manage Categories</h1>
        </div>
        <div class="flex flex-col lg:flex-row bg-white w-full">
            <!-- New Category Form -->
            <div class="rounded shadow overflow-hidden w-full lg:w-1/2 mb-8">
                <form @submit.prevent="createCategory()">
                    <div class="p-8 -mr-6 -mb-8 flex flex-col lg:flex-row lg:flex-wrap w-full">
                        <text-input v-model="forms.category.name" :errors="getErrors('name')" class="lg:pr-6 pb-8 w-full lg:w-1/2" label="Name" />
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
