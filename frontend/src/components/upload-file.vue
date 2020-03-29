<template>
  <file-pond
    ref="filepond"
    class-name="upload-file"
    :accepted-file-types="acceptedFileTypes"
    :label-idle="placeholder"
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
      type: Object,
      required: false,
      default() {
        return {}
      },
    },
    acceptedFileTypes: {
      type: String,
      default: null,
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
    placeholder() {
      let message = ''
      if (this.label && Object.keys(this.label).length) {
        message += `<a href="${[this.$settings.file_url, this.label.id].join('/')}" target="_self">${
          this.label.title
        }</a>`
      }
      return message + 'Нажмите для загрузки'
    },
  },
  methods: {
    addFile(e, file) {
      const data = {
        id: file.id,
        metadata: file.getMetadata(),
        file: file.getFileEncodeDataURL(),
      }
      data.metadata.name = file.filename
      data.metadata.size = file.size
      data.metadata.type = file.type
      this.myValue = data
    },
    removeFile() {
      this.myValue = {}
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
    cursor: pointer;

    > label {
      cursor: pointer;

      a {
        display: block;
        margin-bottom: 10px;
      }
    }
  }
}
</style>
