<template>
  <v-row>
    <v-col cols="11" class="mx-auto pb-0">
      <v-row class="pl-md-10 pt-8">
        <v-col cols="12">
          <page-title title="Dashboard" class="flex-sm-column flex-lg-row" />
        </v-col>
        <v-col cols="12" md="6">
          <v-card
            class="py-12 white--text d-flex flex-column align-center rounded-xl elevation-0"
            color="primary"
          >
            <v-icon x-large color="white" class="">mdi-account-multiple</v-icon>
            <h4 class="mb-6">Total Users</h4>
            <h1 class="display-4 font-weight-bold">
              {{ totalUsersCount }}
            </h1>
          </v-card>
        </v-col>
        <v-col cols="12" md="6">
          <v-card
            class="py-12 white--text d-flex flex-column align-center rounded-xl elevation-0"
            color="green"
          >
            <v-icon x-large color="white" class="">
              mdi-office-building-marker
            </v-icon>
            <h4 class="mb-6">Total Companies</h4>
            <h1 class="display-4 font-weight-bold">
              {{ totalCompaniesCount }}
            </h1>
          </v-card>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import PageTitle from '~/components/Common/PageTitle'

export default {
  auth: 'auth',
  components: {
    PageTitle,
  },
  data() {
    return {
      totalUsersCount: 0,
      totalCompaniesCount: 0,
    }
  },
  mounted() {
    this.getUsersCount()
    this.getCompaniesCount()
  },
  methods: {
    async getUsersCount() {
      await this.$axios.$get(`/users?page=0`).then((response) => {
        this.totalUsersCount = response.total
      })
    },
    async getCompaniesCount() {
      await this.$axios.$get(`/companies?page=0`).then((response) => {
        this.totalCompaniesCount = response.total
      })
    },
  },
}
</script>
