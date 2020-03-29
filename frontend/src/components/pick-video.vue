<template>
  <div>
    <vue-autosuggest
      ref="autosuggest"
      :suggestions="suggestions"
      :get-suggestion-value="getSuggestionValue"
      :input-props="{class: 'form-control', required: required}"
      @input="load"
      @selected="onSelected"
    >
      <template slot-scope="{suggestion}">
        <img v-if="suggestion.item.preview['100x75']" :src="suggestion.item.preview['100x75']" />
        {{ suggestion.item.title }}
      </template>
    </vue-autosuggest>
    <img v-if="img" :src="img" class="mt-2" />
  </div>
</template>

<script>
export default {
  name: 'PickVideo',
  props: {
    required: Boolean,
    value: {
      type: Number,
      required: false,
      default: 0,
    },
  },
  data() {
    return {
      suggestions: [
        {
          data: [],
        },
      ],
      img: '',
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
        .get('admin/videos', {params: {limit: 10, query: query || ''}})
        .then((res) => {
          this.suggestions[0].data = res.data.rows
        })
        .catch(() => {})
    },
    onSelected(selected) {
      if (selected && selected.item) {
        this.$emit('input', selected.item.id)
        this.img = selected.item.preview && selected.item.preview['295x166'] ? selected.item.preview['295x166'] : ''
      } else {
        this.$emit('input', null)
        this.img = ''
      }
    },
    getSuggestionValue(suggestion) {
      return suggestion.item.title
    },
    setValue(id) {
      this.$axios
        .get('admin/videos', {params: {id}})
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
