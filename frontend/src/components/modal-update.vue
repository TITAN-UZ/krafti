<script>
import ModalCreate from './modal-create'

export default {
  name: 'ModalUpdate',
  extends: ModalCreate,
  methods: {
    async onSubmit() {
      try {
        let record = JSON.parse(JSON.stringify(this.record))
        record = this.beforeSubmit(record)
        if (record === false) {
          return
        }
        this.loading = true
        const {data} = await this.$axios.patch(this.url, record)
        this.$refs.modal.hide()
        this.$root.$emit(`app::${this.name}::update`, data)
      } catch (err) {
      } finally {
        this.loading = false
      }
    },
  },
}
</script>
