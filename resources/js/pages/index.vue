<template>
	<div>
		<h1>Skill test</h1>
	</div>
</template>

<script type="text/javascript">
	'use strict'
	export default {
		data() {
			return {
				
			}
		},
		created() {
			this.getUser()
		},
		methods: {
			async getUser() {
				return await axios.get('/api/user')
			}
		}
	}
</script>