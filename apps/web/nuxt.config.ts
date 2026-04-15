export default defineNuxtConfig({
  css: ['~/assets/css/main.css'],
  devtools: { enabled: true },
  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:3001',
    },
  },
  app: {
    head: {
      title: 'Product Manager',
      meta: [
        {
          name: 'description',
          content:
            'Responsive product manager frontend built with Nuxt 3.',
        },
      ],
    },
  },
});

