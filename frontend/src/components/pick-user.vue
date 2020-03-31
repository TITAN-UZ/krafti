<template>
  <input-complete v-model="myValue" :url="url" field-title="fullname">
    <template slot-scope="{suggestion}">
      <div class="d-flex align-items-center">
        {{ suggestion.item.fullname }}
        <b-avatar v-if="suggestion.item.photo" :src="$image(suggestion.item.photo, '100x100', 'fit')" class="ml-auto" />
      </div>
    </template>
  </input-complete>
</template>

<script>
export default {
  name: 'PickAuthor',
  props: {
    value: {
      type: Number,
      default: 0,
    },
    filter: {
      type: Object,
      required: false,
      default() {
        return {role_id: 3}
      },
    },
  },
  data() {
    return {
      url: 'admin/users',
    }
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
