<template>
    <layout title="Home">
        <h1 class="w-2/3 uppercase text-3xl font-medium text-blue-900 pb-4 pt-4 mb-8">
            <a :href="route('outlook.signin')" class="btn btn-blue">Connect to Outlook</a>
            <span class="text-sm text-gray-800">Token: {{ token }}</span>
        </h1>
    </layout>
</template>

<script>
import Layout from '@/Shared/Layout';

export default {
    components: {
        Layout,
    },
    props: {
        token: {
            type: String,
            default: () => '',
        },
    },
}
</script>
