<template>
	<v-app>
		<v-container>
			<v-row>
				<v-col>
					<v-card>
						<v-card-title>
							Soal 2
						</v-card-title>
						<v-card-subtitle>
							Ibu ingin mengambil uang tabungan sejumlah Rp. 1.575.250,- yang dimilikinya di
							sebuah bank. Misalkan pada saat itu uang pecahan yang berlaku adalah Rp.
							100.000,-; Rp. 50.000,-; Rp. 20.000,-; Rp. 5.000,-; Rp. 100,- dan Rp. 50. Dengan
							menggunakan script PHP, tentukan banyaknya masing-masing uang pecahan yang
							diperoleh ibu tadi.
							
						</v-card-subtitle>
						<v-card-text>
							<h4>Jawaban</h4>
							<v-row v-for="(item, index) in result" :key="index"> 
								<v-col>
									Pecahan : {{ convertToRupiah(item.money) }}
								</v-col>
								<v-col>
									Jumlah : {{ item.count }}
								</v-col>
							</v-row>
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
				result: []
			}
		},
		created() {
			this.getResult()
		},
		methods: {
			async getResult() {
				return axios.get('/api/soal/2')
				.then( res => {
					this.result = res.data.records
				})
			},
			convertToRupiah(number) {
				let formatter = new Intl.NumberFormat('en-US');

				
				return 'Rp. ' + formatter.format(number);
			}
		}
	}
</script>