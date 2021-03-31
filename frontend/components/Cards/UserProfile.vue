<template>
  <v-card class="py-12 px-sm-12 px-5 elevation-0">
    <v-row>
      <v-col cols="12" md="4" class="py-12 d-flex justify-center">
        <user-avatar
          :image-url="user.avatar"
          :name="`${user.first_name} ${user.last_name}`"
          :size="200"
          font-size="80"
          color="grey darken-3"
        />
      </v-col>
      <v-col cols="12" md="8" class="d-flex flex-column py-12">
        <h4>User ID</h4>
        <span>{{ user.id }}</span>
        <h4 class="mt-4">Role</h4>
        <span>{{ user.roles[0].name }}</span>
        <h4 class="mt-4">Name</h4>
        <span>{{ user.first_name }} {{ user.last_name }}</span>
        <h4 class="mt-4">Email</h4>
        <span>{{ user.email }}</span>
        <h4 class="mt-4">Phone Number</h4>
        <span>{{ user.phone }}</span>
        <h4 class="mt-4">Company</h4>
        <span>{{ user.company ? user.company.name : 'Unemployed' }}</span>
        <h4 class="mt-4">Email Verified At</h4>
        <span>{{ user.email_verified_at }}</span>
        <h4 class="mt-4">Created At</h4>
        <span>{{ new Date(user.created_at) }}</span>
        <h4 class="mt-4">Updated At</h4>
        <span>{{ new Date(user.updated_at) }}</span>
      </v-col>
    </v-row>
  </v-card>
</template>

<script>
import UserAvatar from '~/components/Common/UserAvatar'

export default {
  name: 'UserProfile',
  components: {
    UserAvatar,
  },
  props: {
    user: {
      type: Object,
      required: true,
    },
  },
}
</script>
