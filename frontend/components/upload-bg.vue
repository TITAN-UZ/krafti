<template>
    <div class="wrapper__bg-shadow" :style="{'background-image': 'url(' + ($auth.user.background || bg) + ')'}">
        <file-pond ref="filepond"
                   accepted-file-types="image/jpeg, image/png"
                   className="upload-bg"
                   :allow-multiple="false"
                   :labelIdle="faCameraAlt"
                   :imagePreviewMinHeight="500"
                   :imagePreviewMaxHeight="500"
                   :imagePreviewHeight="500"
                   :imageResizeTargetWidth="3000"
                   :imageResizeTargetHeight="1000"
                   :imageResizeUpscale="false"
                   :server="{process: handleUpload}"
                   :instantUpload="true"
                   :allowDrop="true"
                   labelFileLoading="Подготовка"
                   labelFileProcessing="Загрузка"
                   labelTapToCancel="Отмена"
                   labelFileWaitingForSize="Ожидание размера"
                   stylePanelLayout="integrated"/>
    </div>
</template>

<script>
    import bg from '../assets/images/general/headline_photo.png';
    import {icon} from '@fortawesome/fontawesome-svg-core'
    import {faCameraAlt} from '@fortawesome/pro-light-svg-icons'

    export default {
        data() {
            return {
                bg: bg,
                faCameraAlt: icon(faCameraAlt, {transform: {size: 36}}).html[0],
            }
        },
        /*props: {
            showLabel: {
                type: Boolean,
                required: false,
                default: true,
            },
            size: {
                type: Number,
                required: false,
                default: '150',
            }
        },*/
        methods: {
            handleUpload(fieldName, file, metadata, load, error, progress, abort) {
                const formData = new FormData();
                metadata.type = 'photo';
                formData.append('metadata', JSON.stringify(metadata));
                formData.append('file', file, file.name);

                this.$axios({
                    method: 'POST',
                    url: '/user/background',
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
        },
        mounted() {
            this.setTimeout(() => {
                document.getElementsByClassName('upload-bg')[0].style.height = '500px';
            }, 10)
        }
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
                opacity: .5;
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
