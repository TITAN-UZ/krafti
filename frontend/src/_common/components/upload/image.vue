<template>
  <div>
    <file-pond
      ref="filepond"
      accepted-file-types="image/jpeg, image/png"
      class-name="upload-image"
      :allow-multiple="false"
      :instant-upload="false"
      :allow-drop="true"
      :image-resize-target-width="maxHeight"
      :image-resize-target-height="maxWidth"
      :image-preview-max-height="300"
      :image-preview-height="200"
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
    <div class="upload-image-placeholder">
      <img alt="" height="200" :src="myValue || placeholder" />
    </div>
    <div v-if="label && Object.keys(label).length" class="text-center small mt-1">
      <a :href="$image(label)" target="_blank" rel="noreferrer">
        {{ label.title }}
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UploadImage',
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
        return Object.keys(this.value).length ? this.value : null
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
    placeholder() {
      return this.label && Object.keys(this.label).length
        ? this.$image(this.label, '400x400', 'resize')
        : 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='
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
.upload-image {
  z-index: 1050;
  min-height: 200px;
  cursor: pointer;

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

.upload-image-placeholder {
  z-index: 1040;
  margin-top: -200px;
  background: #222;

  img {
    display: block;
    margin: auto;
  }
}
</style>
