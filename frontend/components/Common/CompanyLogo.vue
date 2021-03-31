<template>
  <div class="d-flex justify-center py-2">
    <v-img
      v-if="logo"
      contain
      :src="logo"
      :max-height="height"
      :max-width="width"
    />
    <v-img
      v-else
      src="/images/logo.png"
      contain
      :max-height="height"
      :max-width="width"
    />
  </div>
</template>

<script>
export default {
  name: 'CompanyLogo',
  props: {
    logo: {
      type: String,
      default: '',
    },
    height: {
      type: [String, Number],
      default: 35,
    },
    width: {
      type: [String, Number],
      default: 35,
    },
  },
}
</script>
