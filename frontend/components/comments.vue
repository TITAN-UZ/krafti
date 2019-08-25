<template>
  <div class="lesson-comments" style="max-height: 600px; overflow: auto">
    <div class="s-title">Комментарии ({{total}})</div>
    <div class="comments__content">
      <!-- Form -->
      <div class="media message__item d-flex align-items-center justify-content-center" v-if="parent_id == 0">
        <div class="wrap mr-2">
          <img class="message__item--photo rounded-circle" :src="$auth.user.photo"/>
        </div>
        <div class="w-100 d-flex align-items-center justify-content-between comment-form">
          <b-textarea class="message__item--info" placeholder="Оставьте комментарий" v-model="text"></b-textarea>
          <div class="comment-buttons">
            <fa :icon="['fad', 'paper-plane']" class="send" @click="onSubmit"/>
          </div>
        </div>
      </div>

      <!-- Thread -->
      <comment
        v-for="comment in comments"
        :key="comment.id"
        :comment="comment"
        :parent_id.sync="parent_id"
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
    import Comment from './_comment'
    import {faPaperPlane} from '@fortawesome/pro-duotone-svg-icons'

    export default {
        name: 'comments',
        data() {
            return {
                parent_id: 0,
                text: '',
                total: 0,
                comments: [],
            }
        },
        watch: {
            lesson_id() {
                this.loadComments()
            }
        },
        components: {Comment},
        props: {
            course_id: {
                type: Number,
                required: true,
            },
            lesson_id: {
                type: Number,
                required: true,
            },
        },
        methods: {
            loadComments() {
                this.$axios.get('web/course/comments', {
                    params: {
                        lesson_id: this.lesson_id,
                        course_id: this.course_id,
                        limit: 1000,
                    }
                }).then(res => {
                    this.total = res.data.total;
                    this.comments = this.prepareComments(res.data.rows);
                })
            },
            prepareComments(list) {
                let map = {}, node, roots = [], i;
                for (i = 0; i < list.length; i++) {
                    map[list[i].id] = i;
                    list[i].children = [];
                }
                for (i = 0; i < list.length; i++) {
                    node = list[i];
                    if (node.parent_id !== 0 && map[node.parent_id] !== undefined) {
                        list[map[node.parent_id]].children.push(node);
                    } else {
                        roots.push(node);
                    }
                }

                const flatten = function flatten(xs) {
                    return xs.reduce((acc, x) => {
                        acc = acc.concat(x);
                        if (x.children) {
                            acc = acc.concat(flatten(x.children));
                            x.children = [];
                        }
                        return acc;
                    }, []);
                };

                roots = roots.map(v => {
                    v.children = flatten(v.children);
                    return v;
                });

                return roots.reverse();
            },
            onCancel() {
                this.text = '';
                this.parent_id = 0;
            },
            onSubmit() {
                if (this.text == '') {
                    return;
                }
                this.$axios.put('web/course/comments', {
                    course_id: this.course_id,
                    lesson_id: this.lesson_id,
                    parent_id: this.parent_id,
                    text: this.text,
                }).then(() => {
                    this.onCancel();
                    this.loadComments()
                }).catch(() => {
                })
            },
            onDelete(item) {
                this.$axios.patch('web/course/comments', {
                    course_id: this.course_id,
                    lesson_id: this.lesson_id,
                    id: item.id,
                    deleted: true,
                })
            },
            onRestore(item) {
                this.$axios.patch('web/course/comments', {
                    course_id: this.course_id,
                    lesson_id: this.lesson_id,
                    id: item.id,
                    deleted: false,
                })
            },
            onReview(item) {
                this.$axios.patch('web/course/comments', {
                    course_id: this.course_id,
                    lesson_id: this.lesson_id,
                    id: item.id,
                    review: true,
                })
            },
            unReview(item) {
                this.$axios.patch('web/course/comments', {
                    course_id: this.course_id,
                    lesson_id: this.lesson_id,
                    id: item.id,
                    review: false,
                })
            }
        },
        mounted() {
            this.loadComments()
        },
        created() {
            this.$fa.add(faPaperPlane)
        }
    }

</script>
