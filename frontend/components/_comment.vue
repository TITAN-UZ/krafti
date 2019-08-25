<template>
  <div class="message__item">
    <div class="">
      <div :class="{'d-flex': true, 'comment-body': true, owner: comment.user.id == $auth.user.id, review: comment.review, deleted: comment.deleted}">
        <div class="wrap mr-2">
          <img class="message__item--photo rounded-circle" :src="comment.user.photo" v-if="comment.user.photo">
          <fa :icon="['fad', 'user-circle']" size="3x"
              style="--fa-primary-color: #fff"
              class="message__item--photo" v-else/>
        </div>
        <div class="media-body">
          <div class="media-body-top d-flex align-items-center justify-content-between">
            <h4 class="message__item--title mt-0">{{comment.user.fullname}}</h4>
            <span class="days_ago">{{comment.created_at | dateago}}</span>
          </div>
          <div class="media-body-bottom d-flex align-items-center justify-content-between">
            <div class="message__item--info">{{comment.text}}
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex align-items-center">
        <a class="btn__answer" @click="onReply(comment.id)" v-if="parent != comment.id && comment.deleted !== true">Ответить</a>
        <span v-if="parent != comment.id && $auth.user.scope.includes('comments')" class="ml-auto comment-admin">
          <fa :icon="['fal', 'star']" class="text-success"
             v-if="comment.deleted === false && comment.review === false"
             @click="onReview(comment)"/>
          <fa :icon="['fad', 'star']" class="text-success"
             v-if="comment.deleted === false && comment.review === true"
             @click="unReview(comment)"/>
          &nbsp;
            <fa :icon="['fad', 'times']" class="text-danger"
               v-if="comment.deleted === false"
               @click="onDelete(comment)"/>
            <fa :icon="['fad', 'redo']" class="text-success"
               v-if="comment.deleted === true"
               @click="onRestore(comment)"/>
          </span>
      </div>
    </div>
    <div class="d-flex flex-column message__item ml-md-5 ml-2" v-if="parent == comment.id">
      <div class="comment-wrapper d-flex align-items-center justify-content-center">
        <div class="wrap mr-2">
          <img class="message__item--photo rounded-circle" :src="$auth.user.photo" v-if="$auth.user.photo"/>
        </div>
        <div class="w-100 d-flex align-items-center justify-content-between comment-form">
          <b-textarea
            class="message__item--info"
            placeholder="Оставьте комментарий"
            v-model="text"
            autofocus></b-textarea>
          <div class="comment-buttons">
            <fa :icon="['fad', 'times-circle']" class="cancel" @click="onCancel"/>
            <fa :icon="['fad', 'paper-plane']" class="send" @click="onSubmit"/>
          </div>
        </div>
      </div>
    </div>
    <div class="comment-children ml-md-5 ml-2" v-if="comment.children.length">
      <comment
        v-for="child in comment.children"
        :key="child.id"
        :comment="child"
        :parent_id.sync="parent"
        v-model="text"
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
        name: 'comment',
        props: {
            parent_id: {
                type: Number,
                required: false,
                default: 0,
            },
            value: String,
            comment: {
                type: Object,
                required: true,
                default() {
                    return {
                        id: 0,
                        parent_id: 0,
                        text: '',
                        created_at: null,
                        user: {
                            id: 0,
                            photo: null,
                            fullname: '',
                        }
                    }
                }
            },
        },
        computed: {
            parent: {
                get() {
                    return this.parent_id
                },
                set(newValue) {
                    this.$emit('update:parent_id', newValue)
                }
            },
            text: {
                get() {
                    return this.value
                },
                set(newValue) {
                    this.$emit('input', newValue);
                }
            }
        },
        methods: {
            onReply(id) {
                this.parent = id;
            },
            onCancel() {
                this.text = '';
                this.parent = 0;
            },
            onSubmit() {
                this.$emit('submit', {
                    parent_id: this.parent_id,
                    text: this.text,
                });
            },
            onDelete(item) {
                item.deleted = true;
                this.$emit('delete', item);
            },
            onRestore(item) {
                item.deleted = false;
                this.$emit('restore', item);
            },
            onReview(item) {
                item.review = true;
                this.$emit('review', item);
            },
            unReview(item) {
                item.review = false;
                this.$emit('unreview', item);
            }
        },
        created() {
            this.$fa.add(faPaperPlane, faTimesCircle, faTimes, faRedo, faStar, faReview, faUserCircle)
        }
    }
</script>
