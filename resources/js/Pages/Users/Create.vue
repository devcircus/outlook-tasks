<template>
    <layout title="Create User">
        <div class="w-full flex bg-blue-800 p-4">
            <inertia-link class="text-lg md:text-xl text-blue-300 hover:text-white uppercase mr-1" :href="route('dashboard')">Dashboard</inertia-link>
            <span class="text-lg md:text-xl text-blue-300 font-medium mr-1">></span>
            <h1 class="text-white text-lg md:text-xl font-semibold uppercase">{{ form.name ? form.name : 'New User' }}</h1>
        </div>
        <div class="bg-white rounded shadow overflow-hidden max-w-lg">
            <form @submit.prevent="submit">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <text-input v-model="form.name" :errors="getErrors('name')" class="pr-6 pb-8 w-full lg:w-1/2" label="Name" />
                    <text-input v-model="form.email" :errors="getErrors('email')" class="pr-6 pb-8 w-full lg:w-1/2" label="Email" />
                    <text-input v-model="form.password" :errors="getErrors('password')" class="pr-6 pb-8 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Password" />
                </div>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-end items-center">
                    <loading-button :loading="sending" class="btn-blue" type="submit">Create User</loading-button>
                </div>
            </form>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import TextInput from '@/Shared/TextInput';
import LoadingButton from '@/Shared/LoadingButton';

export default {
    components: {
        Layout,
        LoadingButton,
        TextInput,
    },
    remember: 'form',
    data () {
        return {
            sending: false,
            submitted: false,
            form: {
                name: null,
                email: null,
                password: null,
            },
        }
    },
    methods: {
        submit () {
            this.sending = true;
            this.submitted = true;
            this.$inertia.post(this.route('users.store'), this.form)
            .then(() => this.sending = false)
        },
    },
}
</script>
