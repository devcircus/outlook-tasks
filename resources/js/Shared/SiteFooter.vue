<template>
    <footer class="w-full bg-blue-500 md:h-240p text-gray-100 px-4 md:px-12 pt-0 pb-4">
        <div class="container mx-auto pt-8">
            <div class="flex flex-wrap mb-6 px-4">
                <div class="w-full md:w-1/3 text-center md:text-left px-3 mb-2 md:mb-0 pb-3 sm:pb-0">
                    <p class="text-white font-bold text-3xl my-6">Outlook Tasks</p>
                    <p class="text-gray-200 font-normal text-sm my-3">maintained by: &nbsp;<a class="text-blue-900 font-medium hover:text-blue-200" :href="meta.admin.sites.github.url">{{ meta.admin.name }}</a></p>
                    <p class="text-gray-200 font-normal text-sm my-3">base template: &nbsp;<a class="text-blue-900 font-medium hover:text-blue-200" :href="meta.links.template.url">{{ meta.links.template.name }}</a></p>
                </div>
                <div class="w-full md:w-1/3 text-center md:text-left px-3 sm:px-1 mb-2 md:mb-0 pb-3 sm:pb-0">
                    <p class="text-white font-medium text-xl my-6">Other tools:</p>
                    <ul>
                        <li class="mb-3"><a class="text-blue-900 text-lg font-medium hover:text-blue-200" :href="meta.links.local.dashboard.url" target="_blank" rel="noopener">{{ meta.links.local.dashboard.name }}</a></li>
                    </ul>
                </div>
                <div class="w-full md:w-1/3 text-center md:text-left px-3 sm:px-1 mb-2 md:mb-0 pb-3 sm:pb-0">
                    <p class="text-white font-medium text-xl my-6">Corporate:</p>
                    <ul>
                        <li class="mb-3"><a class="text-blue-900 text-lg font-medium hover:text-blue-200" :href="meta.links.local.corporate.url" target="_blank" rel="noopener">{{ meta.links.local.corporate.name }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    store: ['meta'],
}
</script>
