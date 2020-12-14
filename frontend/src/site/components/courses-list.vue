<template>
  <div class="row">
    <div v-for="course in courses" :key="course.id" :class="'mb-3 course ' + (courses.length == 1 ? 'big' : 'small')">
      <nuxt-link
        :to="{name: 'courses-cid', params: {cid: course.id}}"
        :style="{'background-image': course.cover ? 'url(' + $image(course.cover) + ')' : false}"
      >
        <div class="d-flex flex-column justify-content-between h-100" @click="clickFacebook('ViewContent')">
          <div class="mt-3">
            <div class="title">
              <!--{{course.title}}-->
            </div>
            <div class="tagline mt-3">{{ course.tagline }}</div>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div class="price">
              <!--<span v-if="!course.lessons_count">Готовится к публикации</span>-->
              <span v-if="course.lessons_count && !course.bought"
                >от {{ course.price[[Object.keys(course.price)[0]]] | price(course.discount) | number }} р.</span
              >
              <span v-else-if="course.paid_till">Оплачен до {{ course.paid_till | date }}</span>
            </div>
            <b-spinner v-if="loading == course.id" small type="grow" />
            <fa
              v-else-if="$auth.loggedIn && !$auth.user.favorites.includes(course.id)"
              :icon="['fal', 'heart']"
              @click.prevent="addFavorite(course.id)"
            />
            <fa v-else-if="$auth.loggedIn" :icon="['fas', 'heart']" @click.prevent="deleteFavorite(course.id)" />
          </div>
        </div>
      </nuxt-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CoursesList',
  props: {
    courses: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
    }
  },
  methods: {
    addFavorite(id) {
      this.loading = id
      this.$axios
        .put('user/favorites', {course_id: id})
        .then((res) => {
          this.loading = false
          this.$auth.setUser(res.data.user)
        })
        .catch(() => {
          this.loading = false
        })
    },
    deleteFavorite(id) {
      this.loading = id
      this.$axios
        .delete('user/favorites', {params: {course_id: id}})
        .then((res) => {
          this.loading = false
          this.$auth.setUser(res.data.user)
        })
        .catch(() => {
          this.loading = false
        })
    },
    clickFacebook(track) {
      this.$fb.track(track)
    },
  },
}
</script>
