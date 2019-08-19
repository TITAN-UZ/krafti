import Vue from 'vue'

import DatePicker from 'vue2-datepicker'
import TableFilter from '../components/table-filter'
import TableFooter from '../components/table-footer'

Vue.component('date-picker', DatePicker);
Vue.component('table-filter', TableFilter);
Vue.component('table-footer', TableFooter);

export default ({app}, inject) => {
    Vue.prototype.loadTable = (ctx, $component, action) => {
        let params = {
            page: ctx.currentPage,
            limit: ctx.perPage,
            sort: ctx.sortBy,
            dir: ctx.sortDesc ? 'desc' : 'asc',
        };
        for (let i in $component.filters) {
            if ($component.filters.hasOwnProperty(i) && $component.filters[i] !== undefined) {
                params[i] = $component.filters[i];
            }
        }

        if (action === undefined) {
            action = $component.$options.name;
        }

        return app.$axios.get(action, {params: params})
            .then(res => {
                let items = res.data;
                $component.totalRows = (items.total || 0);

                return items.rows || [];
            })
            .catch(error => {
                console.warn(error)
            });
    };
}
