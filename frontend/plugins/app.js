export default ({app, store}, inject) => {
  inject('app', {

    header_image: {
      get() {
        return store.getters['app/header_image'];
      },
      set(value) {
        if (value !== this.get()) {
          if (value === false) {
            store.commit('app/remove_header_image')
          } else {
            store.commit('app/add_header_image')
          }
        }
      },
    }

  })
}
