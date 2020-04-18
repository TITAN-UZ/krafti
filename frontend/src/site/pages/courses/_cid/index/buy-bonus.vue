<template>
  <b-modal ref="modalWindowBuy" hide-footer visible @hidden="onHidden">
    <div class="container">
      <h4 class="text-center pb-5">
        Вы действительно хотите купить этот урок за {{ cost | number }} {{ cost | noun('крафтик|крафтика|крафтиков') }}?
      </h4>

      <b-row class="justify-content-around">
        <b-button :disabled="loading" @click="hideModal">Отмена</b-button>
        <b-button :disabled="loading" variant="success" @click="onSubmit">Списать крафтики!</b-button>
      </b-row>
    </div>

    <template slot="modal-header">
      <button class="close" type="button" aria-label="Close" @click="hideModal">
        <fa :icon="['fal', 'times']" size="2x" />
      </button>
    </template>
  </b-modal>
</template>

<script>
import {faTimes} from '@fortawesome/pro-light-svg-icons'

export default {
  auth: true,
  data() {
    return {
      loading: false,
      bought: false,
      payment: {
        period: 0,
        service: 'account',
        course_id: this.$route.params.cid,
      },
      cost: process.env.COINS_BUY_BONUS,
    }
  },
  created() {
    this.$fa.add(faTimes)
  },
  methods: {
    hideModal() {
      if (this.$refs.modalWindowBuy) {
        this.$refs.modalWindowBuy.hide()
      }
    },
    onHidden() {
      const that = this
      if (that.bought) {
        that.$store.commit('courses/progress', {
          id: that.$route.params.cid,
          data: {section: 0, rank: 0},
        })

        // Используем глобальную функцию, потому что на момень её запуска компонент уже будет уничтожен
        setTimeout(() => {
          that.$router.push({
            name: 'courses-cid-index-lesson-lid',
            params: {cid: that.$route.params.cid, lid: that.bought},
          })
        }, 500)
      }

      that.$router.push({name: 'courses-cid', params: that.$route.params})
    },
    async onSubmit() {
      this.loading = false
      try {
        const {data: res} = await this.$axios.post('user/payment', this.payment)
        this.bought = res.lesson_id
        this.hideModal()
      } catch (e) {
      } finally {
        this.loading = false
      }
    },
  },
}
</script>
