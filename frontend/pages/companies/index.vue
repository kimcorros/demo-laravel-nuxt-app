<template>
  <v-row>
    <v-col cols="11" class="mx-auto pb-0">
      <v-row class="pl-md-10 pt-8">
        <v-col cols="12">
          <page-title title="Companies" class="flex-sm-column flex-lg-row">
            <v-btn
              color="primary"
              depressed
              large
              @click="showCreateCompanyModal()"
            >
              <v-icon class="mr-2">mdi-plus-thick</v-icon>
              Add New Company
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
            :items="companies"
            hide-default-footer
            item-key="id"
            class="elevation-0 grey--text"
            sort-by="name"
            :sort-desc="false"
          >
            <template #[`item.id`]="{ item }">
              <span class="font-weight-bold primary--text">{{ item.id }}</span>
            </template>
            <template #[`item.name`]="{ item }">
              <nuxt-link
                class="text-decoration-none"
                :to="`/companies/${item.id}`"
              >
                <span class="font-weight-bold grey--text text--darken-2">
                  {{ item.name }}
                </span>
              </nuxt-link>
            </template>
            <template #[`item.email`]="{ item }">
              <span class="font-weight-semibold">
                {{ item.email }}
              </span>
            </template>
            <template #[`item.logo`]="{ item }">
              <company-logo :logo="`http://localhost/${item.logo}`" />
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
                <v-btn icon color="primary" @click="showEditCompanyModal(item)">
                  <v-icon>mdi-clipboard-edit-outline</v-icon>
                </v-btn>
                <v-btn icon color="red" @click="showDeleteCompanyModal(item)">
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
        @input="getCompanies"
      />

      <!-- Delete Company Dialog -->
      <v-dialog v-model="isDeleteCompanyModalShown" max-width="400">
        <v-card class="pb-5 pt-0">
          <v-card-title class="py-0 px-1">
            <v-toolbar flat class="pa-0 transparent body-1 font-weight-bold">
              <span>Delete company?</span>
              <v-spacer />
              <v-btn icon @click="isDeleteCompanyModalShown = false">
                <v-icon>close</v-icon>
              </v-btn>
            </v-toolbar>
          </v-card-title>

          <v-card-text class="px-5">
            Are you sure you want to delete
            {{ selectedCompany ? selectedCompany.name : 'this company' }}?
          </v-card-text>

          <v-card-actions>
            <v-spacer />
            <div class="d-flex flex-sm-row flex-column justify-end pt-4 px-5">
              <v-btn
                color="grey darken-3"
                depressed
                large
                @click="isDeleteCompanyModalShown = false"
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
                @click="deleteCompany(selectedCompany.id)"
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

      <!-- Create New Company Dialog -->
      <v-dialog
        v-model="isCreateCompanyModalShown"
        persistent
        scrollable
        width="700"
      >
        <v-card class="px-6 pb-8">
          <v-card-title class="pb-0 px-2">
            <v-toolbar flat class="transparent">
              <h4>Add New Company</h4>
              <v-spacer />
              <v-btn icon @click="hideCreateCompanyModal()">
                <v-icon>close</v-icon>
              </v-btn>
            </v-toolbar>
          </v-card-title>
          <ValidationObserver ref="observer" v-slot="{ invalid }">
            <v-card-text>
              <v-divider class="mb-8" />
              <v-row class="mb-4">
                <v-col cols="12" class="d-flex flex-column align-center">
                  <company-logo
                    :logo="newCompany ? newCompany.logo : ''"
                    height="200"
                    width="200"
                  />
                  <v-btn
                    depressed
                    color="primary"
                    class="ma-2 white--text text-none"
                    @click="browseFiles('photoFileInput')"
                  >
                    <v-icon left dark> mdi-camera-outline </v-icon>
                    Upload photo
                  </v-btn>
                  <input
                    ref="photoFileInput"
                    type="file"
                    class="d-none"
                    @change="setSelectedImage()"
                  />
                </v-col>
              </v-row>
              <v-row>
                <v-col class="py-0">
                  <v-label for="userFirstName">
                    <span class="primary--text">*</span> Name
                  </v-label>
                  <ValidationProvider
                    v-slot="{ errors }"
                    name="company name"
                    rules="required"
                  >
                    <v-text-field
                      id="userFirstName"
                      v-model="newCompany.name"
                      :error-messages="errors"
                      outlined
                      dense
                    />
                  </ValidationProvider>
                </v-col>
                <v-col class="py-0">
                  <v-label for="companyEmail">
                    <span class="primary--text">*</span> Company Email Address
                  </v-label>
                  <ValidationProvider
                    v-slot="{ errors }"
                    name="email"
                    rules="required|email"
                  >
                    <v-text-field
                      id="companyEmail"
                      v-model="newCompany.email"
                      outlined
                      :error-messages="errors"
                      dense
                    />
                  </ValidationProvider>
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
                  @click="hideCreateCompanyModal()"
                >
                  <v-icon left size="18" color="white">
                    mdi-close-thick
                  </v-icon>
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
                  :disabled="invalid"
                  @click="addCompany()"
                >
                  <v-icon left size="18"> mdi-check-bold </v-icon>
                  <span class="subtitle-2 font-weight-bold text-none">
                    Add company
                  </span>
                </v-btn>
              </div>
            </v-card-actions>
          </ValidationObserver>
        </v-card>
      </v-dialog>

      <!-- Update Company Dialog -->
      <v-dialog
        v-model="isEditCompanyModalShown"
        persistent
        scrollable
        width="700"
      >
        <v-card class="px-6 pb-8">
          <v-card-title class="pb-0 px-2">
            <v-toolbar flat class="transparent">
              <h4>Edit Company</h4>
              <v-spacer />
              <v-btn icon @click="hideEditCompanyModal()">
                <v-icon>close</v-icon>
              </v-btn>
            </v-toolbar>
          </v-card-title>
          <v-card-text>
            <ValidationObserver ref="observer" v-slot="{}">
              <v-divider class="mb-8" />
              <v-row class="mb-4">
                <v-col cols="12" class="d-flex flex-column align-center">
                  <company-logo
                    :logo="
                      newLogo && logo
                        ? newLogo
                        : selectedCompany.logo
                        ? `http://localhost/${selectedCompany.logo}`
                        : ''
                    "
                    height="200"
                    width="200"
                  />
                  <v-btn
                    depressed
                    color="primary"
                    class="ma-2 white--text text-none"
                    @click="browseFiles('photoFileInput2')"
                  >
                    <v-icon left dark> mdi-camera-outline </v-icon>
                    Upload photo
                  </v-btn>
                  <input
                    ref="photoFileInput2"
                    type="file"
                    class="d-none"
                    @change="setSelectedNewImage()"
                  />
                </v-col>
              </v-row>
              <v-row>
                <v-col class="py-0">
                  <v-label for="userFirstName">
                    <span class="primary--text">*</span> Name
                  </v-label>
                  <ValidationProvider
                    v-slot="{ errors }"
                    name="company name"
                    rules="required"
                  >
                    <v-text-field
                      id="userFirstName"
                      v-model="selectedCompany.name"
                      :error-messages="errors"
                      outlined
                      dense
                    />
                  </ValidationProvider>
                </v-col>
                <v-col class="py-0">
                  <v-label for="companyEmail">
                    <span class="primary--text">*</span> Company Email Address
                  </v-label>
                  <ValidationProvider
                    v-slot="{ errors }"
                    name="email"
                    rules="required|email"
                  >
                    <v-text-field
                      id="companyEmail"
                      v-model="selectedCompany.email"
                      outlined
                      :error-messages="errors"
                      dense
                    />
                  </ValidationProvider>
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
                @click="hideEditCompanyModal()"
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
                @click="updateCompany()"
              >
                <v-icon left size="18"> mdi-check-bold </v-icon>
                <span class="subtitle-2 font-weight-bold text-capitalize">
                  Update Company
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
import Company from '~/models/Company'
import CompanyLogo from '~/components/Common/CompanyLogo'

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
    CompanyLogo,

    ValidationObserver,
    ValidationProvider,
  },
  data() {
    return {
      headers: [
        { text: 'Company ID', sortable: false, value: 'id', align: 'center' },
        { text: 'Logo', sortable: false, value: 'logo', align: 'center' },
        { text: 'Name', sortable: false, value: 'name' },
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

      isDeleteCompanyModalShown: false,
      selectedCompany: new Company(),
      isDeleting: false,

      isCreateCompanyModalShown: false,
      newCompany: {},
      isSaving: false,

      isEditCompanyModalShown: false,

      logo: null,
      newLogo: null,
    }
  },
  computed: {
    companies() {
      return Company.query().withAll().get()
    },
  },
  async mounted() {
    await this.getCompanies()
  },
  methods: {
    async getCompanies() {
      await this.$axios
        .$get(`/companies?page=${this.pagination.current}`)
        .then((response) => {
          Company.create({ data: response.data })
          this.pagination.current = response.current_page
          this.pagination.total = response.last_page
        })
    },
    showDeleteCompanyModal(company) {
      this.selectedCompany = company
      this.isDeleteCompanyModalShown = true
    },
    async deleteCompany(id) {
      this.isDeleting = true

      await this.$axios
        .$delete(`/companies/${id}`)
        .then(() => {
          this.$toast.success('Successfully deleted')
          Company.delete(id)
        })
        .catch(() => {
          this.$toast.error('There was an error deleting the company')
        })
        .finally(() => {
          this.isDeleting = false
          this.isDeleteCompanyModalShown = false
          this.selectedCompany = null
        })
    },
    showCreateCompanyModal() {
      this.isCreateCompanyModalShown = true
    },
    hideCreateCompanyModal() {
      this.isCreateCompanyModalShown = false
      this.newCompany = {}
    },
    async addCompany() {
      this.isSaving = true
      const data = new FormData()
      Object.entries(this.newCompany).forEach(([key, value]) => {
        data.append(key, value)
      })

      if (this.logo) {
        data.append('logo', this.logo)
      }

      await this.$axios
        .$post('/companies', data)
        .then((response) => {
          this.$toast.success('Company successfully created')
          Company.insert({ data: response })
        })
        .catch(() => {
          this.$toast.error('There was an error creating the company')
        })
        .finally(() => {
          this.isSaving = false
          this.isCreateCompanyModalShown = false
          this.newCompany = {}
          this.getCompanies()
          this.logo = null
        })
    },
    showEditCompanyModal(company) {
      this.selectedCompany = company
      this.isEditCompanyModalShown = true
    },
    hideEditCompanyModal() {
      this.isEditCompanyModalShown = false
      this.selectedCompany = new Company()
    },
    async updateCompany(id) {
      this.isSaving = true

      const data = new FormData()
      Object.entries(this.selectedCompany).forEach(([key, value]) => {
        data.append(key, value)
      })

      data.append('_method', 'PUT')

      if (this.logo) {
        if (this.selectedCompany.logo) {
          data.delete('logo')
        }
        data.append('logo', this.logo)
      }

      await this.$axios
        .$post(`/companies/${this.selectedCompany.id}`, data)
        .then((response) => {
          this.$toast.success('Company successfully updated')
          Company.insert({ data: response })
        })
        .catch(() => {
          this.$toast.error('There was an error updating the company')
        })
        .finally(() => {
          this.isSaving = false
          this.getCompanies()
          this.hideEditCompanyModal()
          this.logo = null
          this.newLogo = null
        })
    },

    browseFiles(ref) {
      this.$refs[ref].click()
    },
    setSelectedImage() {
      this.newCompany.logo = URL.createObjectURL(
        this.$refs.photoFileInput.files[0]
      )

      this.logo = this.$refs.photoFileInput.files[0]
    },
    setSelectedNewImage() {
      this.newLogo = URL.createObjectURL(this.$refs.photoFileInput2.files[0])

      this.logo = this.$refs.photoFileInput2.files[0]
    },
  },
}
</script>
