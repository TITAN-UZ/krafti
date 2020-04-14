<script>
import FreeLesson from '../../../index/free'

export default {
  extends: FreeLesson,
  methods: {
    onHidden() {
      this.$router.push({name: 'courses-cid', params: {cid: this.record.course_id}})
    },
  },
}
</script>
