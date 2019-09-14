<template>
  <div>
    <div @click="isOpen = true">
      <slot name="button"></slot>
    </div>
    <b-modal ref="modalWindow"
             dialog-class="modal-vimeo"
             :size="size"
             :visible="isOpen"
             hide-footer
             @shown="onShown"
             @hidden="onHidden">
      <div class="embed-responsive embed-responsive-16by9">
        <iframe id="vimeo-iframe"
                class="embed-responsive-item"
                :src="'https://player.vimeo.com/video/' + video"
                allowfullscreen
                allow="autoplay; fullscreen"></iframe>
      </div>
      <template slot="modal-header">
        <button class="close" type="button" aria-label="Close" @click="hide">
          <fa :icon="['fal', 'times']" size="2x"/>
        </button>
      </template>
    </b-modal>
  </div>
</template>

<script>
    import Player from '@vimeo/player'
    import {faTimes} from '@fortawesome/pro-light-svg-icons'

    export default {
        data() {
            return {
                isOpen: false,
            }
        },
        props: {
            video: {
                type: Number,
                required: true,
            },
            /*value: {
                type: Boolean,
                required: false,
                default: false,
            },*/
            size: {
                type: String,
                required: false,
                default: 'xl'
            }
        },
        methods: {
            onHidden(e) {
                this.$emit('onHidden', e);
                this.isOpen = false;
            },
            onShown() {
                let elem = document.getElementById('vimeo-iframe');
                if (elem) {
                    elem.style.opacity = '1';
                    elem.style.height = this.height;
                    try {
                        const player = new Player(elem);
                        player.play()
                    } catch (e) {
                        console.error(e)
                    }
                }
            },
            hide() {
                this.$refs['modalWindow'].hide()
            },
            show() {
                this.$refs['modalWindow'].show()
            },
        },
        created() {
            this.$fa.add(faTimes)
        }
    }
</script>

<style lang="scss">
  .modal-vimeo {
    .modal-header {
      position: absolute;
      z-index: 10;
      width: 100%;
      .close {
        margin-left: auto;
        color: #fff;
      }
    }

    .modal-body {
      padding: 0;
      border-radius: 28.3099px;
    }

    iframe {
      border-radius: 28.3099px;
      opacity: 0;
      height: 0;
      transition: opacity 1s ease-in-out, height 3s ease-in-out;
    }
  }
</style>
