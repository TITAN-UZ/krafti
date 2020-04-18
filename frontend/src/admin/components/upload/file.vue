<template>
  <div :style="cssVars">
    <file-pond
      ref="filepond"
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
  </div>
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
    height: {
      type: [Number, String],
      default: 100,
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
    cssVars() {
      return {
        '--height': `${this.height}px`,
      }
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

<style scoped lang="scss">
div::v-deep {
  .filepond--wrapper {
    .filepond--root {
      border: $input-border-width solid $input-border-color;
      border-radius: $input-border-radius;
    }
    .filepond--file-info,
    .filepond--file-status {
      color: $body-color;
    }
    .filepond--drop-label {
      height: var(--height);
      cursor: pointer;
      > label {
        cursor: pointer;
        color: $body-color;
        font-family: $font-family-base;
        a {
          display: block;
          margin-bottom: 10px;
        }
      }
    }
  }
}
</style>
