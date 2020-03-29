import Vue from 'vue'

import TableFilter from '../components/table-filter'
import TableFooter from '../components/table-footer'

Vue.component('table-filter', TableFilter)
Vue.component('table-footer', TableFooter)

export default ({app}) => {
  Vue.prototype.loadTable = (ctx, $component, action) => {
    const params = {
      page: ctx.currentPage,
      limit: ctx.perPage,
      sort: ctx.sortBy,
      dir: ctx.sortDesc ? 'desc' : 'asc',
    }
    for (const i in $component.filters) {
      if (Object.prototype.hasOwnProperty.call($component.filters, i) && $component.filters[i] !== undefined) {
        params[i] = $component.filters[i]
      }
    }

    if (action === undefined) {
      action = $component.$options.name
    }

    return app.$axios
      .get(action, {params})
      .then((res) => {
        const items = res.data
        $component.totalRows = items.total || 0
        if (items.total_cost !== undefined) {
          $component.totalCost = items.total_cost || 0
        }

        return items.rows || []
      })
      .catch((error) => {
        console.warn(error)
      })
  }
}
