<template>
  <div class="p-5">
    <div class="alert alert-success text-center p-5 col-12 col-md-6 m-auto" v-if="type == 'success'">
      <strong>Спасибо за оплату курса!</strong>
    </div>
    <div class="alert alert-danger text-center p-5 col-12 col-md-6 m-auto" v-else>
      <strong>Курс не был оплачен</strong>
    </div>
  </div>
</template>

<script>
    export default {
        validate({params}) {
            return ['success', 'failure'].indexOf(params.slug) > -1
        },
        async asyncData({app, query, params, error}) {
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
        },
        mounted() {
            if (this.record.course_id) {
                this.$router.replace('/courses/' + this.record.course_id)
            }
        }
    }
</script>
