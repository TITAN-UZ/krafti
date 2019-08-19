import Vue from 'vue'
import VueAutosuggest from 'vue-autosuggest';
import PickVideo from '../components/pick-video';
import PickAuthor from '../components/pick-author';

Vue.use(VueAutosuggest);

Vue.component('pick-video', PickVideo);
Vue.component('pick-author', PickAuthor);
