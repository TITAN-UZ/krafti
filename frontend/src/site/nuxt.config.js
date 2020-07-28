import Config from '../../nuxt.config'

Config.srcDir = __dirname
Config.buildDir = '.nuxt/site'
Config.generate = {
  dir: 'dist/site',
}
Config.mode = 'universal'

Config.modules.push('@nuxtjs/markdownit')
Config.modules.push('@nuxtjs/sitemap')

Config.plugins.push('~/plugins/components.client.js')
Config.plugins.push('~/plugins/polyfills.client.js')
Config.plugins.push('~/plugins/metrika.js')

Config.router = {
  base: '/',
  linkActiveClass: 'active',
  middleware: ['auth'],
  extendRoutes(routes) {
    for (const i in routes) {
      if (Object.prototype.hasOwnProperty.call(routes, i)) {
        if (routes[i].name === 'office') {
          routes[i].redirect = {name: 'office-courses'}
        }
      }
    }
  },
}

Config.build.extend = (config) => {
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
}

Config.auth.redirect = {
  home: '/',
  login: '/service/auth',
  logout: '/',
}

Config.markdownit = {
  html: true,
  linkify: true,
  typographer: true,
  breaks: true,
  injected: true,
}

Config.sitemap = {
  hostname: process.env.SITE_URL,
  gzip: false,
  exclude: ['/admin', '/admin/**', '/office', '/office/**', '/profile', '/profile/**', '/service', '/service/**'],
}

Config.fontawesome = {
  component: 'fa',
  icons: {
    brands: ['faInstagram', 'faWhatsapp', 'faVk', 'faFacebook', 'faPinterest', 'faTwitter'],
  },
  proIcons: {
    duotone: [
      'faUserCircle',
      'faTimesCircle',
      'faCheckCircle',
      'faPlusCircle',
      'faPaperPlane',
      'faCircle',
      'faEye',
      'faShare',
      'faThumbsUp',
      'faThumbsDown',
      'faUser',
      'faCopy',
      'faMale',
      'faFemale',
    ],
    light: ['faStream', 'faTimes', 'faHeart', 'faStar'],
    solid: [
      'faCalendarAlt',
      'faHeart',
      'faCog',
      'faStar',
      'faRedo',
      'faTimes',
      'faUserCircle',
      'faCircle',
      'faPlay',
      'faChevronLeft',
      'faChevronRight',
      'faDownload',
    ],
  },
}

Config.server =
  process.env.NODE_ENV === 'production'
    ? {socket: '../tmp/nuxt.socket', timing: {total: true}}
    : {host: '0.0.0.0', port: 4100}

export default Config
