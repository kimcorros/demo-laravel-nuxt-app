<template>
  <v-card class="py-12 px-sm-12 px-5 elevation-0">
    <v-row>
      <v-col cols="12">
        <company-logo
          :logo="`http://localhost/${company.logo}`"
          height="200"
          width="200"
        />
      </v-col>
      <v-col cols="12" class="d-flex flex-column py-12">
        <h4>Company ID</h4>
        <span>{{ company.id }}</span>
        <h4 class="mt-4">Name</h4>
        <span>{{ company.name }}</span>
        <h4 class="mt-4">Email</h4>
        <span>{{ company.email }}</span>
        <h4 class="mt-4">Created At</h4>
        <span>{{ new Date(company.created_at) }}</span>
        <h4 class="mt-4">Updated At</h4>
        <span>{{ new Date(company.updated_at) }}</span>
      </v-col>
    </v-row>
  </v-card>
</template>

<script>
import CompanyLogo from '~/components/Common/CompanyLogo'

export default {
  name: 'CompanyProfile',
  components: {
    CompanyLogo,
  },
  props: {
    company: {
      type: Object,
      required: true,
    },
  },
}
</script>
