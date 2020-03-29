import Vue from 'vue'
import YaMetrika from 'vue-yandex-metrika'
import VueAnalytics from 'vue-analytics'

export default (ctx) => {
  if (process.client) {
    Vue.use(YaMetrika, {
      id: 54983017,
      router: ctx.app.router,
      ignoreRoutes: ['admin'],
      env: process.env.NODE_ENV,
    })
    Vue.use(VueAnalytics, {
      id: 'UA-144748143-1',
      router: ctx.app.router,
      ignoreRoutes: ['admin'],
    })
  }
}
