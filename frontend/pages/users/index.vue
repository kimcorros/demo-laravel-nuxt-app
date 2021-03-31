<template>
  <v-row>
    <v-col cols="11" class="mx-auto pb-0">
      <v-row class="pl-md-10 pt-8">
        <v-col cols="12">
          <page-title title="Users" class="flex-sm-column flex-lg-row">
            <v-btn
              color="primary"
              depressed
              large
              @click="showCreateUserModal()"
            >
              <v-icon class="mr-2">mdi-plus-thick</v-icon>
              Add New User
            </v-btn>
          </page-title>
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="11" class="mx-auto mb-12 py-0">
      <v-row class="pl-md-10 pt-8">
        <v-col cols="12">
          <v-data-table
            :headers="headers"
            :items="users"
            hide-default-footer
            sort-by="first_name"
            :sort-desc="false"
            item-key="id"
            class="elevation-0 grey--text"
          >
            <template #[`item.id`]="{ item }">
              <span class="font-weight-bold primary--text">{{ item.id }}</span>
            </template>
            <template #[`item.avatar`]="{ item }">
              <nuxt-link :to="`/users/${item.id}`" class="text-decoration-none">
                <user-avatar
                  class="py-2"
                  :image-url="item.avatar"
                  :name="`${item.first_name} ${item.last_name}`"
                  :size="35"
                  font-size="14"
                  color="grey darken-3"
                />
              </nuxt-link>
            </template>
            <template #[`item.name`]="{ item }">
              <nuxt-link :to="`/users/${item.id}`" class="text-decoration-none">
                <span class="font-weight-bold grey--text text--darken-2">
                  {{ item.first_name }} {{ item.last_name }}
                </span>
              </nuxt-link>
            </template>
            <template #[`item.email`]="{ item }">
              <span class="font-weight-semibold">
                {{ item.email }}
              </span>
            </template>
            <template #[`item.company`]="{ item }">
              <span class="font-weight-semibold">
                {{ item.company ? item.company.name : 'Unemployed' }}
              </span>
            </template>
            <template #[`item.created_at`]="{ item }">
              <span>
                {{ item.created_at | date_format }}
              </span>
            </template>
            <template #[`item.updated_at`]="{ item }">
              <span>
                {{ item.updated_at | date_format }}
              </span>
            </template>
            <template #[`item.actions`]="{ item }">
              <span class="d-flex justify-content-center">
                <v-btn icon color="primary" @click="showEditUserModal(item)">
                  <v-icon>mdi-clipboard-edit-outline</v-icon>
                </v-btn>
                <v-btn icon color="red" @click="showDeleteUserModal(item)">
                  <v-icon>mdi-trash-can-outline</v-icon>
                </v-btn>
              </span>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
      <v-pagination
        v-model="pagination.current"
        class="mt-10"
        color="primary"
        :length="pagination.total"
        :total-visible="7"
        @input="getUsers"
      />

      <!-- Delete User Dialog -->
      <v-dialog v-model="isDeleteUserModalShown" max-width="400">
        <v-card class="pb-5 pt-0">
          <v-card-title class="py-0 px-1">
            <v-toolbar flat class="pa-0 transparent body-1 font-weight-bold">
              <span>Delete user?</span>
              <v-spacer />
              <v-btn icon @click="isDeleteUserModalShown = false">
                <v-icon>close</v-icon>
              </v-btn>
            </v-toolbar>
          </v-card-title>

          <v-card-text class="px-5">
            Are you sure you want to delete
            {{ selectedUser ? selectedUser.first_name : 'this user' }}?
          </v-card-text>

          <v-card-actions>
            <v-spacer />
            <div class="d-flex flex-sm-row flex-column justify-end pt-4 px-5">
              <v-btn
                color="grey darken-3"
                depressed
                large
                @click="isDeleteUserModalShown = false"
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
                :loading="isDeleting"
                large
                @click="deleteUser(selectedUser.id)"
              >
                <v-icon left size="18"> mdi-check-bold </v-icon>
                <span class="subtitle-2 font-weight-bold text-capitalize">
                  Delete
                </span>
              </v-btn>
            </div>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <!-- Create New User Dialog -->
      <v-dialog
        v-model="isCreateUserModalShown"
        persistent
        scrollable
        width="700"
      >
        <v-card class="px-6 pb-8">
          <v-card-title class="pb-0 px-2">
            <v-toolbar flat class="transparent">
              <h4>Add New User</h4>
              <v-spacer />
              <v-btn icon @click="isCreateUserModalShown = false">
                <v-icon>close</v-icon>
              </v-btn>
            </v-toolbar>
          </v-card-title>
          <v-card-text>
            <ValidationObserver ref="observer" v-slot="{}">
              <v-divider class="mb-8" />
              <v-row>
                <v-col class="py-0">
                  <v-label for="userFirstName">
                    <span class="primary--text">*</span> First Name
                  </v-label>
                  <ValidationProvider
                    v-slot="{ errors }"
                    name="first name"
                    rules="required"
                  >
                    <v-text-field
                      id="userFirstName"
                      v-model="newUser.first_name"
                      :error-messages="errors"
                      outlined
                      dense
                    />
                  </ValidationProvider>
                </v-col>
                <v-col class="py-0">
                  <v-label for="userLastName">
                    <span class="primary--text">*</span> Last Name
                  </v-label>
                  <ValidationProvider
                    v-slot="{ errors }"
                    name="last name"
                    rules="required"
                  >
                    <v-text-field
                      id="userLastName"
                      v-model="newUser.last_name"
                      :error-messages="errors"
                      outlined
                      dense
                    />
                  </ValidationProvider>
                </v-col>
              </v-row>

              <v-row>
                <v-col class="py-0">
                  <v-label for="userEmail">
                    <span class="primary--text">*</span> Email Address
                  </v-label>
                  <ValidationProvider
                    v-slot="{ errors }"
                    name="email"
                    rules="required|email"
                  >
                    <v-text-field
                      id="userEmail"
                      v-model="newUser.email"
                      outlined
                      :error-messages="errors"
                      dense
                    />
                  </ValidationProvider>
                </v-col>
                <v-col class="py-0">
                  <v-label for="userMobileNumber"> Phone Number </v-label>
                  <v-text-field
                    id="userMobileNumber"
                    v-model="newUser.phone"
                    outlined
                    dense
                  />
                </v-col>
              </v-row>
              <v-row>
                <v-col class="py-0">
                  <v-label for="userCompany"> Company </v-label>
                  <v-select
                    id="userCompany"
                    v-model="newUser.company_id"
                    :items="companies"
                    item-text="name"
                    item-value="id"
                    outlined
                    dense
                  />
                </v-col>
                <v-col class="py-0">
                  <v-label for="userRole"> Role </v-label>
                  <v-select
                    id="userRole"
                    v-model="newUser.role"
                    :items="['admin', 'employee']"
                    outlined
                    dense
                  />
                </v-col>
              </v-row>
            </ValidationObserver>
            <v-row>
              <v-col class="py-0">
                <v-label for="password"> Password </v-label>
                <v-text-field
                  id="password"
                  v-model="newUser.password"
                  outlined
                  dense
                />
              </v-col>
            </v-row>
          </v-card-text>

          <v-card-actions>
            <v-spacer />
            <div class="d-flex flex-sm-row flex-column justify-end pt-4 px-5">
              <v-btn
                color="grey darken-3"
                depressed
                large
                @click="isCreateUserModalShown = false"
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
                :loading="isSaving"
                large
                @click="addUser()"
              >
                <v-icon left size="18"> mdi-check-bold </v-icon>
                <span class="subtitle-2 font-weight-bold text-capitalize">
                  Add user
                </span>
              </v-btn>
            </div>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <!-- Update User Dialog -->
      <v-dialog
        v-model="isEditUserModalShown"
        persistent
        scrollable
        width="700"
      >
        <v-card class="px-6 pb-8">
          <v-card-title class="pb-0 px-2">
            <v-toolbar flat class="transparent">
              <h4>Edit User</h4>
              <v-spacer />
              <v-btn icon @click="isEditUserModalShown = false">
                <v-icon>close</v-icon>
              </v-btn>
            </v-toolbar>
          </v-card-title>
          <v-card-text>
            <ValidationObserver ref="observer" v-slot="{}">
              <v-divider class="mb-8" />
              <v-row>
                <v-col class="py-0">
                  <v-label for="userFirstName">
                    <span class="primary--text">*</span> First Name
                  </v-label>
                  <ValidationProvider
                    v-slot="{ errors }"
                    name="first name"
                    rules="required"
                  >
                    <v-text-field
                      id="userFirstName"
                      v-model="selectedUser.first_name"
                      :error-messages="errors"
                      outlined
                      dense
                    />
                  </ValidationProvider>
                </v-col>
                <v-col class="py-0">
                  <v-label for="userLastName">
                    <span class="primary--text">*</span> Last Name
                  </v-label>
                  <ValidationProvider
                    v-slot="{ errors }"
                    name="last name"
                    rules="required"
                  >
                    <v-text-field
                      id="userLastName"
                      v-model="selectedUser.last_name"
                      :error-messages="errors"
                      outlined
                      dense
                    />
                  </ValidationProvider>
                </v-col>
              </v-row>

              <v-row>
                <v-col class="py-0">
                  <v-label for="userEmail">
                    <span class="primary--text">*</span> Email Address
                  </v-label>
                  <ValidationProvider
                    v-slot="{ errors }"
                    name="email"
                    rules="required|email"
                  >
                    <v-text-field
                      id="userEmail"
                      v-model="selectedUser.email"
                      outlined
                      :error-messages="errors"
                      dense
                    />
                  </ValidationProvider>
                </v-col>
                <v-col class="py-0">
                  <v-label for="userMobileNumber"> Phone Number </v-label>
                  <v-text-field
                    id="userMobileNumber"
                    v-model="selectedUser.phone"
                    outlined
                    dense
                  />
                </v-col>
              </v-row>
              <v-row>
                <v-col class="py-0">
                  <v-label for="userCompany"> Company </v-label>
                  <v-select
                    id="userCompany"
                    v-model="selectedUser.company_id"
                    :items="companies"
                    item-text="name"
                    item-value="id"
                    outlined
                    dense
                  />
                </v-col>
                <v-col class="py-0">
                  <v-label for="userRole"> Role </v-label>
                  <v-select
                    id="userRole"
                    v-model="selectedUser.role"
                    :items="['admin', 'employee']"
                    outlined
                    dense
                  />
                </v-col>
              </v-row>
            </ValidationObserver>
          </v-card-text>

          <v-card-actions>
            <v-spacer />
            <div class="d-flex flex-sm-row flex-column justify-end pt-4 px-5">
              <v-btn
                color="grey darken-3"
                depressed
                large
                @click="isEditUserModalShown = false"
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
                :loading="isSaving"
                large
                @click="updateUser()"
              >
                <v-icon left size="18"> mdi-check-bold </v-icon>
                <span class="subtitle-2 font-weight-bold text-capitalize">
                  Update User
                </span>
              </v-btn>
            </div>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-col>
  </v-row>
