<template>
  <v-card class="elevation-0 py-12 px-4 px-md-12">
    <h3 class="grey--text text--darken-2 mb-12">Employees</h3>
    <v-row v-if="users.length > 0">
      <v-col
        v-for="user in users"
        :key="user.id"
        cols="12"
        class="d-flex justify-space-between"
      >
        <span class="d-flex">
          <user-avatar
            class="py-2"
            :image-url="user.avatar"
            :name="`${user.first_name} ${user.last_name}`"
            :size="35"
            font-size="14"
            color="grey darken-3"
          />
          <span class="ml-4">{{ user.first_name }} {{ user.last_name }}</span>
        </span>
        <span>
          <v-btn
            color="primary"
            outlined
            class="text-capitalize subtitle-2"
            @click="showRemoveCompanyUserModal(user)"
          >
            <v-icon small class="mr-3"> mdi-trash-can-outline </v-icon>
            Remove from company
          </v-btn>
        </span>
      </v-col>
    </v-row>
    <v-row v-else>
      <v-col cols="12" class="text-center">
        <v-img src="/images/empty.svg" height="200" contain />
        <h2 class="grey--text">No employees yet...</h2>
      </v-col>
    </v-row>

    <!-- Remove User Dialog -->
    <v-dialog v-model="isRemoveCompanyUserModalShown" max-width="400">
      <v-card class="pb-5 pt-0">
        <v-card-title class="py-0 px-1">
          <v-toolbar flat class="pa-0 transparent body-1 font-weight-bold">
            <span>Remove user from company?</span>
            <v-spacer />
            <v-btn icon @click="isRemoveCompanyUserModalShown = false">
              <v-icon>close</v-icon>
            </v-btn>
          </v-toolbar>
        </v-card-title>

        <v-card-text class="px-5">
          Are you sure you want to remove
          {{ selectedUser ? selectedUser.first_name : 'this user' }} from
          {{ company ? company.name : 'this company' }}?
        </v-card-text>

        <v-card-actions>
          <v-spacer />
          <div class="d-flex flex-sm-row flex-column justify-end pt-4 px-5">
            <v-btn
              color="grey darken-3"
              depressed
              large
              @click="isRemoveCompanyUserModalShown = false"
            >
              <v-icon left size="18" color="white"> mdi-close-thick </v-icon>
              <span
                class="subtitle-2 font-weight-bold white--text text-capitalize pr-4"
              >
                Cancel
              </span>
            </v-btn>
            <v-btn
              color="primary"
              class="white--text"
              :class="$vuetify.breakpoint.smAndUp ? 'ml-2' : 'mt-2'"
              depressed
              :loading="isRemoving"
              large
              @click="removeCompanyUser()"
            >
              <v-icon left size="18"> mdi-check-bold </v-icon>
              <span class="subtitle-2 font-weight-bold text-capitalize">
                Remove User
              </span>
            </v-btn>
          </div>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import UserAvatar from '~/components/Common/UserAvatar'
import User from '~/models/User'
import Company from '~/models/Company'

export default {
  name: 'CompanyUsersList',
  components: {
    UserAvatar,
  },
  props: {
    users: {
      type: Array,
      required: true,
    },
    company: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      isRemoveCompanyUserModalShown: false,
      selectedUser: new User(),
      isRemoving: false,
    }
  },
  methods: {
    async getCompany(id) {
      await this.$axios.$get(`/companies/${id}`).then((response) => {
        Company.insert({ data: response })
      })
    },
    showRemoveCompanyUserModal(user) {
      this.selectedUser = user
      this.selectedUser.role = user.roles.length > 0 ? user.roles[0].name : null
      this.selectedUser.company_id = null
      this.isRemoveCompanyUserModalShown = true
    },
    async removeCompanyUser() {
      await this.$axios
        .$put(`/users/${this.selectedUser.id}`, this.selectedUser)
        .then((response) => {
          this.$toast.success('User successfully removed')
          User.insert({ data: response })
        })
        .catch(() => {
          this.$toast.error('There was an error updating the user')
        })
        .finally(() => {
          this.isSaving = false
          this.isRemoveCompanyUserModalShown = false
          this.getCompany(this.company.id)
          this.selectedUser = new User()
        })
    },
  },
}
</script>

<style lang="scss" scoped></style>
