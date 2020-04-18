import Vue from 'vue'
import {library, config} from '@fortawesome/fontawesome-svg-core'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'

config.autoAddCss = false
Vue.config.productionTip = false

Vue.component('fa', FontAwesomeIcon)
Vue.use(library)

export default ({app}, inject) => {
  inject('fa', library)
}
