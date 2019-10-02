<template>
    <layout :title="`Profile for ${form.name}`">
        <div class="w-full flex bg-blue-800 p-4">
            <inertia-link class="text-lg md:text-xl text-blue-300 hover:text-white uppercase mr-1" :href="route('dashboard')">Dashboard</inertia-link>
            <span class="text-lg md:text-xl text-blue-300 font-medium mr-1">></span>
            <h1 class="text-white text-lg md:text-xl font-semibold uppercase">{{ form.name }}</h1>
        </div>
        <trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore">
            This user has been deleted.
        </trashed-message>
        <div class="bg-white rounded shadow overflow-hidden max-w-lg mb-8">
            <form @submit.prevent="submit">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <text-input v-model="form.name" :errors="getErrors('name')" class="pr-6 pb-8 w-full lg:w-1/2" label="Name" />
                    <text-input v-model="form.email" :errors="getErrors('email')" class="pr-6 pb-8 w-full lg:w-1/2" label="Email" />
                    <text-input v-model="form.password" :errors="getErrors('password')" class="pr-6 pb-8 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Password" />
                </div>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
                    <button v-if="! user.deleted_at" class="text-red-500 hover:underline" tabindex="-1" type="button" @click="destroy">Delete User</button>
                    <loading-button :loading="sending" class="btn-blue ml-auto" type="submit">Update User</loading-button>
                </div>
            </form>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import TextInput from '@/Shared/TextInput';
import LoadingButton from '@/Shared/LoadingButton';
import TrashedMessage from '@/Shared/TrashedMessage';

export default {
    components: {
        Layout,
        LoadingButton,
        TextInput,
        TrashedMessage,
    },
    props: {
        user: Object,
    },
    remember: 'form',
    data () {
        return {
            sending: false,
            form: {
                id: this.user.id,
                name: this.user.name,
                email: this.user.email,
                password: this.user.password,
            },
        }
    },
    methods: {
        submit () {
            this.sending = true;
            this.$inertia.put(this.route('users.update', this.user.id), this.form)
            .then(() => {
                this.sending = false;
             });
        },
        destroy () {
            this.$modal.show('deleteUserDialog', {
                title: 'Caution!',
                text: 'Are you sure you want to delete this user?',
                buttons: [
                    {
                        title: 'Delete User',
                        type: 'delete',
                        handler: () => {
                            this.$inertia.delete(this.route('users.destroy', this.user.id), { replace: false, preserveScroll: true, preserveState: true });
                            this.$modal.hide('deleteUserDialog');
                         },
                    },
                    {
                        title: 'Close',
                        type: 'close',
                        handler: () => { this.$modal.hide('deleteUserDialog') },
                    },
                ],
            });
        },
        restore () {
            this.$modal.show('restoreUserDialog', {
                title: 'Notice!',
                text: 'Are you sure you want to restore this user?',
                buttons: [
                    {
                        title: 'Restore User',
                        type: 'restore',
                        handler: () => {
                            this.$inertia.put(this.route('users.restore', this.user.id), null, { replace: false, preserveScroll: true, preserveState: true });
                            this.$modal.hide('restoreUserDialog');
                         },
                    },
                    {
                        title: 'Close',
                        type: 'close',
                        handler: () => { this.$modal.hide('restoreUserDialog') },
                    },
                ],
            });
        },
    },
}
</script>
