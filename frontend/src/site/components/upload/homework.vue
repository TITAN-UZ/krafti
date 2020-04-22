<template>
  <div class="upload-homework-wrapper">
    <file-pond
      ref="filepond"
      accepted-file-types="image/jpeg, image/png"
      :class-name="'upload-homework size-' + size"
      :allow-multiple="false"
      :label-idle="faCameraAlt"
      :image-preview-height="size == 500 ? 300 : 150"
      :image-resize-target-width="1920"
      :image-resize-target-height="1080"
      image-resize-mode="contain"
      :image-resize-upscale="false"
      :server="{process: handleUpload}"
      :instant-upload="true"
      :allow-drop="true"
      label-file-loading="Подготовка"
      label-file-processing="Загрузка"
      label-tap-to-cancel="Отмена"
      label-file-waiting-for-size="Ожидание размера"
    />
    <div v-if="myValue.file" class="uploaded">
      <div>Вы уже отправили нам вот эту работу</div>
      <b-img-lazy :src="$image(myValue.file, `${size}x${size}`, 'resize')" alt="" />
    </div>
  </div>
</template>

<script>
import {icon} from '@fortawesome/fontawesome-svg-core'
import {faCameraAlt} from '@fortawesome/pro-duotone-svg-icons'

export default {
  name: 'UploadHomework',
  props: {
    value: {
      type: Object,
      default() {
        return {}
      },
    },
    courseId: {
      type: Number,
      required: true,
    },
    lessonId: {
      type: Number,
      required: false,
      default: null,
    },
    section: {
      type: Number,
      required: true,
    },
    image: {
      type: Object,
      default() {
        return {}
      },
    },
    size: {
      type: Number,
      required: false,
      default: 500,
    },
  },
  data() {
    return {
      faCameraAlt: icon(faCameraAlt, {transform: {size: 36}}).html[0],
    }
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
    async handleUpload(fieldName, file, metadata, load, error, progress, abort) {
      const formData = new FormData()
      metadata.type = 'photo'
      formData.append('file', file, file.name)
      formData.append('metadata', JSON.stringify(metadata))
      formData.append('course_id', String(this.courseId))
      formData.append('lesson_id', String(this.lessonId))
      formData.append('section', String(this.section))

      const {data: res} = await this.$axios({
        method: 'POST',
        url: '/user/homeworks',
        data: formData,
        onUploadProgress: (e) => {
          progress(e.lengthComputable, e.loaded, e.total)
        },
      })
      load(res.file)
      this.myValue = res.homework

      this.$refs.filepond.removeFile()
      this.$store.commit('courses/progress', {id: this.courseId, data: res.progress})
      this.$notify.success({message: 'Ваша работа успешно загружена!'})

      return {abort}
    },
  },
}
</script>

<style lang="scss">
.upload-homework-wrapper {
  max-width: 500px;
  .uploaded {
    text-align: center;
    font-weight: 200;
    img {
      max-width: 100%;
      border-radius: 10px;
    }
  }
  .upload-homework {
    margin: auto;
    min-width: 250px;
    width: 100%;
    &.size-500 {
      margin-bottom: 50px;

      @media (min-width: 576px) {
        width: 500px;
      }
    }
  }
}
</style>
