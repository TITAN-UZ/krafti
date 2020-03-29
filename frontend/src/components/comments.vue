<template>
  <div class="lesson-comments">
    <div class="s-title">Комментарии ({{ total }})</div>
    <div class="comments__content">
      <!-- Form -->
      <div v-if="parentId == 0" class="media message__item d-flex align-items-center justify-content-center">
        <div class="wrap mr-2">
          <img class="message__item--photo rounded-circle" :src="$auth.user.photo" />
        </div>
        <div class="w-100 d-flex align-items-center justify-content-between comment-form">
          <b-textarea v-model="text" class="message__item--info" placeholder="Оставьте комментарий"></b-textarea>
          <div class="comment-buttons">
            <fa :icon="['fad', 'paper-plane']" class="send" @click="onSubmit" />
          </div>
        </div>
      </div>

      <!-- Thread -->
      <comment
        v-for="comment in comments"
        :key="comment.id"
        v-model="text"
        :comment="comment"
        :parent-id.sync="parentId"
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
import {faPaperPlane} from '@fortawesome/pro-duotone-svg-icons'
import Comment from './_comment'

export default {
  name: 'Comments',
  components: {Comment},
  props: {
    courseId: {
      type: Number,
      required: true,
    },
    lessonId: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      parentId: 0,
      text: '',
      total: 0,
      comments: [],
    }
  },
  watch: {
    lesson_id() {
      this.loadComments()
    },
  },
  mounted() {
    this.loadComments()
  },
  created() {
    this.$fa.add(faPaperPlane)
  },
  methods: {
    loadComments() {
      this.$axios
        .get('web/course/comments', {
          params: {
            lesson_id: this.lessonId,
            course_id: this.courseId,
            limit: 1000,
          },
        })
        .then((res) => {
          this.total = res.data.total
          this.comments = this.prepareComments(res.data.rows)
        })
    },
    prepareComments(list) {
      const map = {}
      let node
      let roots = []
      let i
      for (i = 0; i < list.length; i++) {
        map[list[i].id] = i
        list[i].children = []
      }
      for (i = 0; i < list.length; i++) {
        node = list[i]
        if (node.parent_id !== 0 && map[node.parent_id] !== undefined) {
          list[map[node.parent_id]].children.push(node)
        } else {
          roots.push(node)
        }
      }

      const flatten = function flatten(xs) {
        return xs.reduce((acc, x) => {
          acc = acc.concat(x)
          if (x.children) {
            acc = acc.concat(flatten(x.children))
            x.children = []
          }
          return acc
        }, [])
      }

      roots = roots.map((v) => {
        v.children = flatten(v.children)
        return v
      })

      return roots.reverse()
    },
    onCancel() {
      this.text = ''
      this.parentId = 0
    },
    onSubmit() {
      if (this.text === '') {
        return
      }
      this.$axios
        .put('web/course/comments', {
          course_id: this.courseId,
          lesson_id: this.lessonId,
          parent_id: this.parentId,
          text: this.text,
        })
        .then(() => {
          this.onCancel()
          this.loadComments()
        })
        .catch(() => {})
    },
    onDelete(item) {
      this.$axios.patch('web/course/comments', {
        course_id: this.courseId,
        lesson_id: this.lessonId,
        id: item.id,
        deleted: true,
      })
    },
    onRestore(item) {
      this.$axios.patch('web/course/comments', {
        course_id: this.courseId,
        lesson_id: this.lessonId,
        id: item.id,
        deleted: false,
      })
    },
    onReview(item) {
      this.$axios.patch('web/course/comments', {
        course_id: this.courseId,
        lesson_id: this.lessonId,
        id: item.id,
        review: true,
      })
    },
    unReview(item) {
      this.$axios.patch('web/course/comments', {
        course_id: this.courseId,
        lesson_id: this.lessonId,
        id: item.id,
        review: false,
      })
    },
  },
}
</script>
