<template>
  <div class="d-flex align-items-center">
    <b-avatar v-if="user.photo" :src="$image(user.photo, computedSize, 'fit')" :size="size" />
    <b-avatar v-else src="~assets/images/avatar@2x.png" :size="size" variant="info" />
    <div class="d-flex flex-column ml-2">
      <div v-if="truncate > 0" class="text-truncate" :style="`max-width: ${truncate}px`">{{ user.fullname }}</div>
      <div v-else>{{ user.fullname }}</div>
      <div v-if="add" class="small">{{ add }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserAvatar',
  props: {
    user: {
      type: Object,
      required: true,
    },
    size: {
      type: Number,
      default: 50,
    },
    truncate: {
      type: Number,
      default: 0,
    },
    add: {
      type: String,
      required: false,
      default: null,
    },
  },
  computed: {
    computedSize() {
      return `${this.size * 2}x${this.size * 2}`
    },
  },
}
</script>
