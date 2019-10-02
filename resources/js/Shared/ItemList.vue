<template>
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <tr class="text-left font-bold">
                <th v-for="field in headerFields" :key="field.name" class="px-6 pt-6 pb-4">{{ field.label }}</th>
                <th v-if="hasActions" class="px-6 pt-6 pb-4">
                    Actions
                </th>
                <th>&nbsp;</th>
            </tr>
            <tr v-for="item in data" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td v-for="field in headerFields" :key="field.name" class="border-t">
                    <span class="px-6 py-4 flex items-center focus:text-blue-500 cursor-pointer" :class="item.deleted_at ? 'text-red-500' : ''" @click="performAction(item)">
                        {{ item[field.name] }}
                    </span>
                </td>
                <td v-if="hasActions" class="border-t">
                    <slot :item="item" />
                </td>
                <td class="border-t">
                    <span class="px-6 py-4 flex items-center cursor-pointer" @click="performAction(item)">
                        <icon-base icon-function="cheveron-right" icon-fill="fill-blue-500" classes="ml-2">
                            <cheveron-right />
                        </icon-base>
                    </span>
                </td>
            </tr>
            <tr v-if="data.length === 0">
                <td class="border-t px-6 py-4" colspan="4">{{ notFoundMessage }}</td>
            </tr>
        </table>
    </div>
</template>

<script>
import IconBase from '@/Shared/IconBase';
import CheveronRight from '@/Shared/Icons/CheveronRight';

export default {
    components: {
        IconBase,
        CheveronRight,
    },
    props: {
        headerFields: {
            type: Array,
            default: () => [],
        },
        data: {
            type: [Array, Object],
            default: () => [],
        },
        sortField: {
            type: String,
            default: () => '',
        },
        sort: {
            type: String,
            default: () => 'asc',
        },
        entityName: {
            type: String,
            default: () => '',
        },
        rowAction: {
            type: String,
            default: () => 'show',
        },
        notFoundMessage: {
            type: String,
            default: () => 'No items found.',
        },
        hasActions: {
            type: Boolean,
            default: false,
        },
    },
    methods: {
        action (item) {
            return this.route(`${this.entityName}.${this.rowAction}`, item.id);
        },
        performAction (item) {
            return this.$inertia.visit(this.action(item));
        },
    },
}
</script>
