<template>
  <v-app class="p-2">
    <v-container>
    <v-form
      ref="form"
      v-model="valid"
      lazy-validation
    >

      <v-text-field
        v-model="email"
        :rules="emailRules"
        label="E-mail"
        autocomplete="username"
        required
      ></v-text-field>

      <v-text-field
      v-model="password"
      :append="false"
      :type="showPassword ? 'text' : 'password'"
      :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
      :rules="passwordRules"
      @click:append="showPassword = !showPassword"
      label="Password"
      autocomplete="current-password"
      required
    ></v-text-field>
     
      <v-btn
        :disabled="!valid"
        color="success"
        class="mr-4"
        @click="login"
      >
        Login
      </v-btn>

     
    </v-form>
    </v-container>
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
</template>

<script>
  export default {
    data: () => ({
      valid: true,
      email: '',
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],
      password: '',
      passwordRules: [
        v => !!v || 'Password is required',
      ],
      showPassword: false,
      notification: {
          active: false,
          message: '',
          color: "success"
      }
     
    }),

    methods: {
      login () {
        let result = this.$refs.form.validate()
        if (result) {
          axios.post('/api/login', {email: this.email, password: this.password})
          .then( res=> {
            this.setToken()
          })
          .catch( err => {
            console.log(err.response)
            if (err.response.status === 422) {
              this.showNotification("Error Login: Wrong Password", "error")
            } else if(err.response.status === 404) {
              this.showNotification("Error Login: Email Not Found", "error")
            }
            console.log(err)
          })
        }
      },
      setToken () {
        axios.get('/api/check').then( res=> {
          let token = res.data.response.records;
          if (token != null && typeof token == 'string') {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            this.$router.push('/')
          } else {
              this.showNotification("Error Login", "error")
          }
        });
        return;
      },
      showNotification(message, color) {
        this.notification.message = message
        this.notification.color = color
        this.notification.active = true 
      }

    },
  }
</script>