<template>
  <div class="wrapper">
    <header-bg image="reviews"/>
    <div class="wrapper__content">
      <section class="feedbacks tab__wrap--scroll">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <h2 class="section__title">Отзывы</h2>
            </div>
          </div>
          <div class="container__940">
            <div class="row item__wrap">
              <div class="col-12 col-md-6 col-lg-4 m-width-80">
                <div class="review__item d-flex flex-column justify-content-center align-items-center">
                  <div class="review__item--photo"><img class="rounded-circle"
                                                        src="~assets/images/content/review/man.png" alt=""></div>
                  <h2 class="review__item--name">Виктор Сухоруков</h2>
                  <div class="review__item--position">SaveSpace Inc.</div>
                  <div class="review__item--text">“Chamer is the most valuable business resource we have ever purchased.
                    I would be lost without chamer.”
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-4 m-width-80">
                <div class="review__item d-flex flex-column justify-content-center align-items-center">
                  <div class="review__item--photo"><img class="rounded-circle"
                                                        src="~assets/images/content/review/woman2.png" alt=""></div>
                  <h2 class="review__item--name">Виктор Сухоруков</h2>
                  <div class="review__item--position">SaveSpace Inc.</div>
                  <div class="review__item--text">“Chamer is the most valuable business resource we have ever purchased.
                    I would be lost without chamer.”
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-4 m-width-80">
                <div class="review__item d-flex flex-column justify-content-center align-items-center">
                  <div class="review__item--photo"><img class="rounded-circle"
                                                        src="~assets/images/content/review/man2.png" alt=""></div>
                  <h2 class="review__item--name">Виктор Сухоруков</h2>
                  <div class="review__item--position">SaveSpace Inc.</div>
                  <div class="review__item--text">“Chamer is the most valuable business resource we have ever purchased.
                    I would be lost without chamer.”
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-4 m-width-80">
                <div class="review__item d-flex flex-column justify-content-center align-items-center">
                  <div class="review__item--photo"><img class="rounded-circle"
                                                        src="~assets/images/content/review/man.png" alt=""></div>
                  <h2 class="review__item--name">Виктор Сухоруков</h2>
                  <div class="review__item--position">SaveSpace Inc.</div>
                  <div class="review__item--text">“Chamer is the most valuable business resource we have ever purchased.
                    I would be lost without chamer.”
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-4 m-width-80">
                <div class="review__item d-flex flex-column justify-content-center align-items-center">
                  <div class="review__item--photo"><img class="rounded-circle"
                                                        src="~assets/images/content/review/man2.png" alt=""></div>
                  <h2 class="review__item--name">Виктор Сухоруков</h2>
                  <div class="review__item--position">SaveSpace Inc.</div>
                  <div class="review__item--text">“Chamer is the most valuable business resource we have ever purchased.
                    I would be lost without chamer.”
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-4 m-width-80">
                <div class="review__item d-flex flex-column justify-content-center align-items-center">
                  <div class="review__item--photo"><img class="rounded-circle"
                                                        src="~assets/images/content/review/woman2.png" alt=""></div>
                  <h2 class="review__item--name">Виктор Сухоруков</h2>
                  <div class="review__item--position">SaveSpace Inc.</div>
                  <div class="review__item--text">“Chamer is the most valuable business resource we have ever purchased.
                    I would be lost without chamer.”
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
    import HeaderBg from '../components/header-bg'

    export default {
        auth: false,
        components: {
            'header-bg': HeaderBg,
        },
        head() {
            return {
                title: 'Крафти / Отзывы',
            }
        }
    }
</script>
