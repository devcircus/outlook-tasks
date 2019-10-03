<template>
    <div>
        <modal />
        <portal-target name="dropdown" slim />
        <flash-message />
        <div class="flex flex-col">
            <div class="flex flex-col">
                <div class="flex flex-wrap">
                    <div class="bg-blue-800 flex-no-shrink w-full px-4 py-8 md:p-8 flex justify-between items-center">
                        <inertia-link class="mt-1" :href="route('dashboard')">
                            <logo position="left" />
                        </inertia-link>
                        <dropdown class="block md:hidden" placement="bottom-end">
                            <svg class="fill-white w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
                            <div slot="dropdown" class="mt-2 px-8 py-4 shadow-lg bg-blue-800 rounded">
                                <sidebar class="block md:hidden" />
                            </div>
                        </dropdown>
                    </div>
                    <div class="flex w-full h-16 bg-white border-b shadow p-4 px-4 py-8 md:px-12 text-sm md:text-base justify-between items-center relative">
                        <div v-if="! $page.token" class="flex w-full">
                            <a :href="route('outlook.signin')" class="btn btn-blue w-200p text-center">Connect to Outlook</a>
                        </div>
                        <div v-if="$page.token && categoriesReady" class="flex w-full">
                            <loading-button class="btn-blue mr-4" :loading="syncLoading" type="button" @clicked="syncEmail()">Sync</loading-button>
                            <loading-button class="btn-blue" :class="tasksDisabled ? 'cursor-not-allowed btn-disabled' : 'cursor-pointer'" :loading="tasksLoading" type="button" @clicked="processTasks()">Get Tasks</loading-button>
                        </div>
                        <div v-if="$page.token && ! categoriesReady" class="flex w-full">
                            <h2 class="text-lg font-semibold text-blue-800 uppercase">Categories must be defined before you can sync email.</h2>
                        </div>
                        <div class="mt-1 mr-4">&nbsp;</div>
                        <dropdown v-if="$page.auth.user" class="mt-1 md:ml-auto " placement="bottom-end">
                            <div class="flex items-center cursor-pointer select-none group">
                                <div class="text-blue-900 group-hover:text-blue-700 focus:text-blue-700 mr-1 whitespace-no-wrap">
                                    <span class="inline">{{ $page.auth.user.name }}</span>
                                </div>
                                <icon-base icon-function="cheveron-down" icon-fill="fill-blue-500" classes="ml-2">
                                    <cheveron-down />
                                </icon-base>
                            </div>
                            <div slot="dropdown" class="mt-2 py-2 shadow-lg bg-white rounded text-sm">
                                <div class="flex flex-col">
                                    <inertia-link class="flex px-6 py-2 hover:bg-blue-500 hover:text-gray-500 group" :href="route('users.edit', $page.auth.user.id)">
                                        <icon-base width="14" height="14" icon-fill="fill-gray-800" icon-name="profile" classes="mr-2 group-hover:fill-gray-500">
                                            <profile />
                                        </icon-base>
                                        My Profile
                                    </inertia-link>
                                    <div v-if="$page.auth.user.is_admin">
                                        <inertia-link class="flex px-6 py-2 hover:bg-blue-500 hover:text-gray-500 group" :href="route('users.list')">
                                            <icon-base width="14" height="14" icon-fill="fill-gray-800" icon-name="users" classes="mr-2 group-hover:fill-gray-500">
                                                <users />
                                            </icon-base>
                                            Manage Users
                                        </inertia-link>
                                        <inertia-link class="flex px-6 py-2 hover:bg-blue-500 hover:text-gray-500 group" :href="route('admin.index')">
                                            <icon-base width="14" height="14" icon-fill="fill-gray-800" icon-name="manage categories" classes="mr-2 group-hover:fill-gray-500">
                                                <activities />
                                            </icon-base>
                                            Manage Categories
                                        </inertia-link>
                                    </div>
                                    <inertia-link class="flex px-6 py-2 hover:bg-blue-500 hover:text-gray-500 group" :href="route('logout')" method="post">
                                        <icon-base width="14" height="14" icon-fill="fill-gray-800" icon-name="logout" classes="mr-2 group-hover:fill-gray-500">
                                            <logout />
                                        </icon-base>
                                        Logout
                                    </inertia-link>
                                </div>
                            </div>
                        </dropdown>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row w-full relative">
                    <div class="hidden md:block w-full md:w-1/3 lg:w-1/4 xl:w-1/6 bg-white md:px-4 md:pt-4">
                        <sidebar class="sticky top-32" />
                    </div>
                    <div class="overflow-hidden bg-white md:bg-direction-blue w-full md:w-2/3 lg:w-3/4 xl:w-5/6 md:pt-4 md:px-6">
                        <slot />
                    </div>
                </div>
            </div>
        </div>
        <site-footer />
    </div>
</template>

<script>
import Logo from '@/Shared/Logo';
import Modal from '@/Shared/Modal';
import Sidebar from '@/Shared/Sidebar';
import Dropdown from '@/Shared/Dropdown';
import IconBase from '@/Shared/IconBase';
import Users from '@/Shared/Icons/Users';
import Logout from '@/Shared/Icons/Logout';
import SiteFooter from '@/Shared/SiteFooter';
import Profile from '@/Shared/Icons/Profile';
import FlashMessage from '@/Shared/FlashMessage';
import Activities from '@/Shared/Icons/Activities';
import LoadingButton from '@/Shared/LoadingButton';
import CheveronDown from '@/Shared/Icons/CheveronDown';

export default {
    components: {
        Logo,
        Modal,
        Users,
        Logout,
        Sidebar,
        Profile,
        Dropdown,
        IconBase,
        Activities,
        SiteFooter,
        FlashMessage,
        CheveronDown,
        LoadingButton,
    },
    props: {
        title: String,
    },
    head: {
        title: function () {
            return {
                inner: this.title,
            }
        },
    },
    data () {
        return {
            syncLoading: false,
            tasksLoading: false,
            emailsLoading: false,
        }
    },
    computed: {
        tasksDisabled () {
            return this.emailsLoading;
        },
        categoriesReady () {
            return this.$page.categories.ready;
        },
    },
    mounted () {
        this.$listen('categoriesSet', () => {
            this.emailsLoading = false;
        });
    },
    methods: {
        syncEmail () {
            this.syncLoading = true;
            this.emailsLoading = true;
            this.$inertia.post(this.route('outlook.sync'), null, {
                preserveState: false,
            }).then( () => this.syncLoading = false );
        },
        processTasks () {
            this.tasksLoading = true;
            this.$inertia.post(this.route('tasks.process'), null, { preserveState: false }).then( () => this.tasksLoading = false );
        },
    },
}
</script>
