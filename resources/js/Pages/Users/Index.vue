<template>
    <layout title="Users">
        <div class="w-full flex bg-blue-800 p-4">
            <inertia-link class="text-lg md:text-xl text-blue-300 hover:text-white uppercase mr-1" :href="route('dashboard')">Dashboard</inertia-link>
            <span class="text-lg md:text-xl text-blue-300 font-medium mr-1">></span>
            <h1 class="text-white text-lg md:text-xl font-semibold uppercase">Users</h1>
        </div>
        <div class="mb-6 flex justify-between items-center">
            <search-filter v-model="form.search" class="w-full max-w-sm mr-4" @reset="reset">
                <label class="mt-4 block text-gray-900">Trashed:</label>
                <select v-model="form.trashed" class="mt-1 w-full form-select">
                    <option :value="null" />
                    <option value="with">With Trashed</option>
                    <option value="only">Only Trashed</option>
                </select>
            </search-filter>
            <inertia-link class="btn-blue" :href="route('users.create')">
                <span>Create</span>
                <span class="hidden md:inline">User</span>
            </inertia-link>
        </div>
        <item-list :header-fields="headerFields" :data="users" sort-field="name" sort="asc" not-found-message="No users found." entity-name="users" row-action="edit" />
    </layout>
</template>

<script>
import _ from 'lodash';
import Layout from '@/Shared/Layout';
import ItemList from '@/Shared/ItemList';
import SearchFilter from '@/Shared/SearchFilter';

export default {
    components: {
        Layout,
        ItemList,
        SearchFilter,
    },
    props: {
        users: Array,
        filters: Object,
    },
    data () {
        return {
            form: {
                search: this.filters.search,
                trashed: this.filters.trashed,
            },
            headerFields: [
                {
                    name: 'name',
                    label: 'Name',
                },
                {
                    name: 'email',
                    label: 'Email',
                },
            ],
        }
    },
    watch: {
        form: {
            handler: _.throttle(function () {
                let query = _.pickBy(this.form);
                this.$inertia.replace(this.route('users.list', Object.keys(query).length ? query : { remember: 'forget' }))
            }, 150),
            deep: true,
        },
    },
    methods: {
        reset () {
            this.form = _.mapValues(this.form, () => null);
        },
    },
}
</script>
