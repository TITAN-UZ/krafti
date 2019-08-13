<template>
    <div>
        <!--<b-modal hide-footer visible no-close-on-backdrop no-close-on-esc size="xl" @hidden="onHide" ref="modalVideo">-->
        <b-modal hide-footer visible size="xl" @hidden="onHide" ref="modalVideo" dialog-class="modal-video">
            <div class="wrapper">
                <div class="wrapper__bg bg_600 js-bg-selection" :style="style_bg"><a class="ic__play&#45;&#45;white" href="" aria-label="video"></a></div>
                <div class="wrapper__content">
                    <div class="lesson__info container__940">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-7 col-12">
                                    <div class="lesson__info--description">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized </div>
                                    <div class="lesson__info--like"><a class="mr-3" href=""><span class="ic__like mr-1"></span>429</a><a href=""><span class="ic__dislike mr-1"> </span>8</a></div>
                                </div>
                                <div class="col-lg-5 col-12">
                                    <div class="lesson__info--note">По окончании урока, вы можете поделиться с нами своим результатом. Достаточно сделать фото и отправить его нам. Лучшие работы будут опубликованы на главной странице.</div>
                                    <div class="lesson__info--share"><a class="button" href="">Поделиться работой</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="what__is__needed container__940">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="s-title">Что <br> понадобится</div>
                                </div>
                            </div>
                            <div class="needed__list">
                                <form action="">
                                    <div class="needed__list--wrapper d-flex justify-content-between flex-wrap">
                                        <div class="needed__item d-flex justify-content-between align-items-center">
                                            <div class="needed__item--title">Бумага формата А3</div>
                                            <button class="needed__item--cart"><img src="~assets/images/general/ic_cart.svg" alt=""></button>
                                        </div>
                                        <div class="needed__item d-flex justify-content-between align-items-center">
                                            <div class="needed__item--title">Бумага формата А3</div>
                                            <button class="needed__item--cart"><img src="~assets/images/general/ic_cart.svg" alt=""></button>
                                        </div>
                                        <div class="needed__item d-flex justify-content-between align-items-center">
                                            <div class="needed__item--title">Бумага формата А3</div>
                                            <button class="needed__item--cart"><img src="~assets/images/general/ic_cart.svg" alt=""></button>
                                        </div>
                                        <div class="needed__item d-flex justify-content-between align-items-center">
                                            <div class="needed__item--title">Бумага формата А3</div>
                                            <button class="needed__item--cart"><img src="~assets/images/general/ic_cart.svg" alt=""></button>
                                        </div>
                                        <div class="needed__item d-flex justify-content-between align-items-center">
                                            <div class="needed__item--title">Бумага формата А3</div>
                                            <button class="needed__item--cart"><img src="~assets/images/general/ic_cart.svg" alt=""></button>
                                        </div>
                                        <div class="needed__item d-flex justify-content-between align-items-center">
                                            <div class="needed__item--title">Бумага формата А3</div>
                                            <button class="needed__item--cart"><img src="~assets/images/general/ic_cart.svg" alt=""></button>
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="download__materials"><a class="button" href="">Скачать материалы</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section class="teacher__content container__940">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="s-title">Преподаватели</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="teacher__detail--wrap">
                                        <div class="teacher__info">
                                            <div class="teacher__info--photo"><img class="teacher-photo rounded-circle" src="~assets/images/content/woman.png" alt=""><span class="label__shape"></span></div>
                                            <h2 class="teacher__info--name">Елизавета Марьянова</h2>
                                            <div class="teacher__info--position">Художник-оформитель</div>
                                        </div>
                                        <div class="teacher__text">These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="science__content container__940">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-8 offset-lg-2 col-12">
                                    <div class="wrapper__science">
                                        <div class="science__content--img"><img class="img-responsive" src="~assets/images/general/bg_science.png" alt=""></div>
                                        <div class="science__content--pretitle">мини-лекция </div>
                                        <h2 class="science__content--title">Наука для детей   </h2>
                                        <div class="science__content--text">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire.  </div>
                                        <div class="science__content--video d-flex justify-content-center"><a class="video" href="" aria-label="video" data-toggle="modal" data-target="#videoModal"><img class="img-responsive bonus__lesson--thumb" src="~assets/images/content/science.png" alt=""></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="lesson__content--bottom container__940">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-7 col-12">
                                    <div class="s-title">Комментарии</div>
                                    <div class="comments__content">
                                        <div class="media message__item d-flex align-items-center justify-content-center">
                                            <div class="wrap mr-2"><img class="message__item--photo rounded-circle" src="~assets/images/content/teacher.png" alt="..."></div>
                                            <div class="media-body d-flex align-items-center justify-content-between">
                                                <div class="message__item--info">Оставьте комментарий</div>
                                                <button class="btn__answer"><img src="~assets/images/general/ic_send.svg" alt=""></button>
                                            </div>
                                        </div>
                                        <div class="media message__item">
                                            <div class="wrap mr-2"><img class="message__item--photo rounded-circle" src="~assets/images/content/teacher.png" alt="..."></div>
                                            <div class="media-body">
                                                <div class="media-body-top d-flex align-items-center justify-content-between">
                                                    <h4 class="message__item--title mt-0">Анна Сотнич</h4><span class="days_ago">1 день назад</span>
                                                </div>
                                                <div class="media-body-bottom d-flex align-items-center justify-content-between">
                                                    <div class="message__item--info">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, and dislike men who are so beguiled and demoralized by the charms of pleasure of the momentso ...</div>
                                                </div>
                                                <button class="btn__answer">Ответить </button>
                                            </div>
                                        </div>
                                        <div class="media message__item">
                                            <div class="wrap mr-2"><img class="message__item--photo rounded-circle" src="~assets/images/content/teacher.png" alt="..."></div>
                                            <div class="media-body">
                                                <div class="media-body-top d-flex align-items-center justify-content-between">
                                                    <h4 class="message__item--title mt-0">Анна Сотнич </h4><span class="days_ago">1 день назад</span>
                                                </div>
                                                <div class="media-body-bottom d-flex align-items-center justify-content-between">
                                                    <div class="message__item--info">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, and dislike men who are so beguiled and demoralized by the charms of pleasure of the momentso ...</div>
                                                </div>
                                                <button class="btn__answer">Ответить </button>
                                            </div>
                                        </div>
                                        <div class="media message__item">
                                            <div class="wrap mr-2"><img class="message__item--photo rounded-circle" src="~assets/images/content/teacher.png" alt="..."></div>
                                            <div class="media-body">
                                                <div class="media-body-top d-flex align-items-center justify-content-between">
                                                    <h4 class="message__item--title mt-0">Анна Сотнич</h4><span class="days_ago">1 день назад</span>
                                                </div>
                                                <div class="media-body-bottom d-flex align-items-center justify-content-between">
                                                    <div class="message__item--info">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, and dislike men who are so beguiled and demoralized by the charms of pleasure of the momentso ...</div>
                                                </div>
                                                <button class="btn__answer">Ответить </button>
                                            </div>
                                        </div>
                                        <button class="button btn-more">Показать еще +7</button>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-12">
                                    <div class="s-title">Следующие уроки</div>
                                    <div class="nextlessons__content">
                                        <div class="media mb-2">
                                            <div class="media--video mr-2"><a class="video" href="" aria-label="video" data-toggle="modal" data-target="#videoModal"><img class="media--thumb img-responsive" src="~assets/images/content/video/1.jpg" alt=""></a></div>
                                            <div class="media-body">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized </div>
                                        </div>
                                        <div class="media mb-2">
                                            <div class="media--video mr-2"><a class="video" href="" aria-label="video" data-toggle="modal" data-target="#videoModal"><img class="media--thumb img-responsive" src="~assets/images/content/video/2.jpg" alt=""></a></div>
                                            <div class="media-body">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized </div>
                                        </div>
                                        <div class="media mb-2">
                                            <div class="media--video mr-2"><a class="video" href="" aria-label="video" data-toggle="modal" data-target="#videoModal"><img class="media--thumb img-responsive" src="~assets/images/content/video/3.jpg" alt=""></a></div>
                                            <div class="media-body">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized </div>
                                        </div>
                                        <div class="media mb-2">
                                            <div class="media--video mr-2"><a class="video" href="" aria-label="video" data-toggle="modal" data-target="#videoModal"><img class="media--thumb img-responsive" src="~assets/images/content/video/4.jpg" alt=""></a></div>
                                            <div class="media-body">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <template slot="modal-header">
                <button class="close" type="button" aria-label="Close" @click="hideModal()">
                    <fa :icon="['fal', 'times']" size="3x"/>
                </button>
            </template>
        </b-modal>
    </div>
</template>

<script>
    import bg from '../../../../../assets/images/general/headline_video.png'
    import {faTimes} from '@fortawesome/pro-light-svg-icons'

    export default {
        data() {
            return {
                style_bg: {'background-image': 'url(' + bg + ')'},
            }
        },
        methods: {
            hideModal() {
                this.$refs.modalVideo.hide();
            },
            onHide() {
                this.$router.push('/courses/' + this.$route.params.cid)
            }
        },
        created() {
            this.$fa.add(faTimes);
        },
        head () {
            return {
                title: 'Крафти / Курсы / Курс №' + this.$route.params.cid + ' / Видео №' + this.$route.params.vid,
            }
        },
    }
</script>