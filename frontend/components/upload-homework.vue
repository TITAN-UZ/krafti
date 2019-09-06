<template>
  <div class="upload-homework-wrapper">
    <file-pond
      ref="filepond"
      accepted-file-types="image/jpeg, image/png"
      :className="'upload-homework size-' + size"
      :allow-multiple="false"
      :labelIdle="faCameraAlt"
      :imagePreviewHeight="size == 500 ? 300 : 150"
      :imageResizeTargetWidth="3000"
      :imageResizeTargetHeight="1000"
      imageResizeMode="contain"
      :imageResizeUpscale="false"
      :server="{process: handleUpload}"
      :instantUpload="true"
      :allowDrop="true"
      labelFileLoading="Подготовка"
      labelFileProcessing="Загрузка"
      labelTapToCancel="Отмена"
      labelFileWaitingForSize="Ожидание размера"
    />
    <div class="uploaded" v-if="image != ''">
      <div>Вы уже отправили нам вот эту работу</div>
      <img :src="image"/>
    </div>
  </div>
</template>

<script>
    import {icon} from '@fortawesome/fontawesome-svg-core'
    import {faCameraAlt} from '@fortawesome/pro-duotone-svg-icons'

    export default {
        name: 'upload-homework',
        props: {
            course_id: {
                type: Number,
                required: true,
            },
            lesson_id: {
                type: Number,
                required: false,
                default: null,
            },
            section: {
                type: Number,
                required: true,
            },
            image: {
                type: String,
                required: false,
                default: '',
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
        methods: {
            handleUpload(fieldName, file, metadata, load, error, progress, abort) {
                const formData = new FormData();
                metadata.type = 'photo';
                formData.append('file', file, file.name);
                formData.append('metadata', JSON.stringify(metadata));
                formData.append('course_id', this.course_id);
                formData.append('lesson_id', this.lesson_id);
                formData.append('section', this.section);

                this.$axios({
                    method: 'POST',
                    url: '/user/homeworks',
                    data: formData,
                    onUploadProgress: (e) => {
                        progress(e.lengthComputable, e.loaded, e.total)
                    }
                }).then((res) => {
                    load(res.data.file);
                    this.$refs.filepond.removeFile();

                    this.$notify.success({message: 'Ваша работа успешно загружена!'});

                    if (this.lesson_id !== null && this.lesson_id > 0) {
                        this.$root.$emit('app::lesson' + this.lesson_id + '::homework', res.data.homeworks[0]);
                    } else {
                        let homeworks = {};
                        res.data.homeworks.forEach(v => {
                            homeworks[v.section] = v;
                        });
                        this.$root.$emit('app::course' + this.course_id + '::progress', res.data.progress);
                        this.$root.$emit('app::course' + this.course_id + '::homeworks', homeworks);
                    }
                }).catch(() => {
                });

                return {
                    abort
                }
            },
        },
        /*mounted() {
            this.setTimeout(() => {
                document.getElementsByClassName('upload-bg')[0].style.height = '500px';
            }, 10)
        }*/
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
