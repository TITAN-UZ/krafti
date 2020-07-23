export const state = () => ({
  header_image: true,
  mobile_menu: false,
  settings: {},
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
  settings(state, payload) {
    // state.settings[payload.key] = {type: payload.type, value: payload.value}
    state.settings[payload.key] = payload.value
  },
}

export const getters = {
  header_image: (state) => {
    return state.header_image
  },
  mobile_menu: (state) => {
    return state.mobile_menu
  },
  settings: (state) => (key) => {
    return state.settings[key]
  },
}

export const actions = {
  async settings({commit}, $axios) {
    const {data: res} = await $axios.get('web/settings', {params: {limit: 0}})
    for (const row of res.rows) {
      commit('settings', row)
    }
  },
}
