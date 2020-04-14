import Config from '../../nuxt.config'

Config.srcDir = __dirname
Config.buildDir = '.nuxt/admin'
Config.generate = {
  dir: 'dist/admin',
}
Config.mode = 'spa'

Config.plugins.push('~/plugins/components.js')
Config.plugins.push('~/plugins/charts.js')

Config.router = {
  base: '/admin/',
  linkActiveClass: 'active',
  middleware: ['auth'],
  extendRoutes(routes, resolve) {
    for (const i in routes) {
      if (Object.prototype.hasOwnProperty.call(routes, i)) {
        if (routes[i].name === 'admin') {
          routes[i].redirect = {name: 'admin-orders'}
        }
      }
    }
  },
}

Config.auth.redirect = {
  home: '/',
  login: '/',
  logout: '/',
}

export default Config
