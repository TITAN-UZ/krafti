export const state = () => ({
  likes: {},
  homeworks: {},
  progress: {},
})

export const mutations = {
  likes(state, object) {
    delete object.stat
    delete object.debug

    state.likes[object.id] = object.data
  },
  homeworks(state, object) {
    // state.likes[object.id] = object.data
    state.homeworks[object.id] = object.data
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
  homeworks: (state) => (id) => {
    return state.homeworks[id] || []
  },
  progress: (state) => (id) => {
    return state.progress[id] || {}
  },
}
