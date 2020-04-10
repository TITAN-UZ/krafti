<template>
  <div class="mb-3">
    <div class="d-flex justify-content-center">
      <!--<input-date v-model="date" :range="true" :disabled-date="checkDate" class="col-md-4" />-->
      <b-button :disabled="checkPrev()" size="sm" @click="changePrev()">
        <fa :icon="['fas', 'chevron-left']" />
      </b-button>
      <b-button :disabled="type == 'day'" size="sm" class="ml-1 mr-1" @click="changeType('day')">Дни</b-button>
      <b-button :disabled="type == 'month'" size="sm" @click="changeType('month')">Месяцы</b-button>
      <b-button :disabled="type == 'year'" size="sm" class="ml-1 mr-1" @click="changeType('year')">Годы</b-button>
      <b-button :disabled="checkNext()" size="sm" @click="changeNext()">
        <fa :icon="['fas', 'chevron-right']" />
      </b-button>
      <b-button size="sm" class="ml-2" @click="load()">
        <b-spinner v-if="loading" small />
        <fa v-else :icon="['fas', 'sync']" />
      </b-button>
    </div>
    <div style="width: 100%; overflow-x: auto;">
      <apexchart type="area" :series="series" :options="options" :height="height" :width="width" :style="{opacity: loading ? 0.5 : 1, 'min-width': '600px'}" />
    </div>
  </div>
</template>

<script>
import {faChevronLeft, faChevronRight, faSync} from '@fortawesome/pro-solid-svg-icons'

export default {
  name: 'ChartOrders',
  props: {
    height: {
      type: [String, Number],
      default: 400,
    },
    width: {
      type: [String, Number],
      default: '100%',
    },
    chartStart: {
      type: String,
      default: '2019-09-11',
    },
    dateFormat: {
      type: String,
      default: 'YYYY-MM-DD',
    },
  },
  data() {
    const ru = require('apexcharts/dist/locales/ru')
    const that = this
    return {
      type: 'day',
      loading: false,
      date: [
        this.$moment()
          .subtract(1, 'month')
          .format(this.dateFormat),
        this.$moment().format(this.dateFormat),
      ],
      series: [],
      xaxis: [],
      idx: 0,
      options: {
        colors: ['#2b908f', '#FEB019'],
        chart: {
          locales: [ru],
          defaultLocale: 'ru',
          toolbar: false,
        },
        tooltip: {
          x: {
            formatter(idx) {
              return that.$options.filters.date(that.xaxis[idx - 1], 'DD MMMM YYYY')
            },
          },
          y: {
            formatter(val, obj) {
              val = that.$options.filters.number(val)
              if (!obj.seriesIndex) {
                val += ' руб.'
              } else {
                val += ' чел.'
              }
              return val
            },
          },
        },
        dataLabels: {
          enabled: false,
          formatter(val) {
            return that.$options.filters.number(val) + ' руб.'
          },
        },
        yaxis: [
          {
            title: {
              text: 'Оплаченные покупки',
            },
            labels: {
              formatter(val) {
                return val > 0 ? `${val / 1000}k руб.` : null
              },
            },
          },
          {
            opposite: true,
            title: {
              text: 'Новые пользователи',
            },
          },
        ],
        xaxis: {
          labels: {
            rotate: 0,
            offsetY: 5,
            formatter(val) {
              let date
              if (that.type === 'year') {
                date = val
              } else if (that.type === 'month') {
                date = that.$options.filters.ucfirst(that.$moment(val, 'YYYY-MM').format('MMMM'))
              } else {
                date = that.$options.filters.date(val, 'DD MMM')
              }
              return date
            },
          },
          tickAmount: 10,
          tooltip: {
            enabled: false,
          },
          offsetY: -15,
        },
      },
    }
  },
  watch: {
    date() {
      this.load()
    },
  },
  created() {
    this.$fa.add(faChevronLeft, faChevronRight, faSync)
    this.load()
  },
  methods: {
    async load() {
      this.loading = true
      try {
        const {data} = await this.$axios.get('admin/charts/orders', {params: {date: this.date, type: this.type}})
        const keys = []
        const orders = {
          name: 'Оплаченные покупки',
          data: [],
        }
        const users = {
          name: 'Новые пользователи',
          data: [],
        }

        data.rows.forEach((item) => {
          keys.push(item.date)
          orders.data.push(item.orders)
          users.data.push(item.users)
        })

        const xAxis = this.options.xaxis
        xAxis.categories = keys
        this.options = {xaxis: xAxis}
        this.series = [orders, users]
        this.xaxis = keys
      } catch (e) {
        console.error(e)
      } finally {
        this.loading = false
      }
    },
    /* checkDate(val) {
      const now = this.$moment()
      const start = this.$moment(this.chartStart, this.dateFormat)

      return val < start || val > now
    }, */
    checkPrev() {
      if (this.type === 'year') {
        return true
      }
      return this.type === 'month'
        ? this.$moment(this.date[0], this.dateFormat)
            .subtract(1, 'year')
            .format('YYYY') <= this.$moment(this.chartStart, this.dateFormat).format('YYYY')
        : this.$moment(this.date[0], this.dateFormat)
            .subtract(1, 'month')
            .format('YYYY-MM') <=
            this.$moment(this.chartStart, this.dateFormat)
              .subtract(1, 'month')
              .format('YYYY-MM')
    },
    checkNext() {
      if (this.type === 'year') {
        return true
      }
      return this.type === 'month'
        ? this.$moment(this.date[0], this.dateFormat)
            .add(1, 'year')
            .format('YYYY') >= this.$moment().format('YYYY')
        : this.$moment(this.date[0], this.dateFormat)
            .add(1, 'month')
            .format('YYYY-MM') >= this.$moment().format('YYYY-MM')
    },
    changeType(type) {
      this.type = type

      if (type === 'year') {
        this.date = null
      } else if (type === 'month') {
        this.date = [
          this.$moment()
            .subtract(1, 'year')
            .format(this.dateFormat),
          this.$moment().format(this.dateFormat),
        ]
      } else {
        this.date = [
          this.$moment()
            .subtract(1, 'month')
            .format(this.dateFormat),
          this.$moment().format(this.dateFormat),
        ]
      }
    },
    changePrev() {
      this.date =
        this.type === 'month'
          ? [
              this.$moment(this.date[0], this.dateFormat)
                .subtract(1, 'year')
                .format(this.dateFormat),
              this.$moment(this.date[1], this.dateFormat)
                .subtract(1, 'year')
                .format(this.dateFormat),
            ]
          : [
              this.$moment(this.date[0], this.dateFormat)
                .subtract(1, 'month')
                .format(this.dateFormat),
              this.$moment(this.date[1], this.dateFormat)
                .subtract(1, 'month')
                .format(this.dateFormat),
            ]
    },
    changeNext() {
      this.date =
        this.type === 'month'
          ? [
              this.$moment(this.date[0], this.dateFormat)
                .add(1, 'year')
                .format(this.dateFormat),
              this.$moment(this.date[1], this.dateFormat)
                .add(1, 'year')
                .format(this.dateFormat),
            ]
          : [
              this.$moment(this.date[0], this.dateFormat)
                .add(1, 'month')
                .format(this.dateFormat),
              this.$moment(this.date[1], this.dateFormat)
                .add(1, 'month')
                .format(this.dateFormat),
            ]
    },
  },
}
</script>
