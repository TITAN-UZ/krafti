<template>
  <file-pond
    ref="filepond"
    -accepted-file-types="application/zip, application/pdf, image/jpeg, image/png"
    class-name="upload-file"
    :label-idle="label"
    :allow-multiple="false"
    :instant-upload="false"
    :allow-drop="true"
    :allow-image-preview="false"
    :allow-image-resize="false"
    :allow-image-filter="false"
    label-file-loading="Подготовка"
    label-file-processing="Загрузка"
    label-tap-to-cancel="Отмена"
    label-file-waiting-for-size="Ожидание размера"
    style-panel-layout="integrated"
    @addfile="addFile"
    @removefile="removeFile"
  />
</template>

<script>
export default {
  name: 'UploadFile',
  props: {
    value: {
      type: Object,
      required: false,
      default() {
        return {}
      },
    },
    label: {
      type: String,
      required: false,
      default: 'Нажмите для загрузки',
    },
  },
  data() {
    return {}
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
    addFile(e, file) {
      this.myValue = {
        id: file.id,
        metadata: file.getMetadata(),
        file: file.getFileEncodeDataURL(),
      }
    },
    removeFile(e, file) {
      this.myValue = null
    },
  },
}
</script>

<style lang="scss">
.upload-file {
  .filepond--file-info,
  .filepond--file-status {
    color: black;
  }
  .filepond--drop-label {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    label {
      text-align: left;
    }
  }
}
</style>
