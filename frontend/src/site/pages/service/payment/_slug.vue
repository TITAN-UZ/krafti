<template>
  <div class="p-5">
    <div v-if="$route.params.slug == 'success'" class="text-center">
      <div v-if="loading" style="margin: 100px 0;">
        <b-spinner variant="primary" style="width: 3rem; height: 3rem;" />
        <p class="mt-3">Проверка статуса платежа...</p>
      </div>
    </div>

    <div v-if="status === false" class="alert alert-danger text-center p-5 col-12 col-md-6 m-auto">
      <strong>{{ error }}</strong>
    </div>
    <div v-if="status === true" class="alert alert-success text-center p-5 col-12 col-md-6 m-auto">
      <strong>Спасибо за оплату курса!</strong>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: true,
      status: null,
      error: 'Курс не был оплачен',
    }
  },
  validate({params}) {
    return ['success', 'failure'].includes(params.slug)
  },
  async mounted() {
    if (this.$route.params.slug === 'failure') {
      this.loading = false
      this.status = false
      if (this.$route.query.InvId !== undefined) {
        this.$axios.get('user/order', {params: {id: this.$route.query.InvId}}).then((res) => {
          this.$fb.track('Purchase',{value: res.data.cost, currency: 'RUB'})
          this.$router.replace({name: 'courses-cid', params: {cid: res.data.course_id}})
        })
      }
    } else {
      let res = null
      while (res === null) {
        res = await this.finalizeOrder()
      }
      this.loading = false
      this.status = res !== false
      if (res) {
        this.$router.replace('/courses/' + res)
      }
    }
  },
  /* async asyncData({app, query, params, error}) {
            let id = query['InvId'] !== undefined
                ? query['InvId']
                : 0;
            if (id > 0) {
                try {
                    const res = await app.$axios.get('user/order', {params: {id: id}});
                    return {
                        record: res.data,
                        type: params.slug,
                    };
                } catch (e) {
                    return error({statusCode: e.status, message: e.data})
                }
            } else {
                return error({statusCode: 404, message: 'Страница не найдена'})
            }
        }, */
  methods: {
    async finalizeOrder() {
      try {
        const res = await this.$axios.post('user/order', this.$route.query)
        if (res.status !== 200) {
          return null
        }
        return res.data.id
      } catch (e) {
        this.error = e.data
      }
      return false
    },
  },
}
</script>
