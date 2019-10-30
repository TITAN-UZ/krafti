<template>
  <vue2-datepicker
    class="date-picker"
    v-model="date"
    ref="datepicker"
    range-separator="~"
    input-class="form-control"
    :lang="'ru'"
    :confirm="false"
    :editable="true"
    :shortcuts="false"
    :width="width"
    :type="type"
    :range="range"
    :format="format"
    :first-day-of-week="1"
    :value-type="formatDate"
    :input-attr="{required: required, disabled: disabled}"
    :default-value="defaultValue"
    :not-after="notAfter"
    @clear="onDateClear"
    @change="onDateChange">
    <template slot="calendar-icon">
      <fa :icon="['far', 'calendar-alt']"/>
    </template>
    <template slot="mx-clear-icon">
      <fa :icon="['far', 'times']"/>
    </template>
  </vue2-datepicker>
</template>

<script>
    import {faCalendarAlt, faTimes} from '@fortawesome/pro-regular-svg-icons'

    export default {
        name: 'date-picker',
        data() {
            const formatDate = {
                value2date: value => {
                    return value ? this.$moment(new Date(value), 'DD.MM.YYYY').toDate() : null;
                },
                date2value: date => {
                    return date ? this.$moment(date).format('YYYY-MM-DD') : null;
                }
            };
            return {
                formatDate
            }
        },
        props: {
            value: null,
            width: {
                type: String,
                default: 'auto'
            },
            range: {
                type: Boolean,
                default: true
            },
            format: {
                type: String,
                default: 'DD.MM.YYYY'
            },
            type: {
                type: String,
                default: 'date'
            },
            disabled: false,
            required: false,
            defaultValue: null,
            notAfter: null
        },
        computed: {
            date: {
                get() {
                    return this.value
                },
                set(newValue) {
                    this.$emit('input', newValue)
                }
            }
        },
        methods: {
            onDateClear() {
                this.date = null;
            },
            onDateChange() {
                this.$refs.datepicker.closePopup()
            }
        },
        created() {
            this.$fa.add(faCalendarAlt, faTimes)
        }
    }
</script>

<style lang="scss">
  .date-picker {
    width: 100% !important;

    .mx-range-wrapper {
      width: auto;
      display: flex;
      flex-wrap: wrap;
    }

    .mx-input-wrapper {
      .mx-input-append {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        svg {
          margin-top: -4px;
        }
        &.mx-clear-wrapper {
          & + .mx-input-append {
            display: none;
          }
        }
      }
    }
  }
</style>
