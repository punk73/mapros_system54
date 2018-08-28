
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import mymain from './components/Main.vue';
import join from './components/Join.vue';
import config from './components/Config.vue';
import modal from './components/Modal.vue';

const routes = [
  { path: '/', component: mymain },
  // { path: '/join', component: join, props: true },
  { path: '/config', component: config, props: true },

]

const router = new VueRouter({
  routes // short for `routes: routes`
})

const app = new Vue({
    el: '#app',

    router,
    
    components: {
    	mymain
    },
});
