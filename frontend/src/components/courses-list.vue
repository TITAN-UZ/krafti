<template>
  <div class="row">
    <div v-for="course in courses" :key="course.id" :class="'mb-3 course ' + (courses.length == 1 ? 'big' : 'small')">
      <nuxt-link
        :to="{name: 'courses-cid', params: {cid: course.id}}"
        :style="{'background-image': course.cover ? 'url(' + course.cover + ')' : false}"
      >
        <div class="d-flex flex-column justify-content-between h-100">
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
                >от {{ course.price['3'] | price(course.discount) | number }} р.</span
              >
              <span v-else-if="course.paid_till">Оплачен до {{ course.paid_till | date }}</span>
            </div>
            <b-spinner v-if="loading == course.id" small type="grow" />
            <fa
              v-else-if="$auth.user && !$auth.user.favorites.includes(course.id)"
              :icon="['fal', 'heart']"
              @click.prevent="addFavorite(course.id)"
            />
            <fa v-else-if="$auth.user" :icon="['fas', 'heart']" @click.prevent="deleteFavorite(course.id)" />
          </div>
        </div>
      </nuxt-link>
    </div>
  </div>
</template>

<script>
import {faHeart as faHeartSolid} from '@fortawesome/pro-solid-svg-icons'
import {faHeart as faHeartLight} from '@fortawesome/pro-light-svg-icons'

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
  created() {
    this.$fa.add(faHeartSolid, faHeartLight)
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
  },
}
</script>
