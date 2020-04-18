<template>
  <div class="row palitra">
    <div class="col-lg-8 col-12 palitra__info d-flex justify-content-center align-items-center flex-column">
      <div class="palitra__info--title">Палитра прогресса</div>
      <div class="palitra__info--count">
        +{{ palette_bonus }} {{ palette_bonus | noun('крафтик|крафтика|крафтиков') }}
      </div>
      <div class="palitra__info--text">
        Это ваша персональная палитра прогресса. Смотрите уроки, отправляйте домашние задания, и ваша палитра будет
        заполняться цветами. За полную палитру вы получаете крафтики.
      </div>
    </div>
    <div class="col-lg-4 col-12 col-md-6 palitra__img d-flex align-content-center justify-content-center">
      <img v-if="progress.section == 1" class="img-responsive" src="~assets/images/palette/palette-1.svg" />
      <img v-if="progress.section == 2" class="img-responsive" src="~assets/images/palette/palette-3.svg" />
      <img v-if="progress.section == 3" class="img-responsive" src="~assets/images/palette/palette-6.svg" />
      <img v-if="progress.section == 0" class="img-responsive" src="~assets/images/palette/palette-7.svg" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'CoursePalette',
  props: {
    record: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      palette_bonus: process.env.COINS_PALETTE,
    }
  },
  computed: {
    progress() {
      return this.record.progress
    },
  },
}
</script>
