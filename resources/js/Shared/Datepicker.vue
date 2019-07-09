<template>
    <div class="w-3/4">
        <label v-if="label" :for="id" class="form-label">{{ label }}:</label>
        <datepicker :id="id" v-model="date" :format="format" :use-utc="true" class="border border-gray-300 p-2 rounded" :class="position" @input="$emit('input', date)" />
        <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';

export default {
    components: { Datepicker },
    props: {
        value: {
            type: String,
            default: null,
            required: false,
        },
        label: {
            type: String,
            default: 'Date',
        },
        id: {
            type: String,
            default () {
                return `datepicker-${this._uid}`;
            },
        },
        position: {
            type: String,
            default () {
                return 'datepicker-bottom';
            },
        },
        errors: {
            type: Array,
            default: () => [],
        },
    },
    data () {
        return {
            format: 'yyyy-MM-dd',
            date: this.value,
        }
    },
}
</script>
