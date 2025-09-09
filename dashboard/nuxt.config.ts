export default defineNuxtConfig({
    compatibilityDate: '2025-07-15',
    devtools: {enabled: true},
    modules: ['@nuxt/eslint', '@nuxt/ui-pro', '@nuxt/image', '@nuxtjs/mdc'],
    css: ['~/assets/css/main.css'],
    colorMode: {
        preference: 'system'
    },
    uiPro: {
        license: process.env.NUXT_UI_PRO_LICENSE
    },
    mdc: {
        highlight: {
            langs: ['php', 'javascript', 'python', 'html', 'css', 'bash'],
        },
    },
    runtimeConfig: {
        apiUrl: process.env.API_URL || 'https://api.markrailton.com',
        public: {
            apiUrl: process.env.PUBLIC_API_URL || 'https://api.markrailton.com'
        }
    },
    app: {
        head: {
            title: 'Dashboard - Mark Railton',
        }
    }
})