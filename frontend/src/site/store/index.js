export default {
  actions: {
    async nuxtServerInit({dispatch}, {$axios}) {
      await dispatch('app/settings', $axios)
    },
  },
}
