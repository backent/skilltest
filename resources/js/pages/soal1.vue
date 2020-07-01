<template>
	<v-app>
		<v-container>
			<v-row>
				<v-col>
					<v-card>
						<v-card-title>
							Soal 1
						</v-card-title>
						<v-card-text>
							<h4>Jawaban</h4>
							<span v-html="result"></span>
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
	</v-app>
</template>

<script type="text/javascript">
	'use strict'
	export default {
		data() {
			return {
				result: ""
			}
		},
		created() {
			this.getResult()
		},
		methods: {
			async getResult() {
				return axios.get('/api/soal/1')
				.then( res => {
					this.result = res.data.records
				})
			}
		}
	}
</script>