<template>
    <div class="flex flex-col w-full">
        <div class="flex bg-blue-800 p-4">
            <inertia-link :href="route('admin.index')" class="group flex items-center">
                <h1 class="text-white group-hover:text-blue-200 text-lg md:text-xl font-semibold uppercase">Categories</h1>
                <icon-base icon-function="activities" icon-fill="fill-white" classes="ml-2 group-hover:fill-blue-200">
                    <activities />
                </icon-base>
            </inertia-link>
        </div>
        <div v-if="$page.categories.ready" class="mb-6">
            <div class="px-6">
                <div class="flex mb-3 mt-4">
                    <span class="inline-block text-sm md:text-base font-semibold py-1 border-b-2 border-transparent cursor-pointer" :class="activeCategory === 'all' ? 'text-blue-200 border-white md:text-blue-800 md:border-blue-800' : 'text-white md:text-blue-500'" @click="setCategory('all')">
                        All
                    </span>
                    <span class="inline-block bg-blue-500 text-white font-semibold text-xs rounded-full ml-auto mt-1 px-2 py-1 leading-tight">{{ $page.taskQuantities['all']['all'] }}</span>
                </div>
                <div v-for="category in categories" :key="category.id" class="flex flex-col">
                    <div class="flex items-center mb-3">
                        <span class="inline-block text-sm md:text-base text-white font-semibold py-1 border-b-2 border-transparent cursor-pointer" :class="activeCategory === category.name ? 'text-blue-200 border-white md:text-blue-800 md:border-blue-800' : 'text-white md:text-blue-500'" @click="setCategory(category.name)">
                            {{ category.name | capitalize }}
                        </span>
                        <span class="inline-block bg-blue-500 text-white font-semibold text-xs rounded-full ml-auto mt-1 px-2 py-1 leading-tight">{{ $page.taskQuantities['all'][category.name] }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col mb-6">
            <div class="flex">
                <div class="w-full flex items-center bg-blue-800 p-4">
                    <h1 class="text-white text-lg md:text-xl font-semibold uppercase">Calendar</h1>
                    <icon-base icon-function="calendar options" icon-fill="fill-white" classes="ml-2">
                        <calendar />
                    </icon-base>
                </div>
            </div>
            <div class="flex flex-col pt-4 px-6">
                <div class="flex items-center mb-3">
                    <span class="block text-sm md:text-base text-white font-semibold border-b-2 border-transparent cursor-pointer mb-1 py-1" :class="activeCalendar === 'all' ? 'text-blue-200 border-white md:text-blue-800 md:border-blue-800' : 'text-white md:text-blue-500'" @click="setCalendar('all')">All Dates</span>
                </div>
                <div class="flex items-center mb-3">
                    <span class="inline-block text-sm md:text-base text-white font-semibold border-b-2 border-transparent cursor-pointer" :class="activeCalendar === 'overdue' ? 'text-blue-200 border-white md:text-blue-800 md:border-blue-800' : 'text-white md:text-blue-500'" @click="setCalendar('overdue')">Overdue</span>
                    <span class="inline-block bg-blue-500 text-white font-semibold text-xs rounded-full ml-auto mt-1 px-2 py-1 leading-tight">{{ $page.taskQuantities['overdue'][activeCategory] }}</span>
                </div>
                <div class="flex items-center mb-3">
                    <span class="inline-block text-sm md:text-base text-white font-semibold border-b-2 border-transparent cursor-pointer" :class="activeCalendar === 'today' ? 'text-blue-200 border-white md:text-blue-800 md:border-blue-800' : 'text-white md:text-blue-500'" @click="setCalendar('today')">Today</span>
                    <span class="inline-block bg-blue-500 text-white font-semibold text-xs rounded-full ml-auto mt-1 px-2 py-1 leading-tight">{{ $page.taskQuantities['today'][activeCategory] }}</span>
                </div>
                <div class="flex items-center mb-3">
                    <span class="inline-block text-sm md:text-base text-white font-semibold border-b-2 border-transparent cursor-pointer" :class="activeCalendar === 'week' ? 'text-blue-200 border-white md:text-blue-800 md:border-blue-800' : 'text-white md:text-blue-500'" @click="setCalendar('week')">This Week</span>
                    <span class="inline-block bg-blue-500 text-white font-semibold text-xs rounded-full ml-auto mt-1 px-2 py-1 leading-tight">{{ $page.taskQuantities['week'][activeCategory] }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { filter } from 'lodash';
import IconBase from '@/Shared/IconBase';
import Calendar from '@/Shared/Icons/Calendar';
import Activities from '@/Shared/Icons/Activities';

export default {
    components: {
        Calendar,
        IconBase,
        Activities,
    },
    store: ['activeCategory', 'activeCalendar'],
    data () {
        return {
            sidebarHidden: false,
        }
    },
    computed: {
        categories () {
             return filter(this.$page.categories.data, t => t.deleted_at === null);
        },
    },
    methods: {
        setCategory (category) {
            let categories = this.$collection(this.$page.categories.data);
            if (category === 'all' || categories.has(category)) {
                return this.$inertia.visit(this.route('dashboard', {category: category, calendar: this.activeCalendar}));
            }

            return this.$inertia.visit(this.route('dashboard', {category: 'all', calendar: this.activeCalendar}));
        },
        setCalendar (calendar) {
            let options = this.$collection(['all', 'today', 'overdue', 'week']);

            if (options.contains(calendar)) {
                return this.$inertia.visit(this.route('dashboard', {category: this.activeCategory, calendar: calendar}));
            }

            return this.$inertia.visit(this.route('dashboard', {category: this.activeCategory, calendar: 'all'}));
        },
        toggleSidebar () {
            this.sidebarHidden = ! this.sidebarHidden;
        },
    },
}
</script>
