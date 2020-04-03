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
