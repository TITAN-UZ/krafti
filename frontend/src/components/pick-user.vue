<template>
  <div>
    <vue-autosuggest
      ref="autosuggest"
      :suggestions="suggestions"
      :get-suggestion-value="getSuggestionValue"
      :input-props="{class: 'form-control'}"
      @input="load"
      @selected="onSelected"
    >
      <template slot-scope="{suggestion}">
        <div class="d-flex align-items-center">
          {{ suggestion.item.fullname }}
          <img v-if="suggestion.item.photo" :src="suggestion.item.photo" class="avatar ml-auto" />
        </div>
      </template>
    </vue-autosuggest>
  </div>
</template>

<script>
export default {
  name: 'PickAuthor',
  props: {
    value: {
      type: Number,
      required: false,
      default: 0,
    },
    roleId: {
      type: Array,
      required: false,
      default() {
        return [3]
      },
    },
  },
  data() {
    return {
      suggestions: [
        {
          data: [],
        },
      ],
    }
  },
  created() {
    if (this.value > 0) {
      this.setValue(this.value)
    } else {
      this.load()
    }
  },
  methods: {
    load(query) {
      this.$axios
        .get('admin/users', {params: {limit: 10, role_id: this.roleId, query: query || ''}})
        .then((res) => {
          this.suggestions[0].data = res.data.rows
        })
        .catch(() => {})
    },
    onSelected(selected) {
      this.$emit('input', selected.item.id)
    },
    getSuggestionValue(suggestion) {
      return suggestion.item.fullname
    },
    setValue(id) {
      this.$axios
        .get('admin/users', {params: {id, role: 'author'}})
        .then((res) => {
          this.suggestions[0].data = [res.data]

          const {listeners, setCurrentIndex, setChangeItem} = this.$refs.autosuggest
          setCurrentIndex(0)
          setChangeItem({item: res.data}, true)
          listeners.selected(true)
        })
        .catch(() => {})
    },
  },
}
</script>

<style lang="scss">
#autosuggest {
  .avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 10px;
  }
}
</style>
