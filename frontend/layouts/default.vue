<template>
  <v-app>
    <v-navigation-drawer
      v-model="drawer"
      class="elevation-5"
      width="200"
      fixed
      app
      clipped
    >
      <div class="d-flex justify-center mt-12">
        <user-avatar
          :image-url="user.avatar"
          :name="`${user.first_name} ${user.last_name}`"
          :size="100"
          font-size="40"
          color="grey darken-3"
        />
      </div>
      <div class="text-center mt-3 mb-12">
        <div class="title font-weight-bold">
          {{ user.first_name }} {{ user.last_name }}
        </div>
        <div class="grey--text subtitle-2">
          {{ user.roles[0].name }}
        </div>
      </div>
      <v-list>
        <template v-for="(item, key) in menuItems" class="d-flex">
          <v-list-item :key="key" :to="item.to" exact router>
            <v-list-item-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title
                class="subtitle-2 font-weight-bold"
                v-text="item.title"
              />
            </v-list-item-content>
          </v-list-item>
        </template>
      </v-list>
      <div class="d-flex justify-center mb-3 mt-12 mx-5">
        <v-btn block outlined color="primary" @click="logout()">
          Log out
        </v-btn>
      </div>
    </v-navigation-drawer>
    <v-app-bar class="gradient-bg-1" height="60" flat app clipped-left>
      <v-row class="no-gutters">
        <v-col cols="12" class="d-flex align-center pa-0">
          <v-app-bar-nav-icon
            dark
            class="d-flex d-lg-none"
            @click="drawer = !drawer"
          />
          <nuxt-link to="/" class="d-flex align-center text-decoration-none">
            <v-icon dark large> mdi-hexagon-slice-4 </v-icon>
            <h4 class="font-weight-black d-sm-flex d-none white--text ml-2">
              COMPANY<span class="font-weight-regular">BRAND</span>
            </h4>
          </nuxt-link>
          <v-spacer></v-spacer>
          <span
            class="subtitle-2 d-sm-flex d-none white--text font-weight-semibold mr-2"
          >
            {{ user.email }}
          </span>
          <span class="justify-end">
            <v-menu offset-y>
              <template #activator="{ on }">
                <v-avatar
                  class="cursor-pointer"
                  color="grey darken-3"
                  :size="35"
                  v-on="on"
                >
                  <v-img v-if="user.avatar" contain :src="user.avatar" />
                  <span
                    v-else
                    class="white--text font-weight-bold"
                    :style="{ fontSize: '14px' }"
                  >
                    {{ `${user.first_name} ${user.last_name}` | initials }}
                  </span>
                </v-avatar>
              </template>
              <v-card class="px-2">
                <v-list flat dense>
                  <v-list-item-group color="primary">
                    <v-list-item
                      v-for="(item, key) in dropdownItems"
                      :key="key"
                      class="pr-8"
                      @click="
                        item.action
                          ? handle_function_call(item.action)
                          : $router.push(item.link)
                      "
                    >
                      <v-list-item-icon class="mr-3">
                        <v-icon>{{ item.icon }}</v-icon>
                      </v-list-item-icon>
                      <v-list-item-content>
                        <v-list-item-title class="caption">
                          {{ item.text }}
                        </v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                  </v-list-item-group>
                </v-list>
              </v-card>
            </v-menu>
          </span>
        </v-col>
      </v-row>
    </v-app-bar>
    <v-main style="background-color: #f5f5f5">
      <v-container>
        <nuxt />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import UserAvatar from '~/components/Common/UserAvatar'
import { Roles } from '~/constants'

export default {
  components: {
    UserAvatar,
  },
  data() {
    return {
      drawer: true,
      fixed: false,
      dropdownItems: [
        {
          icon: 'mdi-account',
          text: 'Profile',
          link: '/profile',
        },
        {
          icon: 'mdi-cog',
          text: 'Settings',
          link: '/settings',
        },
        {
          icon: 'mdi-logout',
          text: 'Logout',
          action: 'logout',
        },
      ],
    }
  },
  computed: {
    user() {
      return this.$auth.user
    },
    menuItems() {
      let result
      const role = Roles.ADMIN

      switch (role) {
        case Roles.ADMIN:
          result = [
            {
              icon: 'home',
              title: 'Dashboard',
              to: '/',
            },
            {
              icon: 'mdi-office-building-marker',
              title: 'Companies',
              to: '/companies',
            },
            {
              icon: 'group',
              title: 'Users',
              to: '/users',
            },
          ]
          break
        case Roles.USER:
          result = [
            {
              icon: 'home',
              title: 'Dashboard',
              to: '/',
            },
            {
              icon: 'person',
              title: 'My Profile',
              to: '/profile',
            },
          ]
          break
      }
      return result
    },
  },
  methods: {
    async logout() {
      await this.$auth.logout().then(() => {
        this.$router.push('/auth/login')
      })
    },
    handle_function_call(functionName) {
      this[functionName]()
    },
  },
}
</script>

<style lang="scss" scoped></style>
