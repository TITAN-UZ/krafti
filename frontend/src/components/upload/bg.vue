<template>
  <div class="wrapper__bg-shadow" :style="`background-image: url('${placeholder}')`">
    <file-pond
      ref="filepond"
      accepted-file-types="image/jpeg, image/png"
      class-name="upload-bg"
      :allow-multiple="false"
      :label-idle="faCameraAlt"
      :image-preview-min-height="500"
      :image-preview-max-height="500"
      :image-preview-height="500"
      :image-resize-target-width="3000"
      :image-resize-target-height="1500"
      image-resize-mode="contain"
      :image-resize-upscale="false"
      :server="{process: handleUpload}"
      :instant-upload="true"
      :allow-drop="true"
      label-file-loading="Подготовка"
      label-file-processing="Загрузка"
      label-tap-to-cancel="Отмена"
      label-file-waiting-for-size="Ожидание размера"
      style-panel-layout="integrated"
    />
  </div>
</template>

<script>
import {icon} from '@fortawesome/fontawesome-svg-core'
import {faCameraAlt} from '@fortawesome/pro-duotone-svg-icons'
import bg from '../../assets/images/general/headline_photo.png'

export default {
  name: 'UploadBg',
  data() {
    return {
      faCameraAlt: icon(faCameraAlt, {transform: {size: 36}}).html[0],
      size: 500,
    }
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
      return this.$auth.user && this.$auth.user.background
        ? this.$image(this.$auth.user.background, `${this.size * 2}x${this.size}`, 'fit')
        : bg
    },
  },
  mounted() {
    this.setTimeout(() => {
      document.getElementsByClassName('upload-bg')[0].style.height = `${this.size}px`
    }, 10)
    console.log(this.placeholder)
  },
  methods: {
    handleUpload(fieldName, file, metadata, load, error, progress, abort) {
      const formData = new FormData()
      metadata.type = 'photo'
      formData.append('metadata', JSON.stringify(metadata))
      formData.append('file', file, file.name)

      this.$axios({
        method: 'POST',
        url: '/user/background',
        data: formData,
        onUploadProgress: (e) => {
          progress(e.lengthComputable, e.loaded, e.total)
        },
      })
        .then((res) => {
          load(res.data.file)
          this.$refs.filepond.removeFile()
          this.$auth.setUser(res.data.user)
        })
        .catch(() => {})

      return {
        abort,
      }
    },
  },
}
</script>

<style lang="scss">
.upload-bg {
  .filepond--drop-label {
    > label {
      position: absolute;
      right: 10px;
      bottom: 10px;
      color: #fff;
      opacity: 0.5;
    }
  }

  &:hover {
    .filepond--drop-label > label {
      opacity: 1;
    }
  }

  .filepond--image-clip {
    //width: 100% !important;
  }

  /*.filepond--file-info, .filepond--file-status {
        display: none;
    }*/
}

@media (max-width: 1024px) {
  .upload-bg {
    .filepond--drop-label > label {
      bottom: 40px;
    }
  }
}
</style>
