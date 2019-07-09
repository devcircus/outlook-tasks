export default {
    workingTask: null,
    tables: {
        fields: {
            taskFields: [
                {
                    field: 'due_date',
                    label: 'Due',
                },
                {
                    field: 'title',
                    label: 'Title',
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
