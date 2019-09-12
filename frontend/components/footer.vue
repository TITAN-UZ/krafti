<template>
    <footer class="footer">
        <div class="container">
            <div class="footer__top">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="footer__logo">
                            <b-link to="/"><img src="~assets/images/general/logo.svg" alt=""></b-link>
                        </div>
                        <div class="footer__instagram">
                            <a class="ic__instagram" :href="$settings.links.instagram" target="_blank" rel="noreferrer"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer__middle">
                <div class="row">
                    <div class="col-12 col-md-8 offset-md-2">
                        <div class="footer__menu d-flex">
                            <nav>
                                <ul class="list">
                                    <b-nav-item v-for="i in $settings.menu.footer.left"
                                                v-if="!i.auth || $auth.loggedIn"
                                                :to="i.to" :key="i.to">{{i.title}}
                                    </b-nav-item>
                                </ul>
                            </nav>
                            <nav>
                                <ul class="list">
                                    <b-nav-item v-for="i in $settings.menu.footer.center"
                                                v-if="!i.auth || $auth.loggedIn"
                                                :to="i.to" :key="i.to">{{i.title}}
                                    </b-nav-item>
                                </ul>
                            </nav>
                            <!--<nav>
                                <ul class="list">
                                    <li class="nav-item" v-for="i in $settings.menu.footer.right" :key="i.to">
                                        <a :href="i.to" class="nav-link" target="_blank" rel="noreferrer">{{i.title}}</a>
                                    </li>
                                </ul>
                            </nav>-->
                          <nav>
                            <ul class="list">
                              <b-nav-item v-for="i in $settings.menu.footer.right"
                                          :to="i.to" :key="i.to">{{i.title}}
                              </b-nav-item>
                            </ul>
                          </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer__bottom">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="footer__pay d-flex align-items-center">
                            <ul class="pay__list d-flex">
                                <li class="ic_paypal"></li>
                                <li class="ic_mastercard"></li>
                                <li class="ic_visa"></li>
                                <li class="ic_google_pay"></li>
                                <li class="ic_applepay"></li>
                            </ul>
                            <div class="pay__text">
                              <p>ИП Матюшкин А. А.<br>ИНН 542512143999 / ОГРН 318547600033813</p>
                              <p>630047, г. Новосибирск, ул. Кузьмы Минина, 9/1, кв. 163<br>Контактное лицо: Матюшкин Антон, тел. +7-913-790-10-90</p>
                              <p></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="footer__copyright">{{$settings.copyright}}</div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
    export default {
    }
</script>
