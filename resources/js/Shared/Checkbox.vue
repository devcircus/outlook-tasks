<template>
    <div class="block">
        <div class="mt-2">
            <label class="inline-flex items-center">
                <span class="mr-2 text-sm font-semibold">{{ label }}:</span>
                <input type="checkbox" class="form-checkbox text-blue-600 h-6 w-6" :class="{ error: errors.length }" :checked="checked" @change="$emit('input', $event.target.checked)">
                <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>
            </label>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            default: () => 'Checkbox',
        },
        checked: {
            type: Boolean,
            default: () => false,
        },
        errors: {
            type: Array,
            default: () => [],
        },
    },

}
</script>
