require('dotenv').config({path: '../core/.' + (process.env.USER === 's4000' ? 'prod' : 'dev') + '.env'})

export default {
  mode: 'universal',
  srcDir: './src/',
  buildDir: './.nuxt/',
  generate: {
    dir: 'dist/',
  },
  head: {
    title: 'Крафти',
    meta: [
      {charset: 'utf-8'},
      {name: 'viewport', content: 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0'},
      {name: 'msapplication-TileColor', content: '#ffffff'},
      {name: 'theme-color', content: '#ffffff'},
      {name: 'apple-mobile-web-app-title', content: 'Крафти'},
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
  css: ['~assets/scss/styles.scss'],
  plugins: [
    '~/plugins/app.js',
    '~/plugins/settings.js',
    '~/plugins/axios.js',
    '~/plugins/fontawesome.js',
    '~/plugins/filters.js',
    '~/plugins/vimeo.js',
    '~/plugins/metrika.js',
    {src: '~/plugins/mixins.js', mode: 'client'},
    {src: '~/plugins/alertify.js', mode: 'client'},
    {src: '~/plugins/tables.js', mode: 'client'},
    {src: '~/plugins/inputs.js', mode: 'client'},
    {src: '~/plugins/modals.js', mode: 'client'},
    {src: '~/plugins/filepond.js', mode: 'client'},
  ],
  modules: [
    'bootstrap-vue/nuxt',
    'nuxt-izitoast',
    '@nuxtjs/axios',
    '@nuxtjs/auth',
    '@nuxtjs/pwa',
    '@nuxtjs/moment',
    '@nuxtjs/markdownit',
    '@nuxtjs/dotenv',
    '@nuxtjs/sitemap',
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
    filename: '.' + (process.env.USER === 's4000' ? 'prod' : 'dev') + '.env',
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
  markdownit: {
    html: true,
    linkify: true,
    typographer: true,
    breaks: true,
    injected: true,
  },
  axios: {
    baseURL: process.env.SITE_URL + 'api/',
    progress: true,
    proxyHeaders: false,
    credentials: false,
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
          logout: {url: 'security/logout', method: 'post'},
          user: {url: 'user/profile', method: 'get', propertyName: 'user'},
        },
      },
    },
  },
  router: {
    linkActiveClass: 'active',
    middleware: ['auth'],
    extendRoutes(routes) {
      for (const i in routes) {
        if (Object.prototype.hasOwnProperty.call(routes, i)) {
          if (routes[i].name === 'admin') {
            routes[i].redirect = {name: 'admin-courses'}
          } else if (routes[i].name === 'office') {
            routes[i].redirect = {name: 'office-courses'}
          }
        }
      }
    },
  },
  izitoast: {
    position: 'bottomRight',
    transitionIn: 'bounceInLeft',
    transitionOut: 'fadeOutRight',
  },
  build: {
    extend(config) {
      // here I tell webpack not to include jpgs and pngs
      // as base64 as an inline image
      config.module.rules.find(
        (rule) => rule.use && rule.use[0].loader === 'url-loader',
      ).exclude = /background\/.*?\.(jpe?g|png)$/i

      // now i configure the responsive-loader
      config.module.rules.push({
        test: /background\/.*?\.(jpe?g|png)$/i,
        loader: 'responsive-loader',
        options: {
          // min: 720,
          // max: 3000,
          // step: 10,
          sizes: [2560, 1440, 960, 640],
          placeholder: false,
          quality: 85,
          adapter: require('responsive-loader/sharp'),
        },
      })
    },
  },
  // buildModules: ['@nuxtjs/eslint-module'],
  /* generate: {
    async routes() {
      const [authors, courses] = await Promise.all([
        axios.get(process.env.SITE_URL + 'api/web/authors'),
        axios.get(process.env.SITE_URL + 'api/web/courses'),
      ])

      const routes = []
      authors.data.rows.forEach((v) => {
        routes.push('/team/' + v.id)
      })
      courses.data.rows.forEach((v) => {
        routes.push('/courses/' + v.id)
      })

      return routes
    },
    exclude: [/^\/(admin|office|profile|p|service)\//],
  }, */
  sitemap: {
    hostname: process.env.SITE_URL,
    gzip: false,
    exclude: ['/admin', '/admin/**', '/office', '/office/**', '/profile', '/profile/**', '/service', '/service/**'],
  },
  server:
    process.env.NODE_ENV === 'production'
      ? {socket: '../tmp/nuxt.socket', timing: {total: true}}
      : {host: 'localhost', port: 3876},
}
