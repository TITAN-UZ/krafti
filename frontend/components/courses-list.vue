<template>
  <div class="row">
    <div :class="'mb-3 mb-md-0 course ' + (courses.length == 1 ? 'big' : 'small')" v-for="course in courses">
      <nuxt-link :to="{name: 'courses-cid', params: {cid: course.id}}"
                 :style="{'background-image': (course.cover ? 'url(' + course.cover + ')' : false)}">
        <div class="d-flex flex-column justify-content-between h-100">
          <div class="mt-3">
            <div class="title">{{course.title}}</div>
            <div class="tagline mt-3">{{course.tagline}}</div>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div class="price">от {{course.price['3'] | number}} р.</div>

            <b-spinner small type="grow" v-if="loading == course.id"/>
            <fa :icon="['fal', 'heart']" @click.prevent="addFavorite(course.id)" v-else-if="$auth.user && !$auth.user.favorites.includes(course.id)"/>
            <fa :icon="['fas', 'heart']" @click.prevent="deleteFavorite(course.id)" v-else-if="$auth.user"/>
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
        data() {
            return {
                loading: false,
            }
        },
        props: {
            courses: {
                type: Array,
                required: true,
            }
        },
        methods: {
            addFavorite(id) {
                this.loading = id;
                this.$axios.put('user/favorite', {course_id: id})
                    .then(res => {
                        this.loading = false;
                        this.$auth.setUser(res.data.user);
                    })
                    .catch(() => {
                        this.loading = false;
                    })
            },
            deleteFavorite(id) {
                this.loading = id;
                this.$axios.delete('user/favorite', {params: {course_id: id}})
                    .then(res => {
                        this.loading = false;
                        this.$auth.setUser(res.data.user);
                    })
                    .catch(() => {
                        this.loading = false;
                    })
            },
        },
        created() {
            this.$fa.add(faHeartSolid, faHeartLight)
        }
    }
</script>
