<template>
  <div :style="cssVars">
    <file-pond
      ref="filepond"
      accepted-file-types="image/jpeg, image/png"
      :allow-multiple="false"
      :instant-upload="false"
      :allow-drop="true"
      :image-resize-target-width="maxHeight"
      :image-resize-target-height="maxWidth"
      :image-preview-max-height="300"
      :image-preview-height="height"
      :image-resize-upscale="false"
      image-resize-mode="contain"
      :label-idle="`${!label || !Object.keys(label).length ? 'Нажмите для загрузки' : ''}`"
      label-file-loading="Подготовка"
      label-file-processing="Загрузка"
      label-tap-to-cancel="Отмена"
      label-file-waiting-for-size="Ожидание размера"
      style-panel-layout="integrated"
      @addfile="addFile"
      @removefile="removeFile"
    />
    <div class="upload-image-placeholder">
      <img alt="" :height="height" :src="myValue || placeholder" />
    </div>
    <div v-if="showInfo && label && Object.keys(label).length" class="text-center small mt-1">
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
    height: {
      type: [Number, String],
      default: 200,
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
    showInfo: {
      type: Boolean,
      default: true,
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
        ? this.$image(this.label, `${this.height * 2}x${this.height * 2}`, 'resize')
        : 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='
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
    .filepond--drop-label {
      height: var(--height);
      cursor: pointer;
      > label {
        color: $body-color;
        font-family: $font-family-base;
      }
    }
  }
  .upload-image-placeholder {
    margin-top: calc(var(--height) * -1);
    background: $body-bg;
    img {
      border-radius: $input-border-radius;
      max-width: 100%;
      display: block;
      margin: auto;
    }
  }
}
</style>
