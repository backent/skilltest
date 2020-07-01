<template>
	<v-app>
		<v-app-bar
		  app
	      white
	      clipped-left
	      dense
	      fixed
	    >
	      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

	      <v-toolbar-title>Skill Test</v-toolbar-title>
	    </v-app-bar>

	    <v-navigation-drawer
	      app
	      v-model="drawer"
	      clipped
	    >
	      <v-list
	        nav
	        dense
	      >
	        <v-list-item-group
	          active-class="deep-purple--text text--accent-4"
	        >
	          <v-list-item v-for="(item, index) in nav" :to="item.link" :key="index">
	            <v-list-item-icon>
	              <v-icon>{{item.icon}}</v-icon>
	            </v-list-item-icon>
	            <v-list-item-title>{{item.name}}</v-list-item-title>
	          </v-list-item>



	        </v-list-item-group>
	      </v-list>
	      <template v-slot:append>
	        <div class="pa-2">
	          <v-btn block color="red" @click="logout()">Logout</v-btn>
	        </div>
	      </template>
	    </v-navigation-drawer>
	    <v-content>
	    	<router-view/>
	    </v-content>
	</v-app>
</template>

<script>
	'use strict'
	export default {
		data(){
			return {
				drawer: true,
				nav: [
					{'name': 'DashBoard', link: '/', icon: 'mdi-home'},
				]
			}
		},
		created() {
		},
		methods: {
			logout() {
				return axios.post('/api/logout').then( res=> {
					this.$router.push('/login')
				})
			}
		}
	}
</script>