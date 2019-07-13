import { truncate } from '@/Helpers';

export default {
    workingTask: null,
    tables: {
        fields: {
            taskFields: [
                {
                    field: 'display_due_date',
                    label: 'Due',
                },
                {
                    field: 'title',
                    label: 'Title',
                    formatFn: value => truncate(value),
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filter...',
                    },
                },
            ],
            emailFields: [
                {
                    field: 'received_at',
                    label: 'Received',
                },
                {
                    field: 'subject',
                    label: 'Subject',
                    formatFn: value => truncate(value),
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filter...',
                    },
                },
                {
                    field: 'from_address',
                    label: 'From',
                },
                {
                    field: 'actions',
                    label: 'Actions',
                },
            ],
        },
    },
}
