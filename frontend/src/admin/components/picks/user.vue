<template>
  <div class="pick-user">
    <input-complete url="admin/users" field-title="fullname" v-bind="$props">
      <template slot-scope="{suggestion}">
        <user-avatar :user="suggestion.item" :size="40" :show-name="true" />
      </template>
    </input-complete>
  </div>
</template>

<script>
export default {
  name: 'PickAuthor',
  props: {
    required: {
      type: Boolean,
      required: false,
    },
    value: {
      type: Number,
      required: false,
      default: 0,
    },
    filter: {
      type: Object,
      required: false,
      default() {
        return {}
      },
    },
  },
}
</script>
