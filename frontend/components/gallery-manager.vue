<template>
  <div class="gallery-manager">

    <file-pond
      ref="filepond"
      accepted-file-types="image/jpeg, image/png"
      className="upload-gallery"
      :allow-multiple="true"
      :labelIdle="faCameraAlt"
      :imagePreviewMaxHeight="200"
      :imageResizeTargetWidth="1920"
      :imageResizeTargetHeight="1080"
      imageResizeMode="contain"
      :imageResizeUpscale="false"
      :server="{process: handleUpload}"
      :instantUpload="true"
      :allowDrop="true"
      @processfile="onUpload"
      labelFileLoading="Подготовка"
      labelFileProcessing="Загрузка"
      labelTapToCancel="Отмена"
      labelFileWaitingForSize="Ожидание размера"
    />

    <div class="container text-center text-muted" v-if="items.length > 1">
      Вы можете сортировать картинки перетаскиванием
    </div>

    <draggable v-model="items" @end="onSort">
      <transition-group tag="div" name="list" class="gallery d-flex flex-wrap">
        <div v-for="item in items" :key="item.id" class="col-auto gallery-manager-item-wrapper">
          <div class="gallery-manager-item">
            <div :class="{image: true, disabled: !item.active}">
              <a :href="item.file" target="_blank">
                <img :src="[$settings.image_url, item.id, '200x200'].join('/')"/>
              </a>
              <div class="actions">
                <button class="btn btn-success" @click.prevent="onEnable(item)" v-if="!item.active" title="Включить">
                  <fa :icon="['fad', 'check-circle']"/>
                </button>
                <button class="btn btn-warning" @click.prevent="onDisable(item)" v-else title="Отключить">
                  <fa :icon="['fad', 'power-off']"/>
                </button>
                <button class="btn btn-danger" @click.prevent="onDelete(item)" title="Удалить">
                  <fa :icon="['fad', 'times-circle']"/>
                </button>
              </div>
            </div>
            <div class="title">
              {{item.title}}
            </div>
          </div>
        </div>
      </transition-group>
    </draggable>

    <div class="container text-center text-muted" v-if="items.length > 0">
      {{items.length}} {{items.length | noun('картинка|картинки|картинок')}}
    </div>

  </div>
</template>

<script>
    import Draggable from 'vuedraggable'
    import {icon} from '@fortawesome/fontawesome-svg-core'
    import {faCameraAlt, faTimesCircle, faPowerOff, faCheckCircle} from '@fortawesome/pro-duotone-svg-icons'

    export default {
        name: 'gallery-manager',
        data() {
            return {
                faCameraAlt: icon(faCameraAlt, {transform: {size: 36}}).html[0],
                items: [],
            }
        },
        props: {
            objectId: {
                type: Number,
                required: true,
            },
            objectName: {
                type: String,
                required: true,
            },
        },
        components: {Draggable},
        methods: {
            onUpload(err, file) {
                this.$refs.filepond.removeFile(file.id);
            },
            handleUpload(fieldName, file, metadata, load, error, progress, abort) {
                const formData = new FormData();
                metadata.type = 'image';
                formData.append('file', file, file.name);
                formData.append('metadata', JSON.stringify(metadata));
                formData.append('object_id', this.objectId);
                formData.append('object_name', this.objectName);

                this.$axios({
                    method: 'POST',
                    url: '/admin/gallery',
                    data: formData,
                    onUploadProgress: (e) => {
                        progress(e.lengthComputable, e.loaded, e.total)
                    }
                }).then(res => {
                    load(file);
                    this.items.push(res.data)
                }).catch(() => {
                    error(file)
                });

                return {abort}
            },
            loadFiles() {
                this.$axios.get('admin/gallery', {params: {limit: 0, object_id: this.objectId, object_name: this.objectName}})
                    .then(res => {
                        this.items = res.data.rows
                    })
            },
            onDelete(item) {
                this.$message.confirm('Вы уверены, что хотите удалить эту запись?', () => {
                    this.$axios.delete('admin/gallery', {params: {id: item.id}})
                        .then(() => {
                            this.loadFiles();
                        })
                });
            },
            onDisable(item) {
                item.active = false;
                this.$axios.patch('admin/gallery', {id: item.id, active: false})
            },
            onEnable(item) {
                item.active = true;
                this.$axios.patch('admin/gallery', {id: item.id, active: true})
            },
            onSort(e) {
                if (e.newIndex !== e.oldIndex) {
                    const item = this.items[e.newIndex];
                    this.$axios.patch('admin/gallery', {id: item.id, rank: e.newIndex})
                    /*.then(res => {
                        //this.loadFiles()
                    })*/
                }
            }
        },
        created() {
            this.$fa.add(faCameraAlt, faTimesCircle, faPowerOff, faCheckCircle);
            this.loadFiles();
        },
    }
</script>

<style lang="scss">
  .gallery-manager {
    .list-enter-active, .list-leave-active {
      transition: all 1s;
    }

    .list-enter, .list-leave-to {
      opacity: 0;
      transform: translateY(-30px);
    }

    &-item-wrapper {
      padding: 10px;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
    }

    &-item {
      .image {
        position: relative;
        max-height: 200px;
        border: 1px solid gray;
        background: #000;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;

        img {
          max-width: 100%;
          max-height: 100%;
        }

        .actions {
          display: flex;
          position: absolute;
          width: 100%;
          align-items: center;
          justify-content: space-between;
          bottom: 0;

          .btn {
            opacity: 0;

            .svg-inline--fa {
              color: #fff;
              font-size: 20px;
              border-radius: 30px;
            }

            &:hover {
              opacity: 1;
            }
          }
        }

        &:hover {
          .btn {
            opacity: .8;
          }
        }

        &.disabled {
          background-color: gray;

          img {
            opacity: .5
          }
        }
      }

      .title {
        text-align: center;
        font-size: 12px;
      }
    }

    .upload-gallery {
      @media (min-width: 30em) {
        .filepond--item {
          width: calc(50% - .5em);
        }
      }

      @media (min-width: 50em) {
        .filepond--item {
          width: calc(33.33% - .5em);
        }
      }
    }
  }
</style>
