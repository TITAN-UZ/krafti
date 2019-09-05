<template>
  <div class="p-5">

    <div class="text-center" v-if="$route.params.slug == 'success'">
      <div style="margin: 100px 0;" v-if="loading">
        <b-spinner variant="primary" style="width: 3rem; height: 3rem;"/>
        <p class="mt-3">Проверка статуса платежа...</p>
      </div>
    </div>

    <div class="alert alert-danger text-center p-5 col-12 col-md-6 m-auto" v-if="status === false">
      <strong>{{error}}</strong>
    </div>
    <div class="alert alert-success text-center p-5 col-12 col-md-6 m-auto" v-if="status === true">
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
                error: 'Курс не был оплачен'
            }
        },
        validate({params}) {
            return ['success', 'failure'].indexOf(params.slug) > -1
        },
        /*async asyncData({app, query, params, error}) {
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
                    return error({statusCode: 404, message: 'Страница не найдена'})
                }
            } else {
                return error({statusCode: 404, message: 'Страница не найдена'})
            }
        },*/
        methods: {
            async finalizeOrder() {
                try {
                    let res = await this.$axios.post('user/order', this.$route.query);
                    if (res.status !== 200) {
                        return null
                    }
                    return res.data.id;
                } catch (e) {
                    this.error = e.data;
                }
                return false;
            },
        },
        async mounted() {
            if (this.$route.params.slug == 'failure') {
                this.loading = false;
                this.status = false;
            } else {
                let res = null;
                while (res === null) {
                    res = await this.finalizeOrder();
                }
                this.loading = false;
                this.status = res !== false;
                if (res) {
                    this.$router.replace('/courses/' + res);
                }
            }
        }
    }
</script>
