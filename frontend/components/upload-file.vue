<template>
  <file-pond
    ref="filepond"
    _acceptedFileTypes="application/zip, application/pdf, image/jpeg, image/png"
    className="upload-file"
    :labelIdle="label"
    :allow-multiple="false"
    :instantUpload="false"
    :allowDrop="true"
    :allowImagePreview="false"
    :allowImageResize="false"
    :allowImageFilter="false"
    labelFileLoading="Подготовка"
    labelFileProcessing="Загрузка"
    labelTapToCancel="Отмена"
    labelFileWaitingForSize="Ожидание размера"
    stylePanelLayout="integrated"
    @addfile="addFile"
    @removefile="removeFile"/>
</template>

<script>

    export default {
        name: 'upload-file',
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
                default: 'Нажмите для загрузки',
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
  .upload-file {
    .filepond--file-info, .filepond--file-status {
      color: black;
    }
    .filepond--drop-label {
      border: 1px solid #dee2e6;
      border-radius: 10px;
      label {
        text-align: left;
      }
    }
  }
</style>
