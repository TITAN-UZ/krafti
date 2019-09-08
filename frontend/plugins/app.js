export default ({app, store}, inject) => {
  inject('app', {

    header_image: {
      get() {
        return store.getters['app/header_image'];
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
        return store.getters['app/mobile_menu'];
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
    }

  })
}
