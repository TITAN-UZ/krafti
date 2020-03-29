import Vue from 'vue'
import {library} from '@fortawesome/fontawesome-svg-core'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'

Vue.component('fa', FontAwesomeIcon)
Vue.use(library)
Vue.config.productionTip = false

Vue.$fa = Vue.$fontawesome = Vue.prototype.$fontawesome = Vue.prototype.$fa = library
