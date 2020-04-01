<template>
  <div :id="'comment-' + comment.id" class="message__item">
    <div class="">
      <div
        :class="{
          'd-flex': true,
          'comment-body': true,
          owner: comment.user.id == $auth.user.id,
          review: comment.review,
          deleted: comment.deleted,
        }"
      >
        <div class="wrap mr-2">
          <img v-if="comment.user.photo" class="message__item--photo rounded-circle" :src="comment.user.photo" />
          <fa v-else :icon="['fad', 'user-circle']" size="3x" style="--fa-primary-color: #fff" class="message__item--photo" />
        </div>
        <div class="media-body">
          <div class="media-body-top d-flex align-items-center justify-content-between">
            <h4 class="message__item--title mt-0">{{ comment.user.fullname }}</h4>
            <span class="days_ago">{{ comment.created_at | dateago }}</span>
          </div>
          <div class="media-body-bottom d-flex align-items-center justify-content-between">
            <div class="message__item--info">{{ comment.text }}</div>
          </div>
        </div>
      </div>
      <div class="d-flex align-items-center">
        <a v-if="parent != comment.id && comment.deleted !== true" class="btn__answer" @click="onReply(comment.id)">Ответить</a>
        <span v-if="parent != comment.id && $auth.user.scope.includes('comments')" class="ml-auto comment-admin">
          <fa v-if="comment.deleted === false && comment.review === false" :icon="['fal', 'star']" class="text-success" @click="onReview(comment)" />
          <fa v-if="comment.deleted === false && comment.review === true" :icon="['fad', 'star']" class="text-success" @click="unReview(comment)" />
          &nbsp;
          <fa v-if="comment.deleted === false" :icon="['fad', 'times']" class="text-danger" @click="onDelete(comment)" />
          <fa v-if="comment.deleted === true" :icon="['fad', 'redo']" class="text-success" @click="onRestore(comment)" />
        </span>
      </div>
    </div>
    <div v-if="parent == comment.id" class="d-flex flex-column message__item ml-md-5 ml-2">
      <div class="comment-wrapper d-flex align-items-center justify-content-center">
        <div class="wrap mr-2">
          <img v-if="$auth.user.photo" class="message__item--photo rounded-circle" :src="$auth.user.photo" />
        </div>
        <div class="w-100 d-flex align-items-center justify-content-between comment-form">
          <b-textarea v-model="text" class="message__item--info" placeholder="Оставьте комментарий" autofocus></b-textarea>
          <div class="comment-buttons">
            <fa :icon="['fad', 'times-circle']" class="cancel" @click="onCancel" />
            <fa :icon="['fad', 'paper-plane']" class="send" @click="onSubmit" />
          </div>
        </div>
      </div>
    </div>
    <div v-if="comment.children.length" class="comment-children ml-md-5 ml-2">
      <comment
        v-for="child in comment.children"
        :key="child.id"
        v-model="text"
        :comment="child"
        :parent-id.sync="parent"
        @submit="onSubmit"
        @delete="onDelete"
        @restore="onRestore"
        @review="onReview"
        @unreview="unReview"
      />
    </div>
  </div>
</template>

<script>
import {faStar} from '@fortawesome/pro-light-svg-icons'
import {faPaperPlane, faTimesCircle, faRedo, faTimes, faStar as faReview, faUserCircle} from '@fortawesome/pro-duotone-svg-icons'

export default {
  name: 'Comment',
  props: {
    parentId: {
      type: Number,
      required: false,
      default: 0,
    },
    value: {
      type: String,
      default: '',
    },
    comment: {
      type: Object,
      required: true,
      default() {
        return {
          id: 0,
          parentId: 0,
          text: '',
          created_at: null,
          user: {
            id: 0,
            photo: null,
            fullname: '',
          },
        }
      },
    },
  },
  computed: {
    parent: {
      get() {
        return this.parentId
      },
      set(newValue) {
        this.$emit('update:parentId', newValue)
      },
    },
    text: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },
  created() {
    this.$fa.add(faPaperPlane, faTimesCircle, faTimes, faRedo, faStar, faReview, faUserCircle)
  },
  methods: {
    onReply(id) {
      this.parent = id
    },
    onCancel() {
      this.text = ''
      this.parent = 0
    },
    onSubmit() {
      this.$emit('submit', {
        parentId: this.parentId,
        text: this.text,
      })
    },
    onDelete(item) {
      item.deleted = true
      this.$emit('delete', item)
    },
    onRestore(item) {
      item.deleted = false
      this.$emit('restore', item)
    },
    onReview(item) {
      item.review = true
      this.$emit('review', item)
    },
    unReview(item) {
      item.review = false
      this.$emit('unreview', item)
    },
  },
}
</script>
