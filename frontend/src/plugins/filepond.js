import Vue from 'vue'

import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
import vueFilePond from 'vue-filepond'

import FilePondPluginImageTransform from 'filepond-plugin-file-encode'
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation'
import FilePondPluginImageResize from 'filepond-plugin-image-resize'
import FilePondPluginImagePreview from 'filepond-plugin-image-preview'
import FilePondPluginImageCrop from 'filepond-plugin-image-crop'
import FilePondPluginFileEncode from 'filepond-plugin-image-transform'
import UploadBg from '../components/upload-bg'
import UploadPhoto from '../components/upload-photo'
import UploadCover from '../components/upload-cover'
import UploadFile from '../components/upload-file'
import UploadHomework from '../components/upload-homework'
import GalleryManager from '../components/gallery-manager'

const FilePond = vueFilePond(
  FilePondPluginFileEncode,
  FilePondPluginFileValidateType,
  FilePondPluginImageExifOrientation,
  FilePondPluginImagePreview,
  FilePondPluginImageCrop,
  FilePondPluginImageResize,
  FilePondPluginImageTransform,
)
Vue.component('file-pond', FilePond)

Vue.component('upload-bg', UploadBg)
Vue.component('upload-photo', UploadPhoto)
Vue.component('upload-cover', UploadCover)
Vue.component('upload-file', UploadFile)
Vue.component('upload-homework', UploadHomework)
Vue.component('gallery-manager', GalleryManager)
