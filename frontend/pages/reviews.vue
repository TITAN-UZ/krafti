<template>
    <div class="wrapper">
        <div class="wrapper__bg js-bg-selection" :style="style_bg"></div>
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
                                    <div class="review__item--photo"><img class="rounded-circle" src="~assets/images/content/review/man.png" alt=""></div>
                                    <h2 class="review__item--name">Виктор Сухоруков</h2>
                                    <div class="review__item--position">SaveSpace Inc.</div>
                                    <div class="review__item--text">“Chamer is the most valuable business resource we have ever purchased. I would be lost without chamer.” </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 m-width-80">
                                <div class="review__item d-flex flex-column justify-content-center align-items-center">
                                    <div class="review__item--photo"><img class="rounded-circle" src="~assets/images/content/review/woman2.png" alt=""></div>
                                    <h2 class="review__item--name">Виктор Сухоруков</h2>
                                    <div class="review__item--position">SaveSpace Inc.</div>
                                    <div class="review__item--text">“Chamer is the most valuable business resource we have ever purchased. I would be lost without chamer.”</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 m-width-80">
                                <div class="review__item d-flex flex-column justify-content-center align-items-center">
                                    <div class="review__item--photo"><img class="rounded-circle" src="~assets/images/content/review/man2.png" alt=""></div>
                                    <h2 class="review__item--name">Виктор Сухоруков</h2>
                                    <div class="review__item--position">SaveSpace Inc.</div>
                                    <div class="review__item--text">“Chamer is the most valuable business resource we have ever purchased. I would be lost without chamer.”</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 m-width-80">
                                <div class="review__item d-flex flex-column justify-content-center align-items-center">
                                    <div class="review__item--photo"><img class="rounded-circle" src="~assets/images/content/review/man.png" alt=""></div>
                                    <h2 class="review__item--name">Виктор Сухоруков</h2>
                                    <div class="review__item--position">SaveSpace Inc.</div>
                                    <div class="review__item--text">“Chamer is the most valuable business resource we have ever purchased. I would be lost without chamer.”</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 m-width-80">
                                <div class="review__item d-flex flex-column justify-content-center align-items-center">
                                    <div class="review__item--photo"><img class="rounded-circle" src="~assets/images/content/review/man2.png" alt=""></div>
                                    <h2 class="review__item--name">Виктор Сухоруков</h2>
                                    <div class="review__item--position">SaveSpace Inc.</div>
                                    <div class="review__item--text">“Chamer is the most valuable business resource we have ever purchased. I would be lost without chamer.”</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 m-width-80">
                                <div class="review__item d-flex flex-column justify-content-center align-items-center">
                                    <div class="review__item--photo"><img class="rounded-circle" src="~assets/images/content/review/woman2.png" alt=""></div>
                                    <h2 class="review__item--name">Виктор Сухоруков</h2>
                                    <div class="review__item--position">SaveSpace Inc.</div>
                                    <div class="review__item--text">“Chamer is the most valuable business resource we have ever purchased. I would be lost without chamer.”</div>
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
    import bg from '../assets/images/general/headline_favorites.jpg';

    export default {
        auth: false,
        data() {
            return {
                style_bg: {'background-image': 'url(' + bg + ')'},
            }
        },
        head () {
            return {
                title: 'Крафти / Отзывы',
            }
        },
        mounted() {
            document.getElementsByTagName('header')[0].classList.add('header_img')
        }
    }
</script>