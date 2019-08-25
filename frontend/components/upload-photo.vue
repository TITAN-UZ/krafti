<template>
  <div>
    <file-pond ref="filepond"
               accepted-file-types="image/jpeg, image/png"
               :allow-multiple="false"
               :className="'upload-avatar size-' + size"
               :labelIdle="showLabel || !$auth.user.photo ? faCameraAlt : ' '"
               :imagePreviewHeight="size"
               :imageResizeTargetWidth="600"
               :imageResizeTargetHeight="600"
               :server="{process: handleUpload}"
               :instantUpload="true"
               :allowDrop="true"
               imageCropAspectRatio="1:1"
               stylePanelLayout="compact circle"
               styleLoadIndicatorPosition="center bottom"
               styleProgressIndicatorPosition="center bottom"
               styleButtonProcessItemPosition="center bottom"
               styleButtonRemoveItemPosition="center bottom"/>
    <img class="avatar" alt="" :style="{width: size + 'px', height: size + 'px'}"
         :src="$auth.user.photo || 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='"/>
  </div>
</template>

<script>
    import {icon} from '@fortawesome/fontawesome-svg-core'
    import {faCameraAlt} from '@fortawesome/pro-light-svg-icons'

    export default {
        name: 'upload-photo',
        data() {
            return {
                faCameraAlt: icon(faCameraAlt, {transform: {size: this.size / 3}}).html[0],
            }
        },
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
        },
        methods: {
            handleUpload(fieldName, file, metadata, load, error, progress, abort) {
                const formData = new FormData();
                metadata.type = 'photo';
                formData.append('metadata', JSON.stringify(metadata));
                formData.append('file', file, file.name);

                this.$axios({
                    method: 'POST',
                    url: '/user/picture',
                    data: formData,
                    onUploadProgress: (e) => {
                        progress(e.lengthComputable, e.loaded, e.total)
                    }
                }).then((res) => {
                    load(res.data.file);
                    this.$refs.filepond.removeFile();
                    this.$auth.setUser(res.data.user);
                }).catch(() => {
                });

                return {
                    abort
                }
            },
        }
    }
</script>

<style lang="scss">
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
        filter: drop-shadow(0 0 5px rgba(0, 0, 0, .5));
        opacity: .5;
      }

      &:hover {
        svg {
          opacity: 1;
        }
      }
    }
  }
</style>
