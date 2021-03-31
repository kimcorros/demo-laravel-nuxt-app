<template>
  <span class="d-flex flex-column flex-sm-row mb-4">
    <span class="d-flex">
      <nuxt-link
        v-if="hasArrow"
        :to="path"
        class="d-flex mr-4 align-center text-decoration-none"
      >
        <v-icon class="accent--text" large>mdi-chevron-left</v-icon>
      </nuxt-link>
      <v-row class="align-center" no-gutters>
        <h2 class="font-size-20">
          {{ title }}
        </h2>
      </v-row>
    </span>
    <v-spacer />
    <slot />
  </span>
</template>

<script>
export default {
  name: 'CommonPageTitle',
  props: {
    // This boolean value will set the existence of an arrow that
    // points back to the previous page
    hasArrow: {
      type: Boolean,
      default: false,
      required: false,
    },
    title: {
      type: String,
      required: true,
    },
    // This will be a url that will link back to the previous page
    path: {
      type: String,
      default: '',
      required: false,
    },
  },
}
</script>
