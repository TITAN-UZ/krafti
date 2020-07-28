<template>
  <b-dropdown variant="default" no-caret right>
    <template slot="button-content">
      <fa :icon="['fad', 'share']" size="2x" class="text-primary" />
    </template>
    <social-sharing :url="siteUrl + 'courses/' + $route.params.cid" inline-template>
      <div>
        <b-dropdown-item>
          <network network="facebook">
            <fa :icon="['fab', 'facebook']" class="mr-2" style="color:#3b5998" />
            Facebook
          </network>
        </b-dropdown-item>
        <b-dropdown-item>
          <network network="vk">
            <fa :icon="['fab', 'vk']" class="mr-2" style="color:#4c75a3" />
            Вконтакте
          </network>
        </b-dropdown-item>
        <b-dropdown-item>
          <network network="pinterest">
            <fa :icon="['fab', 'pinterest']" class="mr-2" style="color:#bd081c" />
            Pinterest
          </network>
        </b-dropdown-item>
        <b-dropdown-item>
          <network network="twitter">
            <fa :icon="['fab', 'twitter']" class="mr-2" style="color:#55acee" />
            Twitter
          </network>
        </b-dropdown-item>
      </div>
    </social-sharing>
  </b-dropdown>
</template>

<script>
import SocialSharing from 'vue-social-sharing'

export default {
  name: 'CourseShare',
  components: {SocialSharing},
  data() {
    return {
      siteUrl: process.env.SITE_URL,
    }
  },
}
</script>
