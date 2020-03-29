import Vue from 'vue'
import VueAutosuggest from 'vue-autosuggest'
import PickVideo from '../components/pick-video'
import PickUser from '../components/pick-user'

Vue.use(VueAutosuggest)

Vue.component('pick-video', PickVideo)
Vue.component('pick-user', PickUser)
