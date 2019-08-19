<template>
    <div class="wrapper">
        <div class="wrapper__bg" :style="style_bg"></div>
        <div class="wrapper__content">
            <section class="teachers">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <h2 class="section__title">Наша команда</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 tab__wrap--scroll">
                                    <b-tabs>
                                        <b-tab title="Рисование" active>
                                            <div class="row">
                                                <div class="col-lg-5 col-12 left__block">
                                                    <div class="teachers__list">
                                                        <div class="media teacher__item">
                                                            <div class="wrap mr-3">
                                                                <img class="teacher__item--photo rounded-circle" src="~assets/images/content/teacher.png" alt="..."><span class="label__shape"></span>
                                                            </div>
                                                            <div class="media-body">
                                                                <b-link to="/teachers/1">
                                                                    <h4 class="teacher__item--title mt-0">Алиса Матюшкина</h4>
                                                                </b-link>
                                                                <h5 class="teacher__item--position">Идейный вдохновитель</h5>
                                                                <div class="teacher__item--info">On the other hand, we
                                                                    denounce with righteous indignation and dislike men
                                                                    who are so beguiled and demoralized by the charms of
                                                                    pleasure of the moment, so ...
                                                                </div>
                                                                <div class="teacher__item--more">
                                                                    <b-link class="more_link" to="/teachers/1">Подробнее</b-link>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="media teacher__item">
                                                            <div class="wrap mr-3">
                                                                <img class="teacher__item--photo rounded-circle" src="~assets/images/content/teacher.png" alt="..."><span class="label__shape"></span>
                                                            </div>
                                                            <div class="media-body">
                                                                <b-link to="/teachers/2">
                                                                    <h4 class="teacher__item--title mt-0">Алиса Матюшкина</h4>
                                                                </b-link>
                                                                <h5 class="teacher__item--position">Идейный вдохновитель</h5>
                                                                <div class="teacher__item--info">On the other hand, we
                                                                    denounce with righteous indignation and dislike men
                                                                    who are so beguiled and demoralized by the charms of
                                                                    pleasure of the moment, so ...
                                                                </div>
                                                                <div class="teacher__item--more">
                                                                    <b-link class="more_link" to="/teachers/2">Подробнее</b-link>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="media teacher__item">
                                                            <div class="wrap mr-3">
                                                                <img class="teacher__item--photo rounded-circle" src="~assets/images/content/teacher.png" alt="..."><span class="label__shape"></span>
                                                            </div>
                                                            <div class="media-body">
                                                                <b-link to="/teachers/3">
                                                                    <h4 class="teacher__item--title mt-0">Алиса Матюшкина</h4>
                                                                </b-link>
                                                                <h5 class="teacher__item--position">Идейный вдохновитель</h5>
                                                                <div class="teacher__item--info">On the other hand, we
                                                                    denounce with righteous indignation and dislike men
                                                                    who are so beguiled and demoralized by the charms of
                                                                    pleasure of the moment, so ...
                                                                </div>
                                                                <div class="teacher__item--more">
                                                                    <b-link class="more_link" to="/teachers/3">Подробнее</b-link>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7 right__block d-none d-lg-block">
                                                    <div class="tab__img">
                                                        <img class="img-responsive" alt=""
                                                             src="~assets/images/general/tab-teachers.png">
                                                    </div>
                                                </div>
                                            </div>
                                        </b-tab>
                                        <b-tab title="Спорт">
                                            ...
                                        </b-tab>
                                        <b-tab title="Музыка">
                                            ...
                                        </b-tab>
                                        <b-tab title="Кулинария">
                                            ...
                                        </b-tab>
                                    </b-tabs>
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
    import bg from '../../assets/images/general/headline_teachers.jpg';

    export default {
        auth: false,
        data() {
            return {
                style_bg: {'background-image': 'url(' + bg + ')'},
            }
        },
        head () {
            return {
                title: 'Крафти / Наша команда',
            }
        },
        mounted() {
            document.getElementsByTagName('header')[0].classList.add('header_img')
        }
    }
</script>
