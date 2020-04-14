<template>
  <div class="lesson-comments">
    <div class="s-title">Комментарии ({{ total }})</div>
    <div class="comments__content">
      <!-- Form -->
      <comments-form
        v-if="!readOnly && $auth.loggedIn && parentId == 0"
        v-model="text"
        :show-cancel="false"
        @onSubmit="onSubmit"
      />

      <!-- Thread -->
      <comments-item
        v-for="comment in comments"
        :key="comment.id"
        :comment="comment"
        :parent-id.sync="parentId"
        @delete="onDelete"
        @restore="onRestore"
        @review="onReview"
        @unreview="unReview"
      >
        <template v-slot:actions="{comment: child}">
          <div v-if="!readOnly && isUser && parentId !== child.id" class="d-flex align-items-center mt-1">
            <a v-if="child.deleted !== true" class="btn__answer" @click="onReply(child.id)">Ответить</a>
            <div v-if="isAdmin" class="d-flex ml-auto align-items-center">
              <div v-if="!child.deleted" class="mr-2">
                <fa
                  v-if="child.review === false"
                  :icon="['fal', 'star']"
                  class="text-success"
                  @click="onReview(child)"
                />
                <fa v-else :icon="['fas', 'star']" class="text-success" @click="unReview(child)" />
              </div>
              <fa v-if="!child.deleted" :icon="['fas', 'times']" class="text-danger" @click="onDelete(child)" />
              <fa v-else :icon="['fas', 'redo']" class="text-success" @click="onRestore(child)" />
            </div>
          </div>
        </template>

        <template v-if="$auth.loggedIn" v-slot:form="{comment: child}">
          <comments-form
            v-if="parentId == child.id"
            v-model="text"
            :autofocus="true"
            class="ml-md-5 ml-2"
            @onCancel="onCancel"
            @onSubmit="onSubmit"
          />
        </template>
      </comments-item>
    </div>
  </div>
</template>

<script>
import CommentsItem from './item'
import CommentsForm from './form'

export default {
  name: 'CommentsList',
  components: {CommentsForm, CommentsItem},
  props: {
    courseId: {
      type: Number,
      required: true,
    },
    lessonId: {
      type: Number,
      required: true,
    },
    url: {
      type: String,
      required: false,
      default: 'web/course/comments',
    },
    readOnly: {
      type: Boolean,
      default: false,
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
  computed: {
    isUser() {
      return this.$auth.loggedIn
    },
    isAdmin() {
      return this.$auth.loggedIn && this.$auth.hasScope('comments')
    },
  },
  watch: {
    lesson_id() {
      this.loadComments()
    },
  },
  mounted() {
    this.loadComments()
  },
  methods: {
    async loadComments() {
      try {
        const {data: res} = await this.$axios.get(this.url, {
          params: {lesson_id: this.lessonId, course_id: this.courseId, limit: 1000},
        })
        this.total = res.total
        this.comments = this.prepareComments(res.rows)
      } catch (e) {
        console.error(e)
      }
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
    async onSubmit() {
      if (!this.text || !this.text.length) {
        return
      }
      try {
        await this.$axios.put(this.url, {
          course_id: this.courseId,
          lesson_id: this.lessonId,
          parent_id: this.parentId,
          text: this.text,
        })
        this.onCancel()
        this.loadComments()
      } catch (e) {
        console.error(e)
      }
    },
    onReply(id) {
      this.parentId = id
    },
    async onDelete(item) {
      item.deleted = true
      await this.$axios.patch(this.url, {
        course_id: this.courseId,
        lesson_id: this.lessonId,
        id: item.id,
        deleted: true,
      })
    },
    async onRestore(item) {
      item.deleted = false
      await this.$axios.patch(this.url, {
        course_id: this.courseId,
        lesson_id: this.lessonId,
        id: item.id,
        deleted: false,
      })
    },
    async onReview(item) {
      item.review = true
      await this.$axios.patch(this.url, {course_id: this.courseId, lesson_id: this.lessonId, id: item.id, review: true})
    },
    async unReview(item) {
      item.review = false
      await this.$axios.patch(this.url, {
        course_id: this.courseId,
        lesson_id: this.lessonId,
        id: item.id,
        review: false,
      })
    },
  },
}
</script>
