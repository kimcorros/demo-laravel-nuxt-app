<template>
  <v-row align="center" justify="center" class="fill-height">
    <v-col class="px-8">
      <v-card flat max-width="350" class="mx-auto py-4 rounded-lg">
        <logo class="mx-auto my-5" />
        <v-card-title
          class="py-4 font-18 black--text justify-center font-weight-medium"
        >
          Login to Dashboard
        </v-card-title>
        <v-card-text>
          <v-container class="pb-0">
            <login-form />
          </v-container>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import LoginForm from '~/components/Forms/LoginForm'
import Logo from '~/components/Logo'

export default {
  components: {
    LoginForm,
    Logo,
  },
  layout: 'auth',
}
</script>
