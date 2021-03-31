<template>
  <v-row>
    <v-col cols="11" class="mx-auto pb-0">
      <v-row class="pl-md-10 pt-8">
        <v-col cols="12">
          <page-title
            :has-arrow="true"
            path="/companies"
            :title="company.name || ''"
            class="flex-sm-column flex-lg-row"
          />
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="11" class="mx-auto mb-12 py-0">
      <v-row class="pl-md-10 pt-8">
        <v-col cols="12" lg="4">
          <company-profile :company="company" />
        </v-col>
        <v-col cols="12" lg="8">
          <company-users-list :users="companyUsers" :company="company" />
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import PageTitle from '~/components/Common/PageTitle'
import Company from '~/models/Company'
import CompanyProfile from '~/components/Cards/CompanyProfile'
import CompanyUsersList from '~/components/Cards/CompanyUsersList'

export default {
  components: {
    PageTitle,
    CompanyProfile,
    CompanyUsersList,
  },
  computed: {
    company() {
      return (
        Company.query()
          .whereId(parseInt(this.$route.params.companyId))
          .withAll()
          .first() || {}
      )
    },
    companyUsers() {
      return this.company.users ?? []
    },
  },
  mounted() {
    this.getCompany(this.$route.params.companyId)
  },
  methods: {
    async getCompany(id) {
      await this.$axios.$get(`/companies/${id}`).then((response) => {
        Company.insert({ data: response })
      })
    },
  },
}
</script>
