<template>
  <div class="p-5">
    <div class="alert alert-success text-center p-5 col-12 col-md-6 m-auto" v-if="$route.params.slug == 'reset'">
      <strong>Вы успешно сбросили свой пароль!</strong> Теперь можно указать новый в настройках профиля.
    </div>
    <div class="alert alert-info text-center p-5 col-12 col-md-6 m-auto" v-else>
      <strong>Спасибо за подтверждение email!</strong>
    </div>
  </div>
</template>

<script>
    export default {
        auth: false,
        validate({params}) {
            return ['reset', 'confirm'].indexOf(params.slug) > -1
        },
        async asyncData({app, query, params, error}) {
            try {
                const res = await app.$axios.get('security/confirm', {
                    params: {
                        type: params.slug,
                        user_id: query.user_id,
                        secret: query.secret
                    }
                });
                return res.data;
            } catch (e) {
                return error({statusCode: 404, message: 'Страница не найдена'})
            }
        },
        mounted() {
            if (this.$route.params.slug == 'reset' && this.token !== undefined) {
                this.$auth.setUserToken(this.token)
                    .then(() => {
                        this.$notify.success({
                            message: 'Вы успешно сбросили свой пароль! Теперь можно указать новый в настройках профиля.'
                        });
                    })
                    .finally(() => {
                        this.$router.replace('/');
                    });
            } else if (this.$route.params.slug == 'confirm') {
                this.$notify.info({message: 'Спасибо за подтверждение email!'});
                this.$router.replace('/');
            }
        },
    }
</script>
