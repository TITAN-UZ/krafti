import Config from '../../nuxt.config'

Config.srcDir = __dirname
Config.buildDir = '.nuxt/admin'
Config.generate = {
  dir: 'dist/admin',
}
Config.mode = 'spa'

Config.plugins.push('~/plugins/components.js')
Config.plugins.push('~/plugins/charts.js')
Config.plugins.push('~/plugins/modals.js')

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

Config.fontawesome = {
  component: 'fa',
  proIcons: {
    light: ['faTimes'],
    solid: [
      'faBars',
      'faChevronLeft',
      'faChevronRight',
      'faSync',
      'faKey',
      'faArrowDown',
      'faArrowUp',
      'faArrowRight',
      'faArrowLeft',
      'faPlus',
      'faTimes',
      'faCheck',
      'faEdit',
      'faVideo',
      'faColumns',
      'faAlignRight',
      'faPowerOff',
      'faPlay',
      'faExternalLink',
      'faInfoCircle',
      'faBackspace',
      'faFilter',
      'faCalendarAlt',
    ],
  },
}

Config.bootstrapVue.componentPlugins = [
  'LayoutPlugin',

  'FormPlugin',
  'FormGroupPlugin',
  'FormTextareaPlugin',
  'FormInputPlugin',
  'FormCheckboxPlugin',
  'FormRadioPlugin',
  'FormSelectPlugin',
  'FormTagsPlugin',
  'InputGroupPlugin',

  'AvatarPlugin',
  'AlertPlugin',
  'NavbarPlugin',
  'ImagePlugin',

  'ButtonPlugin',
  'TabsPlugin',
  'ModalPlugin',
  'TablePlugin',
  'PaginationPlugin',
  'SpinnerPlugin',

  'VBTooltipPlugin',
]

Config.server =
  process.env.NODE_ENV === 'production'
    ? {socket: '../tmp/admin.socket', timing: {total: true}}
    : {host: '0.0.0.0', port: 4000}

export default Config
