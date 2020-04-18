export default ({app, store}, inject) => {
  inject('app', {
    header_image: {
      get() {
        return store.getters['app/header_image']
      },
      set(value) {
        if (value !== this.get()) {
          if (value === true) {
            store.commit('app/add_header_image')
          } else {
            store.commit('app/remove_header_image')
          }
        }
      },
    },

    mobile_menu: {
      get() {
        return store.getters['app/mobile_menu']
      },
      set(value) {
        if (value !== this.get()) {
          if (value === true) {
            store.commit('app/open_mobile_menu')
          } else {
            store.commit('app/close_mobile_menu')
          }
        }
      },
    },
  })

  inject('hasScope', (scope) => {
    if (!app.$auth.loggedIn) {
      return false
    }
    if (!scope) {
      return true
    }
    if (scope.includes('/')) {
      return app.$auth.hasScope(scope) || app.$auth.hasScope(scope.replace(/\/.*/, ''))
    }
    return app.$auth.hasScope(scope) || app.$auth.hasScope(`${scope}/get`)
  })

  inject('image', (data, size = null, type = null) => {
    const params = [app.$settings.image_url]
    params.push(/^\d+$/.test(data) ? data : data.id)
    if (size) {
      params.push(size)
    }
    if (type) {
      params.push(type)
    }

    let url = params.join('/')
    url += '?t=' + (data.updated_at ? new Date(data.updated_at) : new Date()).getTime()

    return url
  })

  inject('file', (data) => {
    const params = [app.$settings.file_url]
    params.push(/^\d+$/.test(data) ? data : data.id)

    let url = params.join('/')
    if (data.updated_at) {
      url += '?t=' + new Date(data.updated_at).getTime()
    }

    return url
  })
}
