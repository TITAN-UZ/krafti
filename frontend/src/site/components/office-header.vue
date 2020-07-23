<template>
  <div>
    <!--<div class="wrapper__bg-shadow" :style="style_bg">
            <form id="header-photo" action="">
                <label for="photo">
                    <input id="photo" type="file" name="photo">
                    <input type="submit" value="">
                </label>
            </form>
        </div>-->

    <upload-bg />

    <div class="mini-banner">
      <div class="learner-avatar">
        <!--<img :src="user.photo" v-if="user.photo"/>
                <nuxt-link :to="{name: 'profile-update'}" v-else>
                    <img src="~assets/images/general/ic_upload_avatar.svg"/>
                </nuxt-link>-->
        <upload-photo :size="88" :show-label="false" />
      </div>

      <div class="learner-info">
        <div class="top">
          <div>Привет,</div>
          <div class="name">{{ user.fullname }}</div>
        </div>

        <div class="footer d-flex align-items-center">
          <fa icon="star" style="color:goldenrod" />
          <!--<span class="ic__star-gold"></span>-->
          <span class="account ml-2">{{ user.account }} крафтиков</span>
          <nuxt-link :to="{name: 'profile-update'}" class="ml-auto">
            <fa icon="cog" />
          </nuxt-link>
          <nuxt-link :to="{name: 'profile-favorites'}" class="ml-2 ml-md-3">
            <fa icon="heart" />
          </nuxt-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {faCog, faHeart, faStar} from '@fortawesome/pro-solid-svg-icons'
import bg from '../assets/images/general/headline_photo.png'

export default {
  name: 'OfficeHeader',
  data() {
    return {
      style_bg: {'background-image': 'url(' + bg + ')'},
    }
  },
  computed: {
    user() {
      return this.$auth.user
    },
  },
  created() {
    this.$fa.add(faCog, faHeart, faStar)
  },
}
</script>
