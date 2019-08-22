import Vue from 'vue'
import YaMetrika from 'vue-yandex-metrika'

export default ctx => {
  Vue.use(YaMetrika, {
    id: 54983017,
    router: ctx.app.router,
    env: process.env.NODE_ENV
  });
}
