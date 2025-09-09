// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    compatibilityDate: '2025-07-15',
    devtools: {enabled: true},
    modules: ['@nuxt/eslint', '@nuxt/ui-pro', '@nuxt/image', '@nuxtjs/mdc'],
    css: ['~/assets/css/main.css'],
    uiPro: {
        license: process.env.NUXT_UI_PRO_LICENSE
    },
    mdc: {
        highlight: {
            langs: ['php', 'javascript', 'python', 'html', 'css', 'bash'],
        },
    },
    runtimeConfig: {
        apiUrl: process.env.API_URL || 'http://api:8000',
        public: {
            apiUrl: process.env.PUBLIC_API_URL || 'http://localhost:8000'
        }
    },
    app: {
        head: {
            title: 'Mark Railton',
        }
    }
})