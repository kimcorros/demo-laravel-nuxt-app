<template>
  <v-form>
    <p v-if="error" class="red--text text-center">{{ error }}</p>
    <ValidationObserver ref="observer">
      <p class="caption black--text mb-1">Email address</p>
      <ValidationProvider
        v-slot="{ errors }"
        rules="required|email"
        name="email address"
      >
        <v-text-field
          v-model.trim="user.email"
          placeholder="Enter email"
          type="text"
          solo
          flat
          dense
          background-color="#E9E9E9"
          hide-details="auto"
          class="caption"
          :error-messages="errors"
          @keypress.enter.native="login()"
        />
      </ValidationProvider>
      <p class="caption black--text mb-1 mt-3">Password</p>
      <ValidationProvider v-slot="{ errors }" rules="required" name="password">
        <v-text-field
          v-model="user.password"
          placeholder="Enter password"
          type="password"
          solo
          flat
          dense
          background-color="#E9E9E9"
          hide-details="auto"
          class="caption"
          :error-messages="errors"
          @keypress.enter.native="login()"
        />
      </ValidationProvider>
      <v-btn
        block
        depressed
        height="40"
        class="caption text-capitalize primary mt-6"
        @click="login()"
      >
        Login
      </v-btn>
    </ValidationObserver>
  </v-form>
</template>

<script>
import { ValidationObserver, ValidationProvider, extend } from 'vee-validate'
import { required, email } from 'vee-validate/dist/rules'

extend('required', {
  ...required,
  message: 'The {_field_} is required',
})

extend('email', {
  ...email,
  message: 'Please enter a valid email address',
})
export default {
  name: 'LoginForm',
  components: {
    ValidationObserver,
    ValidationProvider,
  },
  data() {
    return {
      user: {
        email: '',
        password: '',
      },
      error: '',
    }
  },
  methods: {
    async login() {
      this.isLoggingIn = true

      try {
        if (!(await this.$refs.observer.validate())) return

        await this.$auth
          .loginWith('laravelSanctum', {
            data: this.user,
          })
          .then(() => {
            this.$toast.success('Successfully authenticated')
            this.$router.push(`/`)
          })
          .catch((error) => {
            this.error = error.response.data.error
            this.$toast.error(error.response.data.message)
          })
      } finally {
        this.isLoggingIn = false
      }
    },
  },
}
</script>
