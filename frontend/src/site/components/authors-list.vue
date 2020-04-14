<template>
  <div :class="rowClass">
    <div v-for="item in authors" :key="item.id" :class="itemClass">
      <div class="teacher__item d-flex flex-column justify-content-center align-items-center">
        <nuxt-link class="teacher__info--photo teacher__item--photo " :to="{name: 'team-id', params: {id: item.id}}">
          <img
            class="teacher-photo rounded-circle"
            :src="typeof item.photo === 'string' ? item.photo : $image(item.photo, '300x300', 'fit')"
            alt=""
          />
          <!--<span class="label__shape"></span>-->
        </nuxt-link>
        <h2 class="teacher__item--name text-center">{{ item.fullname }}</h2>
        <div class="teacher__item--position text-center">{{ item.company }}</div>
        <div v-if="showDesc" class="teacher__item--text text-center">{{ item.description }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AuthorsList',
  props: {
    authors: {
      type: Array,
      required: true,
    },
    rowClass: {
      type: String,
      required: false,
      // default: 'row item__wrap',
      default: 'row',
    },
    itemClass: {
      type: String,
      required: false,
      default: 'col-12 col-md-6 col-lg-4 mb-5',
    },
    showDesc: {
      type: Boolean,
      required: false,
      default: true,
    },
  },
}
</script>
