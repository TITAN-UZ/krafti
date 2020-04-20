<template>
  <div class="pick-user">
    <input-complete v-model="myValue" url="admin/users" field-title="fullname" v-bind="$props" :load-empty="true">
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
  computed: {
    myValue: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },
}
</script>
