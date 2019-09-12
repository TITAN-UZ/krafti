<template>
  <div>
    <b-row no-gutters class="justify-content-center justify-content-md-between align-items-center">
      <slot name="actions"></slot>

      <slot name="search">
        <b-input-group class="mt-2 mt-md-0 ml-md-auto col-md-4">
          <b-input-group-prepend v-if="moreFilters">
            <b-button @click.prevent="showFilters = !showFilters" :variant="showFilters ? 'success' : 'secondary'">
              <fa :icon="['far', 'filter']"/>
            </b-button>
          </b-input-group-prepend>
          <b-form-input v-model="filters.query" placeholder="Поиск..." @keydown="changeQuery()"/>
          <b-input-group-append>
            <b-button :disabled="!filters.query" @click.prevent="filters.query = ''">
              <fa :icon="['far', 'times']"/>
            </b-button>
          </b-input-group-append>
        </b-input-group>
      </slot>
    </b-row>

    <b-alert v-model="showFilters" @dismissed="showFilters = false" class="mt-2" v-if="moreFilters">
      <b-row class="text-center justify-content-around justify-content-md-between">
        <div v-if="filters.date !== undefined">
          <b-form-group label="Дата">
            <date-picker
              ref="datepicker"
              v-model="filters.date"
              type="date" format="DD.MM.YY" lang="ru" range-separator="~" width="auto"
              :confirm="false"
              :editable="true"
              :range="true"
              :shortcuts="false"
              :first-day-of-week="1"
              :value-type="formatDate"
              input-class="form-control"
              @clear="onDateClear"
              @change="onDateChange">
              <template slot="calendar-icon">
                <fa :icon="['far', 'calendar-alt']"/>
              </template>
              <template slot="mx-clear-icon">
                <fa :icon="['far', 'times']"/>
              </template>
            </date-picker>
          </b-form-group>
        </div>

        <div v-if="filters.role_id !== undefined">
          <b-form-group label="Группа юзеров">
            <b-form-select v-model="filters.role_id" value-field="id" text-field="title" :options="roles">
              <template slot="first">
                <option :value="null">Все</option>
              </template>
            </b-form-select>
          </b-form-group>
        </div>

        <div v-if="filters.course_id !== undefined">
          <b-form-group label="Курс">
            <b-form-select v-model="filters.course_id" value-field="id" text-field="title" :options="courses">
              <template slot="first">
                <option :value="null">Все</option>
              </template>
            </b-form-select>
          </b-form-group>
        </div>

        <div v-if="filters.service !== undefined">
          <b-form-group label="Оплата">
            <b-form-select v-model="filters.service">
              <option :value="null">Все</option>
              <option value="robokassa">Робокасса</option>
              <option value="paypal">PayPal</option>
            </b-form-select>
          </b-form-group>
        </div>

        <div v-if="filters.status !== undefined">
          <b-form-group label="Статус заказа">
            <b-form-select v-model="filters.status">
              <option :value="null">Все</option>
              <option :value="1">Новый</option>
              <option :value="2">Оплачен</option>
            </b-form-select>
          </b-form-group>
        </div>

        <div v-if="filters.confirmed !== undefined">
          <b-form-group label="Email проверен">
            <b-form-select v-model="filters.confirmed">
              <option :value="null">Все</option>
              <option :value="0">Не проверен</option>
              <option :value="1">Проверен</option>
            </b-form-select>
          </b-form-group>
        </div>

        <div v-if="filters.active !== undefined">
          <b-form-group label="Статус пользователя">
            <b-form-select v-model="filters.active">
              <option :value="null">Все</option>
              <option :value="0">Неактивные</option>
              <option :value="1">Активные</option>
            </b-form-select>
          </b-form-group>
        </div>
      </b-row>
      <b-row v-if="changedFilters">
        <a href="#" @click.prevent="clearFilters" class="ml-auto text-danger">
          <fa :icon="['far', 'backspace']"/>
          Очистить
        </a>
      </b-row>
    </b-alert>
  </div>
</template>

<script>
    import {faBackspace, faCalendarAlt, faFilter, faPlus, faTimes} from '@fortawesome/pro-regular-svg-icons'

    export default {
        name: 'table-filter',
        props: {
            filters: {
                type: Object,
                required: false,
                default() {
                    return {
                        query: '',
                        /*role_id: null,
                        city_id: null,
                        network_id: null,
                        region_id: null,*/
                    }
                }
            },
            table: {
                type: String,
                required: true,
            },
            statuses: {
                type: Array,
                required: false,
            },
            visible: {
                type: Boolean,
                required: false,
                default: false,
            }
        },
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
                roles: {},
                cities: {},
                regions: {},
                networks: {},
                providers: {},
                users: {},
                showFilters: this.visible === true,
                formatDate
            }
        },
        watch: {
            filters: {
                deep: true,
                handler() {
                    this.$root.$emit('app::' + this.table + '::update', this.filters);
                },
            }
        },
        computed: {
            moreFilters() {
                let keys = Object.keys(this.filters).filter(item => {
                    return !['query', 'course_id', 'section'].includes(item);
                });

                return keys.length > 0;
            },
            changedFilters() {
                let changed = false;
                for (let i in this.filters) {
                    if (this.filters.hasOwnProperty(i) && i != 'query') {
                        if (this.filters[i] !== null) {
                            changed = true;
                            break;
                        }
                    }
                }

                return changed;
            },
        },
        methods: {
            clearFilters() {
                for (let i in this.filters) {
                    if (this.filters.hasOwnProperty(i) && i != 'query') {
                        this.filters[i] = null;
                    }
                }
                this.showFilters = false;
            },
            changeQuery() {
                this.$root.$emit('app::' + this.table + '::query', this.filters.query);
            },
            onDateClear() {
                this.filters.date = null;
            },
            onDateChange() {
                this.$refs.datepicker.closePopup()
            }
        },
        created() {
            this.$fa.add(faTimes, faCalendarAlt, faBackspace, faPlus, faFilter);

            for (let i in this.filters) {
                if (!this.filters.hasOwnProperty(i)) {
                    continue;
                }

                let action, storage;
                let params = {
                    limit: 0
                };

                if (i == 'role_id') {
                    action = 'admin/user-roles';
                    storage = 'roles';
                } else if (i == 'course_id') {
                    action = 'admin/courses';
                    storage = 'courses';
                }

                if (action && storage) {
                    this.$axios.get(action, {params: params}).then(res => {
                        let rows = {};
                        res.data.rows.forEach(v => {
                            rows[v.id] = v;
                        });
                        this[storage] = rows;
                    });
                }
            }
        }
    }
</script>

<style lang="scss">
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

  .checkbox-group {
    legend {
      text-indent: -999999px;
    }

    .form-control {
      background: transparent;
      border-color: transparent;
    }
  }
</style>
