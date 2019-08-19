export default {
  mode: 'universal',
  head: {
    title: 'Крафти',
    meta: [
      {charset: 'utf-8'},
      {name: 'viewport', content: 'width=device-width, initial-scale=1'},
      {name: 'msapplication-TileColor', content: '#ffffff'},
      {name: 'theme-color', content: '#ffffff'},
      {name: 'viewport', content: 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0'},
      {name: 'title', content: 'Крафти'},
      {name: 'apple-mobile-web-app-title', content: 'Крафти'},
      {name: 'description', content: ''},
      {property: 'og:title', content: 'Крафти'},
      {property: 'og:site_name', content: 'Krafti.ru'},
      {property: 'og:description', content: ''},
      //{property: 'og:url', content: ''},
      {property: 'og:image', content: '/favicons/android-chrome-512x512.png'},
    ],
    link: [
      {rel: 'icon', type: 'image/x-icon', href: '/favicons/favicon.ico'},
      {rel: 'icon', type: 'image/png', sizes: '32x32', href: '/favicons/favicon-32x32.png'},
      {rel: 'icon', type: 'image/png', sizes: '16x16', href: '/favicons/favicon-16x16.png'},
      {rel: 'apple-touch-icon', sizes: '180x180', href: '/favicons/apple-touch-icon.png'},
      {rel: 'manifest', href: '/favicons/site.webmanifest'},
    ]
  },
  loading: {
    color: '#ff7474',
    //throttle: 0,
  },
  css: [
    '~assets/scss/styles.scss',
  ],
  plugins: [
    '~/plugins/settings.js',
    '~/plugins/axios.js',
    '~/plugins/fontawesome.js',
    '~/plugins/filters.js',
    {src: '~/plugins/autosuggest.js', ssr: false},
    {src: '~/plugins/mixins.js', ssr: false},
    {src: '~/plugins/alertify.js', ssr: false},
    {src: '~/plugins/tables.js', ssr: false},
    {src: '~/plugins/mask.js', ssr: false},
    {src: '~/plugins/filepond.js', ssr: false},
    {src: '~/plugins/tags.js', ssr: false},
  ],
  modules: [
    'bootstrap-vue/nuxt',
    '@nuxtjs/axios',
    '@nuxtjs/auth',
    '@nuxtjs/pwa',
    '@nuxtjs/moment',
    'nuxt-izitoast',
    //'@nuxtjs/eslint-module'
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
  axios: {
    baseURL: 'https://krafti.2head.ru/api/',
    //baseURL: 'http://s4.h2.modhost.test/api/',
    progress: true,
    proxyHeaders: false,
    credentials: false
  },
  auth: {
    redirect: {
      home: '/',
      login: '/service/auth',
      logout: '/',
    },
    watchLoggedIn: true,
    resetOnError: true,
    strategies: {
      local: {
        endpoints: {
          login: {url: 'security/login', method: 'post', propertyName: 'token'},
          user: {url: 'user/profile', method: 'get', propertyName: 'user'},
          //logout: {url: 'security/logout', method: 'post'},
          logout: false
        },
      },
    }
  },
  router: {
    linkActiveClass: 'active',
    middleware: [
      'auth'
    ],
    extendRoutes(routes, resolve) {
      for (let i in routes) {
        if (routes.hasOwnProperty(i)) {
          if (routes[i].name == 'admin') {
            routes[i].redirect = '/admin/courses';
          } else if (routes[i].name == 'office') {
            routes[i].redirect = '/office/store';
          }
        }
      }
    }
  },
  izitoast: {
    position: 'bottomRight',
    transitionIn: 'bounceInLeft',
    transitionOut: 'fadeOutRight',
  },
  build: {
    extend(config, ctx) {
    }
  },
  server: {
    port: 3000,
    //host: '0.0.0.0',
  },
}
