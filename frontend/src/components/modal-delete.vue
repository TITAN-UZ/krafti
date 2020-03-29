<script>
import ModalCreate from './modal-create'

export default {
  name: 'ModalDelete',
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
        const {data} = await this.$axios.delete(this.url, {params: record})
        this.$refs.modal.hide()
        this.$root.$emit(`app::${this.name}::update`, data)
      } catch (err) {
        this.$message.error(err.response.data)
      } finally {
        this.loading = false
      }
    },
  },
}
</script>
