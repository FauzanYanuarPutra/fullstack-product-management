export default defineNuxtConfig({
  css: ['~/assets/css/main.css'],
  compatibilityDate: '2026-04-15',
  devtools: { enabled: true },
  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:3001',
    },
  },
  app: {
    head: {
      htmlAttrs: {
        lang: 'id',
      },
      title: 'Product Manager',
      meta: [
        {
          name: 'description',
          content:
            'Aplikasi katalog produk dengan daftar, detail, pencarian, dan tambah produk.',
        },
        {
          name: 'theme-color',
          content: '#0d0b0e',
        },
      ],
      link: [
        {
          rel: 'preconnect',
          href: 'https://fonts.googleapis.com',
        },
        {
          rel: 'preconnect',
          href: 'https://fonts.gstatic.com',
          crossorigin: '',
        },
        {
          rel: 'stylesheet',
          href: 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap',
        },
      ],
    },
  },
});
