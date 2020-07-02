/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vuetify from 'vuetify';
import VueRouter from 'vue-router';
import { Cropper} from 'vue-advanced-cropper'
 
window.Vue = require('vue');
Vue.use(Vuetify)
Vue.use(VueRouter)
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('cropper', Cropper);


const main = require('./pages/main.vue').default;
const index = require('./pages/index.vue').default;
const login = require('./pages/login.vue').default;
const register = require('./pages/register.vue').default;

const soal1 = require('./pages/soal1.vue').default;
const soal2 = require('./pages/soal2.vue').default;
const soal5 = require('./pages/soal5.vue').default;

const routes = [
	{	path: '/', 
		component: main,
		children: [
			{ path: '/', component: index},
			{ path: '/soal1', component: soal1},
			{ path: '/soal2', component: soal2},
			{ path: '/soal5', component: soal5}
		]
	},
	{  	path: '/login',
		name: 'login',
		component: login
	},
	{  	path: '/register',
		name: 'register',
		component: register
	}
];

const  router = new VueRouter({
	mode: 'history',
	routes,
});

let token = null;
router.beforeEach(async (to, from, next) => {
	if (token == null) {
		let result = await axios.get('/api/checkauth');
		token = result.data.response.records;
		if (token != null && typeof token == 'string') {
			axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
		}
	}
	if (to.name !== 'login' && token == null) {
		next({name: 'login'});
	} else {
		next()
	}

	next();
});


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const vuetifyOptions={};
const app = new Vue({
    vuetify: new Vuetify(vuetifyOptions),
    router: router,
    el: '#app',
    render: h => h('router-view')
});

