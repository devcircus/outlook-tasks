export const config = {
    appName: process.env.MIX_APP_NAME,
    apiUrl: process.env.MIX_API_URL,
    timezone: process.env.MIX_FRONTEND_TIMEZONE,
    algoliaId: process.env.MIX_ALGOLIA_APP_ID,
    algoliaSearch: process.env.MIX_ALGOLIA_SEARCH,
    pusherKey: process.env.MIX_PUSHER_APP_KEY,
    pusherCluster: process.env.MIX_PUSHER_APP_CLUSTER,
    admin: {
        name: process.env.MIX_ADMIN_NAME,
        email:process.env.MIX_ADMIN_EMAIL,
        sites: {
            github: {
                profile: process.env.MIX_ADMIN_GITHUB_PROFILE,
                url: process.env.MIX_ADMIN_GITHUB_URL,
            },
        },
    },
    links: {
        template: {
            name: process.env.MIX_TEMPLATE_NAME,
            url: process.env.MIX_TEMPLATE_URL,
        },
        local: {
            dashboard: {
                name: process.env.MIX_LINK_NAME,
                url: process.env.MIX_LINK_URL,
            },
            corporate: {
                name: process.env.MIX_LINK_CORPORATE_NAME,
                url: process.env.MIX_LINK_CORPORATE_URL,
            },
        },
    },
}
