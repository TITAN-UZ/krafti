export const state = () => ({
  likes: {},
  progress: {},
})

export const mutations = {
  likes(state, object) {
    state.likes[object.id] = object.data
  },
  progress(state, object) {
    state.progress[object.id] = {
      section: object.data.section,
      rank: object.data.rank,
    }
  },
}

export const getters = {
  likes: (state) => (id) => {
    return state.likes[id] || 0
  },
  progress: (state) => (id) => {
    return state.progress[id] || {}
  },
}
