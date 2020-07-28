<template>
  <div :class="showName ? 'd-flex align-items-center' : ''">
    <b-avatar v-if="user.photo" :src="$image(user.photo, cmpSize, 'fit')" v-bind="$props" badge-top variant="primary" />
    <b-avatar v-else src="../../assets/images/avatar@2x.png" v-bind="$props" badge-top variant="info" />
    <div v-if="showName" class="d-flex flex-column ml-2">
      <div v-if="truncate > 0" class="text-truncate" :style="`max-width: ${truncate}px`">
        {{ user.fullname | fullname }}
      </div>
      <div v-else>{{ user.fullname | fullname }}</div>
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
      type: [Number, String],
      default: 50,
    },
    truncate: {
      type: [Number, String],
      default: 0,
    },
    add: {
      type: String,
      default: null,
    },
    showName: {
      type: Boolean,
      default: false,
    },
    badge: {
      type: [Boolean, String],
      default: null,
    },
  },
  computed: {
    cmpSize() {
      return `${this.size * 2}x${this.size * 2}`
    },
  },
}
</script>
