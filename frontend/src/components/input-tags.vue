<template>
  <div v-if="tags.length">
    <b-form-tags v-model="myValue" no-outer-focus>
      <!--eslint-disable-next-line vue/no-template-shadow-->
      <template v-slot="{tags, disabled, size, removeTag, inputAttrs}">
        <ul class="list-unstyled mt-n1 mb-0 d-flex flex-wrap align-items-center" @click="$refs.input.focus()">
          <b-form-tag v-for="tag in tags" :key="tag" :variant="variantSelected" tag="li" class="mt-1 mr-1" @remove="removeTag(tag)">
            {{ renderTag(tag) }}
          </b-form-tag>
          <li aria-live="off" class="d-inline-flex flex-grow-1 mt-1">
            <!--suppress HtmlFormInputWithoutLabel -->
            <input
              ref="input"
              v-model="entered"
              class="b-form-tags-input w-100 flex-grow-1 p-0 m-0 bg-transparent border-0"
              v-bind="inputAttrs"
              :disabled="disabled"
              :size="size"
              :placeholder="placeholder || 'Поиск...'"
              :style="{outline: 0, minWidth: '5rem', color: '#fff'}"
              @change="onChange"
              @keydown="onInput"
            />
          </li>
        </ul>
      </template>
    </b-form-tags>
    <div class="mt-1 d-flex flex-wrap">
      <b-button v-for="tag in options" :key="tag.id" size="sm" class="mt-1 mr-1" :variant="variantSuggest" tabindex="-1" @click.prevent="addTag(tag[valueField])">
        {{ tag[textField] }}
      </b-button>
    </div>
  </div>
  <b-form-tags
    v-else
    v-model="myValue"
    no-outer-focus
    add-on-change
    remove-on-delete
    input-class=""
    tag-remove-label=""
    add-button-text=""
    duplicate-tag-text=""
    add-button-variant="default"
    :tag-variant="variantSelected"
    :separator="separator"
    :placeholder="placeholder || 'Укажите значения...'"
  />
</template>

<script>
export default {
  name: 'Tags',
  props: {
    value: {
      type: Array,
      default: () => {
        return []
      },
    },
    tags: {
      type: Array,
      default: () => {
        return []
      },
    },
    valueField: {
      type: String,
      default: 'id',
    },
    textField: {
      type: String,
      default: 'title',
    },
    placeholder: {
      type: String,
      default: null,
    },
    separator: {
      type: String,
      default: ' ,;',
    },
    variantSelected: {
      type: String,
      default: 'primary',
    },
    variantSuggest: {
      type: String,
      default: 'secondary',
    },
  },
  data() {
    return {
      entered: '',
    }
  },
  computed: {
    myValue: {
      get() {
        return this.value
      },
      set(newValue) {
        newValue.map((val, idx, arr) => {
          arr[idx] = /^\d+$/.test(val) ? Number(val) : val
        })
        this.$emit('input', newValue)
      },
    },
    options() {
      return this.tags.filter((item) => {
        return !this.value.includes(item[this.valueField]) && this.entered.length && item[this.textField].toLowerCase().includes(this.entered.toLowerCase())
      })
    },
  },
  methods: {
    renderTag(value) {
      const filtered = this.tags.filter((item) => String(item[this.valueField]) === String(value))

      return filtered.length ? filtered[0][this.textField] : value
    },
    addTag(val) {
      this.myValue.push(/^\d+$/.test(val) ? Number(val) : val)
      if (!this.options.length) {
        this.entered = ''
      }
    },
    onInput(e) {
      if (e.code === 'Enter') {
        e.preventDefault()
        if (this.options.length === 1) {
          this.addTag(this.options[0][this.valueField])
        }
      } else if (e.code === 'Backspace' && !this.entered.length && this.myValue.length) {
        e.preventDefault()
        this.myValue = this.myValue.slice(0, -1)
      } else if (e.code === 'Tab') {
        if (this.options.length === 1) {
          this.addTag(this.options[0][this.valueField])
        } else {
          this.entered = ''
        }
      }
    },
    onChange() {
      if (this.options.length === 1) {
        this.addTag(this.options[0][this.valueField])
      }
    },
  },
}
</script>
