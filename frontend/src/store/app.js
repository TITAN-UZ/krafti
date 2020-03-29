export const state = () => ({
  header_image: true,
  mobile_menu: false,
})

export const mutations = {
  add_header_image(state) {
    state.header_image = true
  },
  remove_header_image(state) {
    state.header_image = false
  },
  open_mobile_menu(state) {
    state.mobile_menu = true
  },
  close_mobile_menu(state) {
    state.mobile_menu = false
  },
}

export const getters = {
  header_image: (state) => {
    return state.header_image
  },
  mobile_menu: (state) => {
    return state.mobile_menu
  },
}
