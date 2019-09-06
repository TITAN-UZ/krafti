export const state = () => ({
  header_image: true
});

export const mutations = {
  add_header_image(state) {
    state.header_image = true
  },

  remove_header_image(state) {
    state.header_image = false
  }
};

export const getters = {
  header_image: state => {
    return state.header_image
  }
};
