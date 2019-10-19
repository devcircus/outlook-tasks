import { config } from 'Config';

export default {
    meta: {
        admin: {
            name: config.admin.name,
            email: config.admin.email,
            sites: {
                github: {
                    profile: config.admin.sites.github.profile,
                    url: config.admin.sites.github.url,
                },
            },
        },
        links: {
            template: {
                name: config.links.template.name,
                url: config.links.template.url,
            },
            local: {
                dashboard: {
                    name: config.links.local.dashboard.name,
                    url: config.links.local.dashboard.url,
                },
                corporate: {
                    name: config.links.local.corporate.name,
                    url: config.links.local.corporate.url,
                },
            },
        },
    },
    activeCategory: 'all',
    activeCalendar: 'all',
    quantities: {
        tasks: {},
        email: {},
    },
    tables: {
        fields: {
            taskFields: [
                {
                    name: 'display_due_date',
                    label: 'Due',
                    width: '80px',
                },
                {
                    name: 'report_to',
                    label: 'Report To',
                    width: '80px',
                },
                {
                    name: 'short_title',
                    label: 'Title',
                    width: '685px',
                },
            ],
            categoryFields: [
                {
                    name: 'display_name',
                    label: 'Name',
                },
            ],
            emailFields: [
                {
                    name: 'received_at',
                    label: 'Received',
                },
                {
                    name: 'short_subject',
                    label: 'Subject',
                },
                {
                    name: 'from_address',
                    label: 'From',
                },
            ],
        },
    },
}
