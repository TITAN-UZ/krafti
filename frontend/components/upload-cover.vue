<template>
  <div>
    <file-pond
      ref="filepond"
      acceptedFileTypes="image/jpeg, image/png"
      className="upload-cover"
      labelIdle="Нажмите для загрузки"
      :allow-multiple="false"
      :instantUpload="false"
      :allowDrop="true"
      :imageResizeTargetWidth="1920"
      :imageResizeTargetHeight="1080"
      :imageResizeUpscale="false"
      :imagePreviewMaxHeight="300"
      :imagePreviewHeight="176"
      labelFileLoading="Подготовка"
      labelFileProcessing="Загрузка"
      labelTapToCancel="Отмена"
      labelFileWaitingForSize="Ожидание размера"
      stylePanelLayout="integrated"
      @addfile="addFile"
      @removefile="removeFile"/>
    <div class="upload-cover-image">
      <img alt="" height="176" :src="label"/>
    </div>
  </div>
</template>

<script>
    export default {
        name: 'upload-cover',
        data() {
            return {}
        },
        props: {
            value: {
                type: Object,
                required: false,
                default: {},
            },
            label: {
                type: String,
                required: false,
                default: 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==',
            },
        },
        computed: {
            myValue: {
                get() {
                    return this.value
                },
                set(newValue) {
                    this.$emit('input', newValue)
                }
            }
        },
        methods: {
            addFile(e, file) {
                this.myValue = {
                    id: file.id,
                    metadata: file.getMetadata(),
                    file: file.getFileEncodeDataURL(),
                };
            },
            removeFile(e, file) {
                this.myValue = null;
            },
        }
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
