<template>
  <div>
    <input-complete ref="input" v-model="myValue" :url="url" field-title="title" :required="required" :load-empty="true" @onSelected="onSelected">
      <template slot-scope="{suggestion}">
        <div class="d-flex align-items-center">
          <b-img v-if="suggestion.item.preview['100x75']" :src="suggestion.item.preview['100x75']" :rounded="true" />
          <div class="ml-2">
            {{ suggestion.item.title }}
            <div class="small text-muted">{{ suggestion.item.duration | duration }}</div>
          </div>
        </div>
      </template>
    </input-complete>
    <img v-if="img" :src="img" class="mt-2" />
  </div>
</template>

<script>
export default {
  name: 'PickVideo',
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
  },
  data() {
    return {
      url: 'admin/videos?sort=id&dir=desc',
      img: null,
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
  mounted() {
    if (!this.myValue) {
      this.$refs.input.load()
    }
  },
  methods: {
    onSelected(item) {
      this.img = item && item.preview['295x166'] ? item.preview['295x166'] : null
    },
  },
}
</script>
