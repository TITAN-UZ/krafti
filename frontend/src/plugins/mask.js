import Vue from 'vue'

import VueMask from 'v-mask'
import PhoneInput from 'vue-tel-input'
import 'vue-tel-input/dist/vue-tel-input.css'

Vue.use(VueMask)
Vue.component('phone-input', PhoneInput)
