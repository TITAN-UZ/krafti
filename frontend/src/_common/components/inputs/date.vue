<template>
  <vue2-datepicker
    ref="datepicker"
    v-model="myValue"
    range-separator="~"
    :input-class="inputClass"
    :class="{'date-picker': true, 'hide-buttons': hideButtons}"
    :confirm="type !== 'date'"
    :editable="true"
    :width="width"
    :type="type"
    :range="range"
    :format="format"
    :lang="lang"
    :placeholder="placeholder"
    :title-format="externalFormat"
    :time-title-format="externalFormat"
    :first-day-of-week="1"
    :value-type="type === 'datetime' ? 'YYYY-MM-DD HH:mm:ss' : 'YYYY-MM-DD'"
    :input-attr="{required: required, autofocus: autofocus}"
    :disabled-date="disabledDate"
    :disabled="disabled"
    @clear="onDateClear"
  >
    <template slot="icon-calendar">
      <b-button v-show="!myValue || !myValue.length" variant="secondary">
        <fa :icon="['fas', 'calendar-alt']" class="fa-fw" />
      </b-button>
    </template>
    <template v-show="myValue" slot="icon-clear">
      <b-button variant="secondary">
        <fa :icon="['fas', 'times']" class="fa-fw" />
      </b-button>
    </template>
  </vue2-datepicker>
</template>

<script>
import VueDatePicker from 'vue2-datepicker'
import 'vue2-datepicker/locale/ru'

export default {
  name: 'InputDate',
  components: {'vue2-datepicker': VueDatePicker},
  props: {
    value: {
      type: [String, Array],
      default: null,
    },
    width: {
      type: String,
      default: 'auto',
    },
    range: {
      type: Boolean,
      default: false,
    },
    type: {
      type: String,
      default: 'date',
    },
    internalFormat: {
      type: String,
      default() {
        return this.type === 'datetime' ? 'YYYY-MM-DD HH:mm:ss' : 'YYYY-MM-DD'
      },
    },
    externalFormat: {
      type: String,
      default() {
        return this.type === 'datetime' ? 'DD.MM.YYYY HH:mm:ss' : 'DD.MM.YYYY'
      },
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false,
    },
    required: {
      type: Boolean,
      required: false,
      default: false,
    },
    autofocus: {
      type: Boolean,
      required: false,
      default: false,
    },
    placeholder: {
      type: String,
      default() {
        return this.range ? 'Выберите период' : 'Выберите дату'
      },
    },
    inputClass: {
      type: [String, Object],
      required: false,
      default: 'form-control',
    },
    disabledDate: {
      type: Function,
      required: false,
      default() {
        return false
      },
    },
    hideButtons: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      format: {
        stringify: (value) => {
          return value ? this.$moment(value).format(this.externalFormat) : null
        },
        parse: (date) => {
          return date ? this.$moment(date, this.externalFormat).toDate() : null
        },
      },
      lang: {
        formatLocale: {
          firstDayOfWeek: 1,
        },
        monthBeforeYear: true,
      },
    }
  },
  computed: {
    myValue: {
      get() {
        let myValue
        if (this.value && this.range) {
          myValue = []
          if (typeof this.value === 'object') {
            this.value.forEach((v) => {
              myValue.push(this.formatValue(v))
            })
          } else {
            myValue.push(this.value)
          }
        } else {
          myValue = this.formatValue(this.value)
        }

        return myValue
      },
      set(newValue) {
        if (newValue && this.range) {
          const myValue = []
          newValue.forEach((v) => {
            myValue.push(this.formatValue(v, true))
          })
          this.$emit('input', myValue)
        } else {
          this.$emit('input', this.formatValue(newValue, true))
        }
        if (this.type === 'date') {
          this.$refs.datepicker.closePopup()
        }
      },
    },
  },
  methods: {
    onDateClear() {
      this.myValue = null
    },
    formatValue(value, toInternal = false) {
      if (!value || value.includes('0000-00-00')) {
        return null
      }
      return toInternal
        ? this.$moment(value, this.externalFormat).format(this.internalFormat)
        : this.$moment(value, this.internalFormat).format(this.externalFormat)
    },
  },
}
</script>

<style lang="scss">
@import '~vue2-datepicker/scss/index.scss';

.mx-datepicker {
  width: 100% !important;
  &:not(.hide-buttons) {
    .mx-input-wrapper {
      display: flex;
      align-items: stretch;

      .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
      }

      .mx-icon-calendar,
      .mx-icon-clear {
        display: block;
        position: relative;
        right: 0;
        top: auto;
        transform: none;
      }

      .btn {
        width: 35px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        height: 100%;
        text-align: center;
        padding: 0;

        svg {
          vertical-align: -0.125em;
        }
      }
    }
    input {
      width: calc(100% - 35px);
      padding-right: 0;
    }
  }
  &.hide-buttons {
    .mx-icon-calendar,
    .mx-icon-clear {
      display: none !important;
    }
  }
}

.mx-datepicker-popup {
  z-index: 1100 !important;
}
</style>
