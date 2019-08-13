<template>
    <div class="wrapper">
        <div class="wrapper__bg js-bg-selection" :style="style_bg"></div>
        <div class="wrapper__content">
            <section class="favorite">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <div class="row">
                                <div class="col-12">
                                    <h2 class="section__title">Избранное</h2>
                                </div>
                            </div>
                            <div class="row mob_container">
                                <div class="col-6">
                                    <div class="item">
                                        <div class="item__content"><img class="desktop img-responsive" src="~assets/images/content/item-desktop-1.png" alt=""><img class="mobile img-responsive" src="~assets/images/content/item-mob-1.png" alt=""></div>
                                        <div class="item__body">
                                            <div class="tagline">Tagline</div>
                                            <div class="title">Самое удивительное предложение</div>
                                        </div>
                                        <div class="item__bottom d-flex">
                                            <div class="price">1990 р.</div>
                                            <div class="ic__heart active"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="item">
                                        <div class="item__content"><img class="desktop img-responsive" src="~assets/images/content/item-desktop-1.png" alt=""><img class="mobile img-responsive" src="~assets/images/content/item-mob-1.png" alt=""></div>
                                        <div class="item__body">
                                            <div class="tagline">Tagline</div>
                                            <div class="title">Самое удивительное предложение</div>
                                        </div>
                                        <div class="item__bottom d-flex">
                                            <div class="price">1990 р.</div>
                                            <div class="ic__heart"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="item">
                                        <div class="item__content"><img class="desktop img-responsive" src="~assets/images/content/item-desktop-1.png" alt=""><img class="mobile img-responsive" src="~assets/images/content/item-mob-1.png" alt=""></div>
                                        <div class="item__body">
                                            <div class="tagline">Tagline</div>
                                            <div class="title">Самое удивительное предложение</div>
                                        </div>
                                        <div class="item__bottom d-flex">
                                            <div class="price">1990 р.</div>
                                            <div class="ic__heart"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="item">
                                        <div class="item__content"><img class="desktop img-responsive" src="~assets/images/content/item-desktop-1.png" alt=""><img class="mobile img-responsive" src="~assets/images/content/item-mob-1.png" alt=""></div>
                                        <div class="item__body">
                                            <div class="tagline">Tagline</div>
                                            <div class="title">Самое удивительное предложение</div>
                                        </div>
                                        <div class="item__bottom d-flex">
                                            <div class="price">1990 р.</div>
                                            <div class="ic__heart"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="item">
                                        <div class="item__content"><img class="desktop img-responsive" src="~assets/images/content/item-desktop-1.png" alt=""><img class="mobile img-responsive" src="~assets/images/content/item-mob-1.png" alt=""></div>
                                        <div class="item__body">
                                            <div class="tagline">Tagline</div>
                                            <div class="title">Самое удивительное предложение</div>
                                        </div>
                                        <div class="item__bottom d-flex">
                                            <div class="price">1990 р.</div>
                                            <div class="ic__heart"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="item">
                                        <div class="item__content"><img class="desktop img-responsive" src="~assets/images/content/item-desktop-1.png" alt=""><img class="mobile img-responsive" src="~assets/images/content/item-mob-1.png" alt=""></div>
                                        <div class="item__body">
                                            <div class="tagline">Tagline</div>
                                            <div class="title">Самое удивительное предложение</div>
                                        </div>
                                        <div class="item__bottom d-flex">
                                            <div class="price">1990 р.</div>
                                            <div class="ic__heart"></div>
                                        </div>
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
    import bg from '../../assets/images/general/headline_favorites.jpg'

    export default {
        data() {
            return {
                style_bg: {'background-image': 'url(' + bg + ')'}
            }
        },
        head () {
            return {
                title: 'Крафти / Личный кабинет / Избранное',
            }
        },
        methods: {
        },
        mounted() {
            document.getElementsByTagName('header')[0].classList.add('header_img');
        }
    }
</script>