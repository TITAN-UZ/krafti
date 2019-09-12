<template>
  <div class="upload-avatar-wrapper">
    <file-pond ref="filepond"
               accepted-file-types="image/jpeg, image/png"
               :allow-multiple="false"
               :className="'upload-avatar size-' + size"
               :labelIdle="showLabel || !$auth.user.photo ? faCameraAlt : ' '"
               :imagePreviewHeight="size"
               :imageResizeTargetWidth="600"
               :imageResizeTargetHeight="600"
               imageResizeMode="contain"
               :imageResizeUpscale="false"
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
         :src="photo || 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='"/>
  </div>
</template>

<script>
    import {icon} from '@fortawesome/fontawesome-svg-core'
    import {faCameraAlt} from '@fortawesome/pro-duotone-svg-icons'

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
            userId: {
                type: Number,
                required: false,
                default: null,
            },
            value: {
                type: String,
                required: false,
                default() {
                    return this.$auth.user.photo
                },
            }
        },
        computed: {
            photo: {
                get() {
                    return !this.userId ? this.$auth.user.photo : this.value
                },
                set(newValue) {
                    this.$emit('input', newValue)
                }
            }
        },
        methods: {
            handleUpload(fieldName, file, metadata, load, error, progress, abort) {
                const formData = new FormData();
                metadata.type = 'photo';
                formData.append('metadata', JSON.stringify(metadata));
                formData.append('file', file, file.name);
                if (this.userId) {
                    formData.append('user_id', this.userId);
                }

                this.$axios({
                    method: 'POST',
                    url: !this.userId
                      ? 'user/picture'
                      : 'admin/users' ,
                    data: formData,
                    onUploadProgress: (e) => {
                        progress(e.lengthComputable, e.loaded, e.total)
                    }
                }).then((res) => {
                    load(file);
                    this.$refs.filepond.removeFile();
                    if (!this.userId) {
                        this.$auth.setUser(res.data.user)
                    } else {
                        this.photo = res.data.photo
                    }
                }).catch(() => {
                    error(file)
                });

                return {
                    abort
                }
            },
        }
    }
</script>

<style lang="scss">
  .upload-avatar-wrapper {
    .avatar {
      display: block;
      margin: auto;
      border-radius: 50%;
      box-shadow: 0 .125rem .25rem rgba(#000, .075);
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
