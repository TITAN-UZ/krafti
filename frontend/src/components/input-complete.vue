<template>
  <vue-autosuggest
    :id="'input-ac-' + url.replace('/', '-')"
    ref="autosuggest"
    :suggestions="suggestions"
    :get-suggestion-value="getValue"
    :input-props="{class: 'form-control', required: required, disabled: disabled}"
    @input="load"
    @selected="onSelected"
  >
    <template slot-scope="{suggestion}">
      {{ suggestion.item[fieldTitle] }}
    </template>
    <template v-for="(_, name) in $scopedSlots" :slot="name" slot-scope="slotData">
      <slot :name="name" v-bind="slotData" />
    </template>
  </vue-autosuggest>
</template>

<script>
import Vue from 'vue'
import VueAutosuggest from 'vue-autosuggest'
Vue.use(VueAutosuggest)

export default {
  name: 'InputComplete',
  props: {
    value: {
      type: Number,
      required: false,
      default: null,
    },
    url: {
      type: String,
      required: true,
    },
    fieldId: {
      type: String,
      required: false,
      default: 'id',
    },
    fieldTitle: {
      type: String,
      required: false,
      default: 'title',
    },
    filter: {
      type: Object,
      required: false,
      default() {
        return {}
      },
    },
    required: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    loadEmpty: {
      type: Boolean,
      default: false,
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
      return false
    }
  },
  methods: {
    async setValue(id) {
      const params = {}
      params[this.fieldId] = id
      try {
        const {data} = await this.$axios.get(this.url, {params})
        this.suggestions[0].data = data

        const {listeners, setCurrentIndex, setChangeItem} = this.$refs.autosuggest
        setCurrentIndex(0)
        setChangeItem({item: data}, true)
        listeners.selected(true)
      } catch (e) {
        console.error(e)
      }
    },
    async load(query) {
      if (!query) {
        this.reset()
        if (!this.loadEmpty) {
          return
        }
      }
      const params = {limit: 10, query: query || ''}
      for (const i in this.filter) {
        if (Object.prototype.hasOwnProperty.call(this.filter, i)) {
          params[i] = this.filter[i]
        }
      }
      params.combo = true

      try {
        let {data: items} = await this.$axios.get(this.url, {params})
        if (this.$listeners.onLoad) {
          const res = this.$listeners.onLoad(items)
          if (res && res.rows) {
            items = res
          }
        }
        this.suggestions[0].data = items.rows
      } catch (e) {
        console.error(e)
      }
    },
    onSelected(selected) {
      if (selected && selected.item) {
        this.$emit('input', selected.item[this.fieldId])
      } else {
        this.$emit('input', null)
      }
      if (this.$listeners.onSelected) {
        this.$listeners.onSelected(selected && selected.item ? selected.item : null)
      }
    },
    getValue(suggestion) {
      return suggestion.item[this.fieldTitle]
    },
    reset() {
      this.$emit('input', null)
      this.suggestions = [{data: []}]
      this.$refs.autosuggest.internalValue = null
    },
  },
}
</script>
