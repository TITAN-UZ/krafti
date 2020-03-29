<template>
  <div>
    <file-pond
      ref="filepond"
      accepted-file-types="image/jpeg, image/png"
      class-name="upload-cover"
      :allow-multiple="false"
      :instant-upload="false"
      :allow-drop="true"
      :image-resize-target-width="maxHeight"
      :image-resize-target-height="maxWidth"
      :image-preview-max-height="300"
      :image-preview-height="176"
      :image-resize-upscale="false"
      image-resize-mode="contain"
      label-idle="Нажмите для загрузки"
      label-file-loading="Подготовка"
      label-file-processing="Загрузка"
      label-tap-to-cancel="Отмена"
      label-file-waiting-for-size="Ожидание размера"
      style-panel-layout="integrated"
      @addfile="addFile"
      @removefile="removeFile"
    />
    <div class="upload-cover-image">
      <img alt="" height="176" :src="label" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'UploadCover',
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
      default: 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==',
    },
    maxWidth: {
      type: Number,
      required: false,
      default: 1920,
    },
    maxHeight: {
      type: Number,
      required: false,
      default: 1080,
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
.upload-cover {
  z-index: 1050;
  min-height: 176px;

  .filepond--panel-root {
    //background: transparent;
  }

  .filepond--drop-label {
    > label {
      cursor: pointer;
      color: #fff;
    }
  }

  .filepond--action-process-item {
    display: none;
  }
}

.upload-cover-image {
  z-index: 1040;
  margin-top: -176px;
  background: #222;

  img {
    display: block;
    margin: auto;
  }
}
</style>
