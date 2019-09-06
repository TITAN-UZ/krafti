<template>
    <div class="wrapper">
        <client-only>
            <div class="wrapper__content">
                <div class="container" id="admin">
                    <ul class="nav nav-tabs justify-content-center justify-content-md-start">
                        <b-nav-item to="/admin/courses" v-if="$auth.hasScope('courses')">Курсы</b-nav-item>
                        <b-nav-item to="/admin/videos" v-if="$auth.hasScope('videos')">Видео</b-nav-item>
                        <b-nav-item to="/admin/users" v-if="$auth.hasScope('users')">Пользователи</b-nav-item>
                        <b-nav-item to="/admin/user-roles" v-if="$auth.hasScope('user-roles')">Группы</b-nav-item>
                    </ul>
                    <div class="tab-content">
                        <nuxt-child/>
                    </div>
                </div>
            </div>
        </client-only>
    </div>
</template>

<script>
    export default {
        //middleware: 'token',
        auth: true,
        created() {
            this.$app.header_image.set(false);
        }
    }
</script>

<style lang="scss">
    @import "../node_modules/bootstrap-vue/dist/bootstrap-vue.min.css";
    @import "~assets/scss/variables";

    #admin {
      .form-control, .custom-select {
        height: auto !important;
        padding: 0.375rem 0.75rem;
      }
      .col-form-label {
        padding-top: 4px;
      }

      .autosuggest__results-container {
        position: relative;
        width: 100%;
        .autosuggest__results {
          position: absolute;
          z-index: 100000;
          width: 100%;
          background: #fff;
          margin: -5px 0 0 0;
          padding: 0;
          max-height: 400px;
          overflow-y: scroll;
          box-shadow: $box-shadow;
          border-radius: 0 0 $input-border-radius $input-border-radius;
          .autosuggest__results-item {
            cursor: pointer;
            padding: 10px;
            &:active, &:hover, &:focus,
            &.autosuggest__results-item--highlighted {
              background-color: $lightestGray;
            }
          }
        }
      }
    }
</style>
