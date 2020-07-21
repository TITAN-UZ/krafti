import Vue from 'vue'
import {VueMasonryPlugin} from 'vue-masonry'
import VueGallery from 'vue-gallery'
import UploadPhoto from '../components/upload/photo'
import UploadHomework from '../components/upload/homework'
import UploadBg from '../components/upload/bg'

Vue.use(VueMasonryPlugin)
Vue.component('vue-gallery', VueGallery)
Vue.component('upload-photo', UploadPhoto)
Vue.component('upload-homework', UploadHomework)
Vue.component('upload-bg', UploadBg)
