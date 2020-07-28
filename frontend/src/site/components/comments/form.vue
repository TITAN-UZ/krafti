<template>
  <div v-if="$auth.user && $auth.loggedIn" class="d-flex flex-column message__item">
    <div class="comment-wrapper d-flex align-items-center justify-content-center">
      <div class="wrap mr-2">
        <user-avatar :user="$auth.user" :size="50" />
      </div>
      <div class="w-100 d-flex align-items-center justify-content-between comment-form">
        <b-textarea
          v-model="myValue"
          class="message__item--info"
          placeholder="Оставьте комментарий"
          :autofocus="autofocus"
        />
        <div class="comment-buttons">
          <fa v-if="showCancel" :icon="['fad', 'times-circle']" class="cancel" @click="onCancel" />
          <fa :icon="['fad', 'paper-plane']" class="send" @click="onSubmit" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CommentsForm',
  props: {
    value: {
      type: String,
      required: true,
    },
    autofocus: {
      type: Boolean,
      default: false,
    },
    showCancel: {
      type: Boolean,
      default: true,
    },
  },
  computed: {
    myValue: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },
  methods: {
    onCancel() {
      if (this.$listeners.onCancel) {
        this.$listeners.onCancel()
      }
    },
    onSubmit() {
      if (this.$listeners.onSubmit) {
        this.$listeners.onSubmit(this.myValue)
      }
    },
  },
}
</script>
