import Vue from 'vue'

// Timeout methods
Vue.mixin({
  data() {
    return {
      TimerIds: [],
    }
  },
  beforeDestroy() {
    if (this.$data.TimerIds !== null) {
      this.$data.TimerIds.forEach((id) => {
        this.clearTimeout(id)
      })
      this.$data.TimerIds = null
    }
  },
  methods: {
    setTimeout(callback, timeout) {
      if (!this.$data.TimerIds) {
        return null
      }
      const timer = setTimeout(() => {
        this.clearTimeout(timer)
        callback()
      }, timeout)
      this.$data.TimerIds.push(timer)
      return timer
    },
    setInterval(callback, timeout) {
      if (!this.$data.TimerIds) {
        return null
      }
      const timer = setInterval(callback, timeout)
      this.$data.TimerIds.push(timer)
      return timer
    },
    clearTimeout(timerId) {
      this._removeTimer(timerId)
      return clearTimeout(timerId)
    },
    clearInterval(timerId) {
      this._removeTimer(timerId)
      return clearInterval(timerId)
    },
    _removeTimer(timerId) {
      if (this.$data.TimerIds) {
        this.$data.TimerIds = this.$data.TimerIds.filter((id) => id !== timerId)
      }
    },
  },
})

// Modal Confirm
Vue.mixin({
  created() {
    const that = this
    this.$confirm = async (message, callback) => {
      try {
        const res = await that.$bvModal.msgBoxConfirm(message, {
          title: 'Требуется подтверждение',
          okVariant: 'danger',
          okTitle: 'Да',
          cancelTitle: 'Нет',
          footerClass: 'justify-content-between',
          hideHeaderClose: false,
        })
        if (res) {
          callback()
        }
      } catch (e) {
        console.error(e)
      }
    }
  },
})