</template>

<script>
import { ValidationObserver, ValidationProvider, extend } from 'vee-validate'
import { required, email } from 'vee-validate/dist/rules'
import PageTitle from '~/components/Common/PageTitle'
import User from '~/models/User'
import Company from '~/models/Company'
import UserAvatar from '~/components/Common/UserAvatar'

extend('required', {
  ...required,
  message: 'The {_field_} is required',
})

extend('email', {
  ...email,
  message: 'Please enter a valid email address',
})

export default {
  components: {
    PageTitle,
    UserAvatar,

    ValidationObserver,
    ValidationProvider,
  },
  data() {
    return {
      headers: [
        { text: 'User ID', sortable: false, value: 'id', align: 'center' },
        { text: 'Avatar', sortable: false, value: 'avatar' },
        { text: 'Name', sortable: false, value: 'name' },
        { text: 'Company', sortable: false, value: 'company' },
        { text: 'Email', sortable: false, value: 'email' },
        {
          text: 'Created',
          sortable: false,
          value: 'created_at',
          align: 'center',
        },
        {
          text: 'Updated',
          sortable: false,
          value: 'updated_at',
          align: 'center',
        },
        { text: 'Actions', value: 'actions' },
      ],
      pagination: {
        current: 0,
        total: 0,
      },

      isDeleteUserModalShown: false,
      selectedUser: new User(),
      isDeleting: false,

      isCreateUserModalShown: false,
      newUser: new User(),
      isSaving: false,

      isEditUserModalShown: false,
    }
  },
  computed: {
    users() {
      return User.query().withAll().get()
    },
    companies() {
      return Company.query().orderBy('name').get()
    },
  },
  async mounted() {
    await this.getUsers()
  },
  methods: {
    async getUsers() {
      await this.$axios
        .$get(`/users?page=${this.pagination.current}`)
        .then((response) => {
          User.create({ data: response.data })
          this.pagination.current = response.current_page
          this.pagination.total = response.last_page
        })
    },
    async getCompanies() {
      await this.$axios.$get('/companies').then((response) => {
        Company.create({ data: response })
      })
    },
    showDeleteUserModal(user) {
      this.selectedUser = user
      this.isDeleteUserModalShown = true
    },
    async deleteUser(id) {
      this.isDeleting = true
      await this.$axios
        .$delete(`/users/${id}`)
        .then(() => {
          this.$toast.success('User successfully deleted')
          User.delete(id)
        })
        .catch(() => {
          this.$toast.error('There was an error deleting the user')
        })
        .finally(() => {
          this.isDeleting = false
          this.isDeleteUserModalShown = false
          this.selectedUser = null
          this.getUsers()
        })
    },
    async showCreateUserModal() {
      await this.getCompanies()
      this.isCreateUserModalShown = true
    },
    async addUser() {
      this.isSaving = true

      await this.$axios
        .$post('/users', this.newUser)
        .then((response) => {
          this.$toast.success('User successfully created')
          User.insert({ data: response })
        })
        .catch(() => {
          this.$toast.error('There was an error creating the user')
        })
        .finally(() => {
          this.isSaving = false
          this.isCreateUserModalShown = false
          this.newUser = new User()
          this.getUsers()
        })
    },
    async showEditUserModal(user) {
      await this.getCompanies()
      this.selectedUser = user
      this.selectedUser.role = user.roles[0].name
      this.isEditUserModalShown = true
    },
    async updateUser(id) {
      this.isSaving = true

      await this.$axios
        .$put(`/users/${this.selectedUser.id}`, this.selectedUser)
        .then((response) => {
          this.$toast.success('User successfully updated')
          User.insert({ data: response })
        })
        .catch(() => {
          this.$toast.error('There was an error updating the user')
        })
        .finally(() => {
          this.isSaving = false
          this.isEditUserModalShown = false
          this.getUsers()
          this.selectedUser = new User()
        })
    },
  },
}
</script>
