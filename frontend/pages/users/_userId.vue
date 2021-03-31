<template>
  <v-row>
    <v-col cols="11" class="mx-auto pb-0">
      <v-row class="pl-md-10 pt-8">
        <v-col cols="12">
          <page-title
            :has-arrow="true"
            path="/users"
            :title="`${user.first_name} ${user.last_name}` || ''"
            class="flex-sm-column flex-lg-row"
          />
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="11" class="mx-auto mb-12 py-0">
      <v-row class="pl-md-10 pt-8">
        <v-col cols="12">
          <user-profile :user="user" />
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import PageTitle from '~/components/Common/PageTitle'
import User from '~/models/User'
import UserProfile from '~/components/Cards/UserProfile'

export default {
  components: {
    PageTitle,
    UserProfile,
  },
  computed: {
    user() {
      return User.find(this.$route.params.userId) || {}
    },
  },
  mounted() {
    this.getUser(this.$route.params.userId)
  },
  methods: {
    async getUser(id) {
      await this.$axios.$get(`/users/${id}`).then((response) => {
        User.insertOrUpdate({ data: response })
      })
    },
  },
}
</script>
