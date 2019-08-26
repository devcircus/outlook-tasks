import Vue from 'vue';
import store from '@/store';
import VueHead from 'vue-head';
import { config } from 'Config';
import Dates from 'Mixins/Dates';
import VueStash from 'vue-stash';
import VModal from 'vue-js-modal';
import PortalVue from 'portal-vue';
import Objects from '@/plugins/Objects';
import Dialogs from '@/plugins/Dialogs';
import GetsErrors from 'Mixins/GetsErrors';
import ParsesUrls from 'Mixins/ParsesUrls';
import VueWindowSize from 'vue-window-size';
import Dispatcher from '@/plugins/Dispatcher';
import { InertiaApp } from '@inertiajs/inertia-vue';
import Snotify, { SnotifyPosition } from 'vue-snotify';
import HandlesDropdowns from 'Mixins/HandlesDropdowns';
import ScreenChanges from 'Mixins/HandlesScreenSizeChanges';

// Use mixins
Vue.mixin(Dates);
Vue.mixin(ParsesUrls);
Vue.mixin(GetsErrors);
Vue.mixin(ScreenChanges);
Vue.mixin(HandlesDropdowns);

Vue.mixin({
    methods: {
        route (...args) {
            return window.route(...args).url();
        },
        setAllProperties (obj, value) {
            return Object.keys(obj).forEach(prop => obj[prop] = value);
        },
        setAllToNull (obj) {
            return this.setAllProperties(obj, null);
        },
    },
});

// Use Dispatcher
Vue.use(Dispatcher);

// Use Dialogs
Vue.use(Dialogs);

// Use Objects
Vue.use(Objects);

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
        timeout: 1500,
        showProgressBar: true,
        closeOnClick: false,
        pauseOnHover: true,
        backdrop: 0.7,
    }
});

// Use InertiaApp
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
    Vue.config.silent = false;
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
