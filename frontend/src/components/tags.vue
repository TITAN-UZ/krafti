<template>
  <vue-tags
    v-model="myValue"
    placeholder=""
    :add-tags-on-comma="true"
    :typeahead="true"
    :only-existing-tags="existingTags.length > 0"
    :existing-tags="existingTags"
    @tag-added="tagAdded"
  ></vue-tags>
</template>

<script>
import VueTags from '@voerro/vue-tagsinput'

export default {
  components: {
    'vue-tags': VueTags,
  },
  props: {
    value: {
      type: Array,
      default: () => {
        return []
      },
    },
    existingTags: {
      type: Array,
      default: () => {
        return []
      },
    },
    /* onlyExistingTags: {
                type: Boolean,
                default: false
            }, */
  },
  data() {
    return {
      loaded: false,
    }
  },
  computed: {
    myValue: {
      get() {
        if (!this.loaded) {
          const value = []
          this.value.forEach((k) => {
            if (/^\d+$/.test(k) && this.existingTags.length) {
              for (const i in this.existingTags) {
                if (Object.prototype.hasOwnProperty.call(this.existingTags, i) && this.existingTags[i].key === k) {
                  value.push(this.existingTags[i])
                }
              }
            } else {
              value.push({key: '', value: k})
            }
          })
          this.loaded = true

          return value
        } else {
          return this.value
        }
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },
  methods: {
    tagAdded(e) {
      this.$emit('tag-added', e)
    },
  },
}
</script>
