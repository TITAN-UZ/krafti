<template>
  <vue2-datepicker
    class="date-picker"
    v-model="date"
    ref="datepicker"
    type="date"
    format="DD.MM.YY"
    range-separator="~"
    input-class="form-control"
    :width="width"
    :lang="'ru'"
    :confirm="false"
    :editable="true"
    :range="true"
    :shortcuts="false"
    :first-day-of-week="1"
    :value-type="formatDate"
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
            value: {
                type: Array,
                default() {
                    return []
                }
            },
            width: {
                type: String,
                default: 'auto'
            },
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
    /*.mx-datepicker-range {
      width: 100%;
    }*/

    .mx-range-wrapper {
      width: auto;
      display: flex;
      flex-wrap: wrap;
    }

    .mx-input-wrapper {
      .mx-input-append {
        top: 2px;
        cursor: pointer;
      }

      .mx-clear-wrapper {
        display: block;

        & + .mx-input-append {
          display: none;
        }
      }
    }
  }
</style>
