<template>
  <b-modal hide-footer visible @hidden="onHidden" ref="modalWindowBuy">

    <div class="container">
      <h4 class="text-center pb-5">
        Вы действительно хотите купить этот урок?
      </h4>

      <b-row class="justify-content-around">
        <b-button @click="hideModal" :disabled="loading">Отмена</b-button>
        <b-button @click="onSubmit" :disabled="loading" variant="success">Списать крафтики!</b-button>
      </b-row>
    </div>

    <template slot="modal-header">
      <button class="close" type="button" aria-label="Close" @click="hideModal">
        <fa :icon="['fal', 'times']" size="2x"/>
      </button>
    </template>
  </b-modal>
</template>

<script>
    import {faTimes} from '@fortawesome/pro-light-svg-icons'
    import AuthForm from '../../../../components/auth-form'

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
            }
        },
        components: {
            'auth-form': AuthForm,
        },
        methods: {
            hideModal() {
                if (this.$refs['modalWindowBuy']) {
                    this.$refs['modalWindowBuy'].hide();
                }
            },
            onHidden() {
                if (!this.bought) {
                    this.$router.push({name: 'courses-cid', params: this.$route.params})
                } else {
                    this.$root.$emit('app::course' + this.$route.params.cid + '::progress', {
                        section: 0,
                        rank: 0,
                    });

                    let that = this;
                    const params = {cid: that.$route.params.cid, lid: that.bought};
                    setTimeout(function() {
                        that.$router.push({name: 'courses-cid-index-lesson-lid', params: params});
                    }, 1000);

                    this.$router.push({name: 'courses-cid', params: this.$route.params});
                }
            },
            onSubmit() {
                this.$axios.post('user/payment', this.payment)
                    .then(res => {
                        this.loading = false;
                        this.bought = res.data.lesson_id;
                        this.hideModal();
                    })
                    .catch(() => {
                    })
            }
        },/*
        asyncData({app, params}) {
            return app.$axios.get('web/courses', {params: {id: params.cid}})
                .then(res => {
                    return {record: res.data}
                })
        },*/
        created() {
            this.$fa.add(faTimes);
        },
    }
</script>
