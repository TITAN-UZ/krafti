<template>
  <div class="wrapper">
    <client-only>
      <div class="wrapper__content">
        <div id="admin" class="container">
          <ul class="nav nav-tabs justify-content-center justify-content-md-start">
            <b-nav-item v-if="$auth.hasScope('courses')" to="/admin/courses">Курсы</b-nav-item>
            <b-nav-item v-if="$auth.hasScope('orders')" to="/admin/orders">Заказы</b-nav-item>
            <b-nav-item v-if="$auth.hasScope('homeworks')" to="/admin/homeworks">Домашние работы</b-nav-item>
            <b-nav-item v-if="$auth.hasScope('comments')" to="/admin/comments">Комментарии</b-nav-item>
            <b-nav-item v-if="$auth.hasScope('users')" to="/admin/users">Пользователи</b-nav-item>
            <b-nav-item v-if="$auth.hasScope('user-roles')" to="/admin/user-roles">Группы</b-nav-item>
            <b-nav-item v-if="$auth.hasScope('discounts')" to="/admin/discounts">Скидки</b-nav-item>
            <b-nav-item v-if="$auth.hasScope('videos')" to="/admin/videos">Видео</b-nav-item>
          </ul>
          <div class="tab-content">
            <nuxt-child />
          </div>
        </div>
      </div>
    </client-only>
  </div>
</template>

<script>
export default {
  auth: true,
  asyncData({app, error}) {
    if (!app.$auth.hasScope('admin')) {
      return error({statusCode: 404, message: 'Страница не найдена'})
    }
    return {}
  },
  created() {
    this.$app.header_image.set(false)
  },
}
</script>

<style lang="scss">
@import '../../node_modules/bootstrap-vue/dist/bootstrap-vue.min.css';
@import '~assets/scss/variables';

#admin {
  .form-control,
  .custom-select,
  .input-group-text {
    height: auto;
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

        &:active,
        &:hover,
        &:focus,
        &.autosuggest__results-item--highlighted {
          background-color: $lightestGray;
        }
      }
    }
  }

  .user-cell {
    display: flex;
    align-items: center;
    img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-right: 10px;
    }
    /*span {
        padding-right: 60px;
      }*/
  }

  .small {
    font-size: 80%;
  }

  blockquote {
    font-weight: 200;
    padding-left: 20px;
    border-left: 2px solid gray;
  }
}
</style>
