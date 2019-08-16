import Vue from 'vue';
import store from '@/store';
import VueHead from 'vue-head';
import { config } from 'Config';
import Dates from 'Mixins/Dates';
import VueStash from 'vue-stash';
import VModal from 'vue-js-modal';
import PortalVue from 'portal-vue';
import ParsesUrls from 'Mixins/ParsesUrls';
import VueWindowSize from 'vue-window-size';
import { InertiaApp } from '@inertiajs/inertia-vue';
import Dispatchable from 'Mixins/Dispatchable';
import Snotify, { SnotifyPosition } from 'vue-snotify';

// Use mixins
Vue.mixin({
    methods: {
         route: (...args) => window.route(...args).url(),
         isObjectEmpty: (obj) => ! Object.values(obj).length >= 1,
         objectContains: (obj, needle) => {
            if (typeof obj === 'object' && obj !== null) {
                return obj.hasOwnProperty(needle);
            }
            return false;
         },
    },
});
Vue.mixin(Dispatchable);
Vue.mixin(ParsesUrls);
Vue.mixin(Dates);

// Use VueHead
Vue.use(VueHead, {
    separator: '|',
    complement: config.appName,
  });

// Use PortalVue
Vue.use(PortalVue);

// Use Vue-Stash for state management
Vue.use(VueStash);

// Use Vue-Modal
Vue.use(VModal, {
    componentName: 'modal-component',
});

// Use Snotify for notifications
Vue.use(Snotify, {
    toast: {
        position: SnotifyPosition.rightTop,
        timeout: 3000,
        showProgressBar: true,
        closeOnClick: false,
        pauseOnHover: true,
    }
});

// Use Inertia
Vue.use(InertiaApp);

// Use vue-window-size
Vue.use(VueWindowSize);

// Filters
Vue.filter('ucase', function (value) {
    return value ? value.toUpperCase() : '';
});

Vue.filter('capitalize', value => {
    if (typeof value !== 'string') return ''
    return value.charAt(0).toUpperCase() + value.slice(1)
});

if (process.env.MIX_APP_ENV === 'production') {
    Vue.config.devtools = false;
    Vue.config.debug = false;
    Vue.config.silent = true;
    Vue.config.productionTip = false;
}

let app = document.getElementById('app');

new Vue({
    data: { store },
    mounted () {
        this.listenForEvents();
    },
    methods: {
        listenForEvents () {
            /* global Echo */
            Echo.channel('outlook')
                .listen('.outlookSynced', e => {
                    this.$snotify.clear();
                    this.$snotify.info(`Email synced for ${e.user.name}. ${e.total} email(s) received.`, 'Notice:');
                });
            Echo.channel('categories')
                .listen('.categoriesSet', e => {
                    this.$dispatch('categoriesSet');
                });
            Echo.channel('tasks')
                .listen('.noTasksGenerated', e => {
                    this.$snotify.clear();
                    this.$snotify.info('No new tasks at this time.', 'Notice:');
                });
            Echo.channel('tasks')
                .listen('.tasksGenerated', e => {
                    this.$snotify.clear();
                    this.$snotify.success('Tasks successfully generated!', 'Success:');
                });
        },
    },
    render: h => h(InertiaApp, {
        props: {
            initialPage: JSON.parse(app.dataset.page),
            resolveComponent: name => import (`@/Pages/${name}`).then(module => module.default),
        },
    }),
}).$mount(app)
