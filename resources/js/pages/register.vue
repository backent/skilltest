<template>
  <v-app class="p-2">
    <v-container>
    <v-form
      ref="form"
      v-model="valid"
      lazy-validation
    >
      <v-card-title>
        Registration
      </v-card-title>
      
      <v-text-field
        v-model="email"
        :rules="emailRules"
        label="E-mail"
        required
      ></v-text-field>

      <v-text-field
        v-model="nohp"
        :rules="phoneRules"
        label="Phone Number"
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

    <v-text-field
      v-model="passwordConfirmation"
      :append="false"
      :type="showPasswordConfirmation ? 'text' : 'password'"
      :append-icon="showPasswordConfirmation ? 'mdi-eye' : 'mdi-eye-off'"
      :rules="passwordConfirmationRules"
      @click:append="showPasswordConfirmation = !showPasswordConfirmation"
      label="Password Confirmation"
      required
    ></v-text-field>
     
      <v-btn
        :disabled="!valid"
        color="success"
        class="mr-4"
        @click="register"
      >
        Register
      </v-btn>

      <v-btn
        color="warning"
        class="mr-4"
        to="/login"
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
    data() {
      return {
      valid: true,
      email: '',
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
        v => v.length <= 50 || 'Email Max Character is 50'
      ],
      nohp: '',
      phoneRules: [
        v => !!v || 'Phone is required',
        v => /^[0-9]*$/.test(v) || 'Phone must be number',
      ],
      password: '',
      passwordRules: [
        v => !!v || 'Password is required',
        v => /[@$!%*#?&]/.test(v) || 'Atleast Have 1 Special Character',
        v => /[a-z]/.test(v) || 'Atleast Have 1 Lowercase Character ',
        v => /[A-Z]/.test(v) || 'Atleast Have 1 Uppercase Character',
        v => /[0-9]/.test(v) || 'Atleast Have 1 Number Character',
        v => v.length > 6 || 'Min 6 Character'
      ],
      passwordConfirmation: '',
      passwordConfirmationRules: [
        v => !!v || 'Password Confirmation is required',
        v => v == this.password || 'Must be same with Password'
      ],
      showPassword: false,
      showPasswordConfirmation: false,
      notification: {
          active: false,
          message: '',
          color: "success"
      }
     
    }
    },
    methods: {
      register () {
        let result = this.$refs.form.validate()
        if (result) {
          axios.post('/api/register', {email: this.email, password: this.password, password_confirmation: this.passwordConfirmation, nohp: this.nohp})
          .then( res=> {
            this.$router.push('/login')
          })
          .catch( err => {
            if(typeof err.response.data.message !== 'undefined') {
              this.showNotification(err.response.data.message, 'red')
            } else {
              this.showNotification('Sorry we have some trouble, please try again later')
            }
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