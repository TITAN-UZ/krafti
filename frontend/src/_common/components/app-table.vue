<template>
  <div>
    <slot name="filters">
      <table-filter :filters="filters" :table="name" :visible="filtersVisible">
        <slot v-for="(_, slotName) in $slots" :slot="slotName" :name="slotName" />
      </table-filter>
    </slot>

    <slot name="header" />

    <div class="b-table-wrapper">
      <b-table
        :id="name"
        :busy="busy"
        :stacked="tableStacked"
        :class="tableClass"
        :items="items"
        :fields="fields"
        :current-page="page"
        :per-page="tLimit"
        :sort-by.sync="tSort"
        :sort-direction.sync="tDir"
        :sort-desc="dir === 'desc'"
        :tbody-tr-class="rowClass"
        :primary-key="primaryKey"
        show-empty
        no-sort-reset
        no-local-sorting
        empty-text="Подходящих результатов не найдено"
        empty-filtered-text="Подходящих результатов не найдено"
      >
        <template v-for="(_, slotName) in $scopedSlots" :slot="slotName" slot-scope="slotData">
          <slot :name="slotName" v-bind="slotData" />
        </template>
      </b-table>
    </div>

    <slot name="footer">
      <table-footer
        :busy="busy"
        :table="name"
        :total-rows="totalRows"
        :page.sync="page"
        :limit.sync="limit"
        :forms="forms"
      >
        <slot v-for="(_, slotName) in $slots" :slot="slotName" :name="slotName" />
      </table-footer>
    </slot>
  </div>
</template>

<script>
import axios from 'axios'
import TableFilter from './table-filter'
import TableFooter from './table-footer'

export default {
  name: 'AppTable',
  components: {
    TableFilter,
    TableFooter,
  },
  props: {
    fields: {
      type: Array,
      required: false,
      default() {
        return []
      },
    },
    url: {
      type: String,
      required: true,
    },
    name: {
      type: String,
      required: false,
      default() {
        return this.url.replace('/', '-')
      },
    },
    limit: {
      type: Number,
      required: false,
      default: 20,
    },
    sort: {
      type: String,
      required: false,
      default: 'id',
    },
    dir: {
      type: String,
      required: false,
      default: 'asc',
    },
    forms: {
      type: String,
      required: false,
      default: 'запись|записи|записей',
    },
    filters: {
      type: Object,
      required: false,
      default() {
        return {
          // query: '',
        }
      },
    },
    filtersVisible: {
      type: Boolean,
      default: false,
    },
    primaryKey: {
      type: String,
      default: 'id',
    },
    rowClass: {
      type: Function,
      required: false,
      default() {},
    },
    tableClass: {
      type: String,
      default: 'mt-5',
    },
    tableStacked: {
      type: [String, Boolean],
      default: false,
    },
    hideLoading: {
      type: Boolean,
      default: false,
    },
    pageLimit: {
      type: Number,
      default: 7,
    },
  },
  data() {
    return {
      page: 1,
      busy: false,
      totalRows: 0,
      items: (ctx) => {
        return this.loadTable(ctx)
      },
    }
  },
  computed: {
    tSort: {
      get() {
        return this.sort
      },
      set(newValue) {
        this.$emit('update:sort', newValue)
      },
    },
    tDir: {
      get() {
        return this.dir
      },
      set(newValue) {
        this.$emit('update:dir', newValue)
      },
    },
    tLimit: {
      get() {
        return this.limit
      },
      set(newValue) {
        this.$emit('update:limit', newValue)
      },
    },
  },
  watch: {
    filters: {
      deep: true,
      handler() {
        if (this.page !== 1) {
          this.page = 1
        }
      },
    },
  },
  created() {
    this.$root.$on('app::' + this.name + '::update', () => {
      this.refresh()
    })
  },
  methods: {
    refresh() {
      this.$root.$emit('bv::refresh::table', this.name)
    },
    getParams(asObject = false) {
      let params = asObject ? {} : ''
      for (const i in this.filters) {
        if (Object.prototype.hasOwnProperty.call(this.filters, i)) {
          if (this.filters[i] !== '' && this.filters[i] !== null) {
            if (typeof this.filters[i] === 'object' && !asObject) {
              this.filters[i].forEach((v) => {
                params += `&${i}[]=${v}`
              })
            } else if (asObject) {
              params[i] = this.filters[i]
            } else {
              params += `&${i}=${this.filters[i]}`
            }
          }
        }
      }
      if (!asObject) {
        params += `&sort=${this.tSort}`
        params += `&dir=${this.tDir}`
      }

      return params
    },
    async loadTable(ctx) {
      const params = {
        page: ctx.currentPage,
        limit: ctx.perPage,
        sort: ctx.sortBy,
        dir: ctx.sortDesc ? 'desc' : 'asc',
      }
      this.tSort = params.sort
      this.tDir = params.dir

      for (const i in this.filters) {
        if (Object.prototype.hasOwnProperty.call(this.filters, i) && this.filters[i] !== undefined) {
          params[i] = this.filters[i]
        }
      }

      if (this.url === undefined) {
        this.url = this.$options.name.toLowerCase()
      }
      this.busy = true

      try {
        axios.defaults.headers = this.$axios.defaults.headers
        axios.defaults.baseURL = this.$axios.defaults.baseURL
        let {data: items} = this.hideLoading
          ? await axios.get(this.url, {params})
          : await this.$axios.get(this.url, {params})

        if (this.$listeners.onLoad) {
          const res = this.$listeners.onLoad(items)
          if (res && res.rows) {
            items = res
          }
        }
        this.totalRows = items.total || 0

        return items.rows || []
      } catch (e) {
      } finally {
        this.busy = false
      }
    },
  },
}
</script>
