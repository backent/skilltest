<template>
	<v-app>
        <v-data-table
      :headers="headers"
      :items="items"
      class="elevation-1"
      :search="search"
      :loading="loading"
    >
      <template v-slot:top>
        <v-toolbar flat color="white">
          <v-toolbar-title>Produk</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-divider
            class="mx-4"
            inset
            vertical
          ></v-divider>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
              class="ml-4"
            ></v-text-field>

        </v-toolbar>
        <v-divider></v-divider>
	        <v-row>
	        	<v-col cols="3" >
	        		<v-btn class="ml-4" @click="addProduct">
					    Tambah Produk
						<v-icon color="primary" right>mdi-plus</v-icon>
					</v-btn>		
	        	</v-col>
	        </v-row>

      </template>
      <template v-slot:item.nomor="{ item }">
      	{{ items.indexOf(item) + 1 }}
      </template>
      <template v-slot:item.harga="{ item }">
      	{{ convertToRupiah(item.harga) }}
      </template>
      <template v-slot:item.actions="{ item }">
	        <v-icon
	          small
	          @click="openDetail(item)"
	          color="primary"
	        >
	          mdi-eye
	        </v-icon>
	        <v-icon
	          small
	          @click="openEdit(item)"
	          color="warning"
	        >
	          mdi-pencil
	        </v-icon>
	        <v-icon
	          small
	          @click="openDialogDelete(item)"
	          color="red"
	        >
	          mdi-delete
	        </v-icon>
	  </template>
    </v-data-table>
    <v-dialog persistent v-model="dialog">
    	<v-card>
    		<v-card-title>
    			{{ titleForm }}
    		</v-card-title>
    		<v-card-text>
    			<v-container>
    				<v-row>
    					<v-col>
    						<v-form
						      ref="form"
						      v-model="valid"
						      lazy-validation
						    >
	    						<v-text-field
						        v-model="data.nama_produk"
						        label="Nama Produk"
						        :rules="namaProductRules"
						        required
						      	></v-text-field>

						      	<v-text-field
						        v-model="data.harga"
						        label="Harga"
						        :rules="hargaRules"
						        required
						      	>
						      	</v-text-field>
						      	{{ convertedHarga }}
						      	<v-text-field
						        v-model="data.stock"
						        :rules="stockRules"
						        label="Stock"
						        required
						      	></v-text-field>
								<v-file-input
						          :rules="imageRules"
						          accept="image/png, image/jpeg, image/jpg"
						          placeholder="Pick an Image"
						          prepend-icon="mdi-camera"
						          show-size
						          label="Image"
						          v-model="data.imageData"
						          ref="fileinput"
						        ></v-file-input>

						      	<v-btn v-if="actionForm == 'create'"
						      	:disabled="!valid"
						        @click="store"
						        color='success'
						        >
						        	Buat Produk
						        </v-btn>
						        <v-btn v-if="actionForm == 'edit'"
						      	:disabled="!valid"
						        @click="editItem"
						        color='success'
						        >
						        	Ubah Produk
						        </v-btn>
						        <v-btn
						        color="warning"
						        @click="closeDialog()"
						        >
						        	Kembali
						        </v-btn>
						    </v-form>
    					</v-col>
    				</v-row>
    			</v-container>
    		</v-card-text>
    	</v-card>
    </v-dialog>
    <v-dialog persistent v-model="dialogDisplay">
    	<v-card>
    		<v-card-title>
    			{{data.nama_produk}}
    		</v-card-title>
    		<v-card-text>
    			<v-container>
    				<v-row>
    					<v-col cols="12" md="3">
    						Nama Produk
    					</v-col>
    					<v-col cols="12" md="9">
    						: {{data.nama_produk}}
    					</v-col>
    					<v-col cols="12" md="3">
    						Harga
    					</v-col>
    					<v-col cols="12" md="9">
    						: {{data.harga}}
    					</v-col>
    					<v-col cols="12" md="3">
    						Stock
    					</v-col>
    					<v-col cols="12" md="9">
    						: {{data.stock}}
    					</v-col>
    					<v-col cols="12" md="4" sm="8">
    						<v-img
    						:src="data.image"
    						>
    						</v-img>
    					</v-col>

    				</v-row>
    				<v-btn
    				color="warning"
    				@click="closeDetail()"
    				>
    					Kembali
    				</v-btn>
    			</v-container>
    		</v-card-text>
    	</v-card>
    </v-dialog>
    <v-dialog persistent v-model="dialogDelete">
    	<v-card>
    		<v-card-title>
    			Apakah anda yakin ingin menghapus produk tersebut?
    		</v-card-title>
    		<v-card-text>
    			<v-btn
    			color="red"
    			@click="deleteItem()"
    			>
    				Oke
    			</v-btn>
    			<v-btn
    			color="warning"
    			@click="closeDialogDelete()"
    			>
    				Kembali
    			</v-btn>
    		</v-card-text>
    	</v-card>
    </v-dialog>
    <v-snackbar
        v-model="notification.active"
        :color="notification.color"
        :timeout="2000"
        :right="true"
        :top="true"
      >
        {{ notification.message }}
        <v-btn
          text
          @click="notification.active = false"
        >
          Close
        </v-btn>
      </v-snackbar>


  </v-app>
	</v-app>
