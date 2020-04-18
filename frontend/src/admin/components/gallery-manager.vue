<template>
  <div class="gallery-manager">
    <file-pond
      ref="filepond"
      accepted-file-types="image/jpeg, image/png"
      class-name="upload-gallery"
      :allow-multiple="true"
      :label-idle="faCameraAlt"
      :image-preview-max-height="200"
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
      @processfile="onUpload"
    />

    <div v-if="items.length > 1" class="container text-center text-muted">
      Вы можете сортировать картинки перетаскиванием
    </div>

    <draggable v-model="items" @end="onSort">
      <transition-group tag="div" name="list" class="gallery d-flex flex-wrap justify-content-around">
        <div v-for="item in items" :key="item.id" class="col-auto gallery-manager-item-wrapper">
          <div class="gallery-manager-item">
            <div :class="{image: true, disabled: !item.active}">
              <a :href="item.file" target="_blank" rel="noreferrer">
                <b-img-lazy :src="$image(item, '300x200', 'resize')" width="150" height="100" />
              </a>
              <div class="actions">
                <b-button
                  v-if="!item.active"
                  v-b-tooltip="'Включить'"
                  size="sm"
                  variant="success"
                  @click.prevent="onEnable(item)"
                >
                  <fa :icon="['fad', 'play']" />
                </b-button>
                <b-button v-else v-b-tooltip="'Отключить'" size="sm" variant="warning" @click.prevent="onDisable(item)">
                  <fa :icon="['fad', 'power-off']" />
                </b-button>
                <b-button v-b-tooltip="'Удалить'" size="sm" variant="danger" @click.prevent="onDelete(item)">
                  <fa :icon="['fad', 'times']" />
                </b-button>
              </div>
            </div>
            <div class="title">{{ item.title }}</div>
          </div>
        </div>
      </transition-group>
    </draggable>

    <div v-if="items.length > 0" class="container text-center text-muted">
      {{ items.length }} {{ items.length | noun('картинка|картинки|картинок') }}
    </div>
  </div>
</template>

<script>
import Draggable from 'vuedraggable'
import {icon} from '@fortawesome/fontawesome-svg-core'
import {faCameraAlt, faTimes, faPowerOff, faPlay} from '@fortawesome/pro-duotone-svg-icons'

export default {
  name: 'GalleryManager',
  components: {Draggable},
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
  data() {
    return {
      faCameraAlt: icon(faCameraAlt, {transform: {size: 36}}).html[0],
      items: [],
    }
  },
  created() {
    this.$fa.add(faCameraAlt, faTimes, faPowerOff, faPlay)
    this.loadFiles()
  },
  methods: {
    onUpload(err, file) {
      this.$refs.filepond.removeFile(file.id)
      if (err) {
        console.error(err)
      }
    },
    handleUpload(fieldName, file, metadata, load, error, progress, abort) {
      const formData = new FormData()
      metadata.type = 'image'
      formData.append('file', file, file.name)
      formData.append('metadata', JSON.stringify(metadata))
      formData.append('object_id', this.objectId)
      formData.append('object_name', this.objectName)

      this.$axios({
        method: 'POST',
        url: '/admin/gallery',
        data: formData,
        onUploadProgress: (e) => {
          progress(e.lengthComputable, e.loaded, e.total)
        },
      })
        .then((res) => {
          load(file)
          this.items.push(res.data)
        })
        .catch(() => {
          error(file)
        })

      return {abort}
    },
    loadFiles() {
      this.$axios
        .get('admin/gallery', {params: {limit: 0, object_id: this.objectId, object_name: this.objectName}})
        .then((res) => {
          this.items = res.data.rows
        })
    },
    onDelete(item) {
      this.$confirm('Вы уверены, что хотите удалить эту запись?', async () => {
        await this.$axios.delete('admin/gallery', {params: {id: item.id}})
        this.loadFiles()
      })
    },
    onDisable(item) {
      item.active = false
      this.$axios.patch('admin/gallery', {id: item.id, active: false})
    },
    onEnable(item) {
      item.active = true
      this.$axios.patch('admin/gallery', {id: item.id, active: true})
    },
    onSort(e) {
      if (e.newIndex !== e.oldIndex) {
        const item = this.items[e.newIndex]
        this.$axios.patch('admin/gallery', {id: item.id, rank: e.newIndex})
        /* .then(res => {
                        //this.loadFiles()
                    }) */
      }
    },
  },
}
</script>

<style lang="scss">
.gallery-manager {
  .list-enter-active,
  .list-leave-active {
    transition: all 1s;
  }

  .list-enter,
  .list-leave-to {
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
      border-radius: 5px;
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
          opacity: 0.8;
        }
      }

      &.disabled {
        background-color: gray;

        img {
          opacity: 0.5;
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
        width: calc(50% - 0.5em);
      }
    }

    @media (min-width: 50em) {
      .filepond--item {
        width: calc(33.33% - 0.5em);
      }
    }
  }
}
</style>
