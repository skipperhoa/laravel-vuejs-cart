import Vue from 'Vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueAxios from 'vue-axios';
import axios from 'axios';
Vue.use(VueAxios,axios);

// import cac template vao day
import App from './App.vue';
import Listsp from './components/Listsp.vue';
import Dathang from './components/Dathang.vue';


const routes = [
  {
      name: 'Listsp',
      path: '/',
      component: Listsp
  },
   {
      name: 'Dathang',
      path: '/dathang',
      component: Dathang
  }
 
];

const router = new VueRouter({mode:'history', routes: routes});
new Vue(Vue.util.extend({router},App)).$mount('#app');