</template>


<script type="text/javascript">
	'use strict'
	export default {
		data() {
			return {
				valid: true,
				img: null,
				imageData:null,
		      	imageRules: [
		        	v => !!v || 'Harap unggah gambar',
		        	v => !v || v.size < 5000000 || 'Ukuran gambar harus kurang dari 5 MB',
		      	],
		      	namaProductRules: [
		      		v => !!v || "Harap isi nama produk"
		      	],
		      	hargaRules: [
		      		v => !!v || "Harap isi harga",
		      		v => /^[0-9]*$/.test(v) || 'Harga harus berupa angka',
		      	],
		      	stockRules: [
		      		v => !!v || "Harap isi stock",
		      		v => /^[0-9]*$/.test(v) || 'Stock harus berupa angka',
		      	],
		      	headers: [
			        {
			          text: 'No.',
			          align: 'start',
			          sortable: true,
			          value: 'nomor',
			        },
			        { text: 'Name', value: 'nama_produk' },
			        { text: 'Stock', value: 'stock' },
			        { text: 'Harga', value: 'harga' },
			        { text: 'Aksi', value: 'actions' },
			    ],
			    data: {
			    	harga: 0 ,
			    	nama_produk: '',
			    	stock: 0,
			    	imageData: null
			    },
			    titleForm: '',
			    actionForm: '',
			    items: [],
			    search: '',
			    loading: false,
			    dialog: false,
			    dialogDelete: false,
			    toBeDeletedID: 0,
			    toBeDeletedIndex: -1,
			    editedIndex: -1,
			    dialogDisplay: false,
			    notification: {
			        active: false,
			        message: '',
			        color: "success"
			    }
			}
		},
		computed: {
			convertedHarga() {
				return this.convertToRupiah(this.data.harga)
			}
		},
		created() {
			this.getProducts();
		},
		methods: {
			fileChange(event) {
		        let imgvalid = this.$refs.fileinput.validate()
		        if (!imgvalid) {
		          return;
		        }
		        if (typeof event != 'undefined') {
		          const fr = new FileReader()
		          fr.readAsDataURL(event)
		          fr.addEventListener('load', () => {
		            this.img = (fr.result)
		          })
		        } else {
		          this.img = null
		        }
		    },
		    async store() {
		    	let datavalidation = this.$refs.form.validate()
		    	let imagevalidation = this.data.imageData !== null;
		    	if (!datavalidation || !imagevalidation) {
		    		return;
		    	}
		    	var formData = new FormData();
				var imagefile = this.data.imageData;
				formData.append("image", imagefile);
				formData.append("nama_produk", this.data.nama_produk);
				formData.append("harga", this.data.harga);
				formData.append("stock", this.data.stock);
		    	return await axios.post('/api/product', formData, {
				    headers: {
				      'Content-Type': 'multipart/form-data'
				    }
				})
		    	.then( res => {
		    		this.items.push(res.data.records)
		    		this.showNotification('Tambah produk berhasil', 'success')
		    		this.closeDialog()
		    	})
		    	.catch( err => {
		    		if (typeof err.response !== 'undefined' && err.response.status == 409) {
		    			this.showNotification('Nama Produk sudah terpakai', 'red')
		    		} else {

		    			this.showNotification('Tambah produk gagal', 'red')
		    		}
		    		console.log(err)
		    	})
		    },
		    async getProducts() {
		    	return await axios.get('/api/product')
		    	.then( res => {
		    		this.items = res.data.records
		    	})
		    	.catch( err => {
		    		console.log(err.response)
		    	})
		    },
		    openDetail(item) {
		    	this.data = item;
		    	this.dialogDisplay = true;
		    },
		    closeDetail() {
		    	this.data = Object.assign({}, {
					harga: 0 ,
			    	nama_produk: '',
			    	stock: 0,
			    	imageData: null
				})
				this.dialogDisplay = false
		    },
		    openEdit(item) {
		    	this.titleForm = "Ubah Produk"
		    	this.actionForm = 'edit'
		    	this.editedIndex = this.items.indexOf(item)
		    	this.data = Object.assign({}, item)
		    	this.data.harga = parseInt(this.data.harga)
		    	this.dialog = true;
		    },
		    async editItem() {
		    	let datavalidation = this.$refs.form.validate()
		    	let imagevalidation = this.data.imageData !== null;
		    	if (!datavalidation || !imagevalidation) {
		    		return;
		    	}
		    	var formData = new FormData();
				var imagefile = this.data.imageData;
				formData.append("image", imagefile);
				formData.append("nama_produk", this.data.nama_produk);
				formData.append("harga", this.data.harga);
				formData.append("stock", this.data.stock);
				formData.append("_method", "PUT");
		    	return await axios.post('/api/product/' + this.data.id, formData, {
				    headers: {
				      'Content-Type': 'multipart/form-data'
				    }
				})
		    	.then( res => {
		    		
		    		Object.assign(this.items[this.editedIndex], res.data.records)
		    		this.showNotification('Ubah produk berhasil', 'success')
		    		this.closeDialog()
		    	})
		    	.catch( err => {
		    		if (typeof err.response !== 'undefined' && err.response.status == 409) {
		    			this.showNotification('Nama Produk sudah terpakai', 'red')
		    		} else {

		    			this.showNotification('Ubah produk gagal', 'red')
		    		}
		    	})
		    },
		    async deleteItem() {
		    	return axios.delete('/api/product/' + this.toBeDeletedID)
		    	.then( res => {
		    		this.items.splice(this.toBeDeletedIndex, 1)
		    		this.closeDialogDelete()
		    	})
		    	.catch( err => {
		    		console.log(err)
		    	})
		    },
		    addProduct() {
		    	this.titleForm = "Tambah Produk"
		    	this.actionForm = 'create'
		    	this.dialog = true;
		    },
		    convertToRupiah(number) {
				let formatter = new Intl.NumberFormat('en-US');

				
				return 'Rp. ' + formatter.format(number);
			},
			closeDialog() {
				this.data = Object.assign({}, {
					harga: 0 ,
			    	nama_produk: '',
			    	stock: 0,
			    	imageData: null
				})
				this.titleForm = ''
				this.editedIndex = -1
				this.dialog = false
			},
			openDialogDelete(item) {
				this.dialogDelete = true
				this.toBeDeletedID = item.id
				this.toBeDeletedIndex = this.items.indexOf(item)
			},
			closeDialogDelete(item) {
				this.dialogDelete = false
				this.toBeDeletedID = 0
				this.toBeDeletedIndex = -1
			},
			showNotification(message, color) {
		      this.notification.message = message
		      this.notification.color = color
		      this.notification.active = true 
		    }
		}
	}
</script>