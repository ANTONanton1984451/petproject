/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 * todo:Перенести роутер и хранилище в разные места!!!!
 *
 */

require('./bootstrap');

window.Vue = require('vue');


import VueRouter from "vue-router";
import App from './components/App';
Vue.use(VueRouter);


Vue.config.productionTip = false;

import {store} from "./store";
import {router} from "./router";


router.beforeEach((to,from,next)=>{
    if(to.meta.needleAuth  && !store.getters.isAuth ){
        router.push('/lenta');
    }else{
        next();
    }
});

const app = new Vue({
    el: '#app',
    router,
    store,
    components: { App },
    template :'<App/>'
});

