<template>
  <v-avatar ref="user_avatar" :color="color" :size="size">
    <v-img v-if="imageUrl" contain :src="imageUrl" />
    <span
      v-else
      class="white--text font-weight-bold"
      :style="{ fontSize: `${fontSize}px` }"
      >{{ name | initials }}</span
    >
  </v-avatar>
</template>

<script>
export default {
  props: {
    imageUrl: {
      type: String,
      default: '',
    },
    name: {
      type: String,
      default: '',
    },
    size: {
      type: Number,
      required: true,
    },
    fontSize: {
      type: [String, Number],
      required: false,
      default: 16,
    },
    color: {
      type: String,
      required: false,
      default: () => {
        const colors = ['primary', 'green', 'grey', 'accent']

        return colors[Math.floor(Math.random() * colors.length)]
      },
    },
  },
}
</script>
