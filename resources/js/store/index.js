export default {
    activeCategory: 'all',
    activeCalendar: 'all',
    quantities: {
        tasks: {},
        email: {},
    },
    workingTask: {},
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
