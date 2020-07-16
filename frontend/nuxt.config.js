const localEnv = require('fs').existsSync('../core/.env.local')
require('dotenv').config({path: `../core/${localEnv ? '.env.local' : '.env'}`})

export default {
  mode: 'universal',
  head: {
    title: 'Крафти',
    meta: [
      {charset: 'utf-8'},
      {name: 'viewport', content: 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0'},
      {name: 'msapplication-TileColor', content: '#ffffff'},
      {name: 'theme-color', content: '#ffffff'},
      {name: 'apple-mobile-web-app-title', content: 'Крафти'},
      {'http-equiv': 'X-UA-Compatible', content: 'IE=edge'},
      {hid: 'title', name: 'title', content: 'Крафти'},
      {
        hid: 'description',
        name: 'description',
        content:
          'Мы — Крафти, новое слово в онлайн-обучении. Мы верим, что в каждом ребенке живет настоящий творец, а еще — что в каждом взрослом живет настоящий ребенок. Именно поэтому творческое и интеллектуальное развитие взрослых и детей стало нашей страстью!',
      },
      {hid: 'og:title', property: 'og:title', content: 'Крафти'},
      {
        hid: 'og:description',
        property: 'og:description',
        content:
          'Мы — Крафти, новое слово в онлайн-обучении. Мы верим, что в каждом ребенке живет настоящий творец, а еще — что в каждом взрослом живет настоящий ребенок. Именно поэтому творческое и интеллектуальное развитие взрослых и детей стало нашей страстью!',
      },
      {hid: 'og:image', property: 'og:image', content: process.env.SITE_URL + 'favicons/android-chrome-512x512.png'},
      {property: 'og:site_name', content: 'Krafti.ru'},
    ],
    link: [
      {rel: 'icon', type: 'image/x-icon', href: '/favicons/favicon.ico'},
      {rel: 'icon', type: 'image/png', sizes: '32x32', href: '/favicons/favicon-32x32.png'},
      {rel: 'icon', type: 'image/png', sizes: '16x16', href: '/favicons/favicon-16x16.png'},
      {rel: 'apple-touch-icon', sizes: '180x180', href: '/favicons/apple-touch-icon.png'},
      {rel: 'manifest', href: '/favicons/site.webmanifest'},
    ],
  },
  loading: {
    color: '#ff7474',
  },
  css: ['~assets/scss/styles.scss', '@fortawesome/fontawesome-svg-core/styles.css'],
  plugins: [
    '../_common/plugins/app.js',
    '../_common/plugins/settings.js',
    '../_common/plugins/axios.js',
    '../_common/plugins/fontawesome.js',
    '../_common/plugins/filters.js',
    '../_common/plugins/components.js',
    {src: '../_common/plugins/mixins.js', mode: 'client'},
    {src: '../_common/plugins/tables.js', mode: 'client'},
    {src: '../_common/plugins/inputs.js', mode: 'client'},
    {src: '../_common/plugins/filepond.js', mode: 'client'},
  ],
  buildModules: ['@nuxtjs/style-resources'],
  build: {
    extractCSS: process.env.NODE_ENV === 'production',
  },
  modules: [
    'bootstrap-vue/nuxt',
    'nuxt-izitoast',
    // 'nuxt-purgecss',
    '@nuxtjs/axios',
    '@nuxtjs/auth',
    '@nuxtjs/pwa',
    '@nuxtjs/moment',
    '@nuxtjs/dotenv',
  ],
  bootstrapVue: {
    css: false,
    bvCSS: false,
  },
  moment: {
    locales: ['ru'],
    localesToKeep: ['ru'],
    defaultLocale: 'ru',
  },
  dotenv: {
    path: '../core/',
    filename: localEnv ? '.env.local' : '.env',
    only: [
      'SITE_URL',
      'COINS_PROMO',
      'COINS_BUY_BONUS',
      'COINS_SUBSCRIBE',
      'COINS_HOMEWORK',
      'COINS_PALETTE',
      'CHILDREN_MAX',
    ],
  },
  axios: {
    baseURL: process.env.SITE_URL + 'api/',
    progress: true,
    proxyHeaders: false,
    credentials: false,
  },
  auth: {
    watchLoggedIn: true,
    resetOnError: true,
    strategies: {
      local: {
        endpoints: {
          login: {url: 'security/login', method: 'post', propertyName: 'token'},
          logout: {url: 'security/logout', method: 'post'},
          user: {url: 'user/profile', method: 'get', propertyName: 'user'},
        },
      },
    },
  },
  izitoast: {
    position: 'bottomRight',
    transitionIn: 'bounceInLeft',
    transitionOut: 'fadeOutRight',
  },
  styleResources: {
    scss: '~assets/scss/_variables.scss',
  },
}
