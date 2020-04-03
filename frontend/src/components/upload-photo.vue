<template>
  <div class="upload-avatar-wrapper">
    <file-pond
      ref="filepond"
      accepted-file-types="image/jpeg, image/png"
      :allow-multiple="false"
      :class-name="'upload-avatar size-' + size"
      :label-idle="showLabel || !$auth.user.photo ? faCameraAlt : ' '"
      :image-preview-height="size"
      :image-resize-target-width="600"
      :image-resize-target-height="600"
      image-resize-mode="contain"
      :image-resize-upscale="false"
      :server="{process: handleUpload}"
      :instant-upload="true"
      :allow-drop="true"
      image-crop-aspect-ratio="1:1"
      style-panel-layout="compact circle"
      style-load-indicator-position="center bottom"
      style-progress-indicator-position="center bottom"
      style-button-process-item-position="center bottom"
      style-button-remove-item-position="center bottom"
    />
    <img class="avatar" :style="`width: ${size}px; height: ${size}px`" :src="myValue || placeholder" />
    <!--<img class="avatar" alt="" :style="{width: size + 'px', height: size + 'px'}" :src="photo || 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='" />-->
  </div>
</template>

<script>
import {icon} from '@fortawesome/fontawesome-svg-core'
import {faCameraAlt} from '@fortawesome/pro-duotone-svg-icons'

export default {
  name: 'UploadPhoto',
  props: {
    showLabel: {
      type: Boolean,
      required: false,
      default: true,
    },
    size: {
      type: Number,
      required: false,
      default: 150,
    },
    userId: {
      type: Number,
      required: false,
      default: null,
    },
    value: {
      type: Object,
      required: false,
      default() {
        return {}
      },
    },
  },
  data() {
    return {
      faCameraAlt: icon(faCameraAlt, {transform: {size: this.size / 3}}).html[0],
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
      return this.$auth.user && this.$auth.user.photo
        ? this.$image(this.$auth.user.photo, `${this.size}x${this.size}`, 'resize')
        : 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='
    },
  },
  methods: {
    handleUpload(fieldName, file, metadata, load, error, progress, abort) {
      const formData = new FormData()
      metadata.type = 'photo'
      formData.append('metadata', JSON.stringify(metadata))
      formData.append('file', file, file.name)
      if (this.userId) {
        formData.append('user_id', this.userId)
      }

      this.$axios({
        method: 'POST',
        url: !this.userId ? 'user/picture' : 'admin/users',
        data: formData,
        onUploadProgress: (e) => {
          progress(e.lengthComputable, e.loaded, e.total)
        },
      })
        .then((res) => {
          load(file)
          this.$refs.filepond.removeFile()
          if (!this.userId) {
            this.$auth.setUser(res.data.user)
          } else {
            this.photo = res.data.photo
          }
        })
        .catch(() => {
          error(file)
        })

      return {
        abort,
      }
    },
  },
}
</script>

<style lang="scss">
.upload-avatar-wrapper {
  .avatar {
    display: block;
    margin: auto;
    border-radius: 50%;
    box-shadow: 0 0.125rem 0.25rem rgba(#000, 0.075);
  }
}

.upload-avatar {
  position: absolute;
  left: 50%;

  &.size-88 {
    width: 88px;
    height: 88px;
    margin-left: -44px;
  }

  &.size-150 {
    width: 150px;
    height: 150px;
    margin-left: -75px;
  }

  .filepond--panel-root {
    background-color: transparent;
  }

  .filepond--drop-label {
    label {
      cursor: pointer;
    }

    svg {
      color: #fff;
      filter: drop-shadow(0 0 5px rgba(0, 0, 0, 0.5));
      opacity: 0.5;
    }

    &:hover {
      svg {
        opacity: 1;
      }
    }
  }
}
</style>
