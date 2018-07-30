
import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueSession from 'vue-session'
Vue.use(VueSession)

import VueAxios from 'vue-axios';
import axios from 'axios';
Vue.use(VueAxios, axios);

import env from 'vue-vitual-env'
import envConfig from './env.js'  
import envConfigProd from './envprods.js'
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red'
})
if(process.env.NODE_ENV == 'development'){
  Vue.use(env, envConfig)
}else{
  Vue.use(env,envConfigProd)
}

import Vuetify from 'vuetify'
Vue.use(Vuetify)

const vueConfig = require('vue-config')
const configs = {
  API: '0b4867278568870ddf0b504a42ab3f9d97f4cd9c197b60ab84aded10f2ba4eb7',
  SERVER: env.get('SERVER')
}

Vue.use(vueConfig, configs)

import {ServerTable, ClientTable, Event} from 'vue-tables-2';
Vue.use(ClientTable,{},{perPage: 5});

import App from './App.vue';
import Example from './components/Example.vue';
import Login from './components/login/Login.vue';
import Dashboard from './components/dashboard/Dashboard.vue';
import DetailRegister from './components/dashboard/DetailRegister.vue';
import DetailNotaList from './components/dashboard/DetailNotaList.vue';
import Status from './components/status/Status.vue';
import TransferBank from './components/transferbank/TransferBank.vue';
import DetailPiutang from './components/piutang/DetailPiutang.vue';
import Request from './components/request/Request.vue';
import Profile from './components/profiletoko/Profile.vue';
import AddTransfer from './components/transfer/AddTransfer.vue';
import AddRequest from './components/request/AddRequest.vue';
import ForgotPassword from './components/password/ForgotPassword.vue';
const routes = [
  {
      name: 'Home',
      path: '/',
      component: Login
  },
  {
      name: 'Login',
      path: '/login',
      component: Login
  },
  {
      name: 'Dashboard',
      path: '/dashboard',
      component: Dashboard
  },
  {
      name: 'DetailRegister',
      path: '/detail',
      component: DetailRegister
  },
  {
      name: 'DetailNotaList',
      path: '/nota',
      component: DetailNotaList
  },
  {
      name: 'Status',
      path: '/status',
      component: Status
  },
  {
      name: 'TransferBank',
      path: '/transfer-bank',
      component: TransferBank
  },
  {
      name: 'DetailPiutang',
      path: '/detail-piutang',
      component: DetailPiutang
  },
  {
      name: 'Request',
      path: '/request',
      component: Request
  },
  {
      name: 'Profile',
      path: '/toko/profile',
      component: Profile
  },
  {
      name: 'AddTransfer',
      path: '/add/transfer',
      component: AddTransfer
  },
  {
      name: 'AddRequest',
      path: '/add/request',
      component: AddRequest
  },
  {
      name: 'ForgotPassword',
      path: '/change-password',
      component: ForgotPassword
  }
]; 

const router = new VueRouter({ mode: 'history', routes: routes});
new Vue(Vue.util.extend({ router }, App)).$mount('#app');