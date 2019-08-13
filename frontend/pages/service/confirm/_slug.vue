<template>
    <div>

    </div>
</template>

<script>
    export default {
        mounted() {
            let tmp = this.$route.params.slug.split('.');
            if (tmp[0] == 'reset' && this.$auth.loggedIn) {
                this.$router.replace('/');
            }

            return this.$axios.get('security/confirm', {
                params: {
                    type: tmp[0],
                    user_id: tmp[1],
                    secret: tmp[2],
                }
            }).then((res) => {
                if (tmp[0] == 'reset') {
                    this.$auth.setUserToken(res.data.token).then(() => {
                        this.$router.replace('/');
                    });
                } else {
                    this.$notify.info({message: 'Спасибо за подтверждение email!'});
                    this.$router.replace('/');
                }
            }).catch(() => {
                this.$router.replace('/');
            })
        }
    }
</script>