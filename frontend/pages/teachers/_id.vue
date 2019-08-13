<template>
    <div class="wrapper">
        <!--<div class="wrapper__bg" :style="style_bg"></div>-->
        <div class="wrapper__content">
            <section class="teacher__content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <div class="row">
                                <div class="col-12">
                                    <div class="teacher__detail--wrap">
                                        <div class="teacher__info">
                                            <div class="teacher__info--photo"><img class="teacher-photo rounded-circle" src="~assets/images/content/woman.png" alt="">
                                                <span class="label__shape"></span>
                                            </div>
                                            <h2 class="teacher__info--name">Анастасия Богермауер</h2>
                                            <div class="teacher__info--position">Художник-оформитель</div>
                                        </div>
                                        <div class="teacher__text">These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. </div>
                                        <div class="teacher__gallery d-flex">
                                            <div class="left__block mr-1"><img class="img-responsive" src="~assets/images/content/teacher/img.jpg" alt=""></div>
                                            <div class="right__block"><img class="mb-15 img-responsive" src="~assets/images/content/teacher/img-2.jpg" alt="">
                                                <img class="img-responsive" src="~assets/images/content/teacher/img-3.jpg" alt="">
                                            </div>
                                        </div>
                                        <div class="teacher__text">These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. </div>
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
    //import bg from '../assets/images/general/headline_teachers.jpg';

    export default {
        data() {
            return {
                //style_bg: {'background-image': 'url(' + bg + ')'},
            }
        },
        auth: false,
        head () {
            return {
                title: 'Крафти / Наша команда / Преподаватель ' + this.$route.params.id,
            }
        },
        mounted() {
            document.getElementsByTagName('header')[0].classList.remove('header_img')
        }
    }
</script>