import 'es6-promise/auto'
import axios from 'axios'
import './bootstrap'
import Vue from 'vue'
import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import Index from './Index'
import auth from './auth'
import router from './router'
// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import VueFlashMessage from '@smartweb/vue-flash-message';
import VueTheMask from 'vue-the-mask';
import VueHtmlToPaper from 'vue-html-to-paper';

const options = {
  name: '_blank',
  specs: [
    'fullscreen=yes',
    'titlebar=yes',
    'scrollbars=yes'
  ],
  styles: [
    'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
    'https://unpkg.com/kidlat-css/css/kidlat.css'
  ]
}


Vue.component('pagination', require('laravel-vue-pagination'));

Vue.use(VueHtmlToPaper, options);

//Install maskVue
Vue.use(VueTheMask)

// Install BootstrapVue
Vue.use(BootstrapVue)

// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

//Install VueFlashMessage
Vue.use(VueFlashMessage);

// Set Vue globally
window.Vue = Vue

// Set Vue router
Vue.router = router
Vue.use(VueRouter)

// Set Vue authentication
Vue.use(VueAxios, axios)
// axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api`
axios.defaults.baseURL = `/api`
Vue.use(VueAuth, auth)

// Load Index
Vue.component('index', Index)
const app = new Vue({
  el: '#app',
  router
});
