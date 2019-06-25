<template>
    <layout title="Home">
        <h1 class="w-2/3 flex flex-col justify-between uppercase text-3xl font-medium text-blue-900 pb-4 pt-4 mb-8">
            <div v-if="! $page.auth.user.has_oauth_tokens" class="flex flex-col">
                <span class="text-sm text-gray-800">
                    You are currently not connected to Outlook. Click the button below to sign in.
                </span>
                <a :href="route('outlook.signin')" class="btn btn-blue w-100p">Connect to Outlook</a>
            </div>
            <div v-if="token">
                <span class="text-sm text-gray-800">Display name: {{ displayName }}</span>
                <span class="text-sm text-gray-800">Token: {{ token }}</span>
            </div>
            <div v-if="error">
                <span class="text-sm text-gray-800">Token: {{ error }}</span>
                <span v-if="errorDescription" class="text-sm text-gray-800">Token: {{ errorDescription }}</span>
            </div>
            <div v-if="messagesAvailable" class="flex flex-col">
                <span v-for="message in messages" :key="message.id" class="text-sm text-gray-800">{{ message.subject }}</span>
            </div>
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
        error: {
            type: String,
            default: () => '',
        },
        errorDescription: {
            type: String,
            default: () => '',
        },
        displayName: {
            type: String,
            default: () => 'Guest',
        },
        messages: {
            type: Array,
            default: () => [],
        },
    },
    computed: {
        messagesAvailable () {
            return ! this.isObjectEmpty(this.messages);
        },
    },
}
</script>
