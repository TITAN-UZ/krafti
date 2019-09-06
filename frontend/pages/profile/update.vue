<template>
  <div class="wrapper">
    <div class="wrapper__content">
      <section class="edit-profile__content">
        <div class="container">

          <div class="row">
            <div class="col-12">
              <h2 class="section__title text-center">Изменить профиль</h2>
            </div>
          </div>

          <div class="row edit-profile__form profile-form">
            <div class="col-12">
              <client-only>
                <div class="form-body">
                  <div class="form-group mb-3">
                    <div class="avatar__box">
                      <upload-photo :size="150" class="mb-3"/>
                    </div>
                    <label class="text-center">Сменить фото профиля</label>
                  </div>

                  <b-form-group class="mb-3" label="Авторизация через соц.сети" label-for="form-social">
                    <div class="social-providers flex-wrap profile">
                      <button class="vkontakte inactive col-12 col-md-6" v-if="$auth.user.oauth2.vkontakte === undefined" @click.prevent="onLink('vkontakte')">
                        <fa :icon="['fab', 'vk']" class="mr-2"/>
                        <span>Подключить</span>
                      </button>
                      <button class="vkontakte col-12 col-md-6" v-else @click.prevent="onUnlink('vkontakte')">
                        <fa :icon="['fab', 'vk']" class="mr-2"/>
                        <span>{{$auth.user.oauth2.vkontakte}}</span>
                        <fa :icon="['fal', 'times']" class="ml-2"/>
                      </button>

                      <button class="instagram inactive  col-12 col-md-6" v-if="$auth.user.oauth2.instagram === undefined" @click.prevent="onLink('instagram')">
                        <fa :icon="['fab', 'instagram']" class="mr-2"/>
                        <span>Подключить</span>
                      </button>
                      <button class="instagram  col-12 col-md-6" v-else @click.prevent="onUnlink('instagram')">
                        <fa :icon="['fab', 'instagram']" class="mr-2"/>
                        <span>{{$auth.user.oauth2.instagram}}</span>
                        <fa :icon="['fal', 'times']" class="ml-2"/>
                      </button>

                    </div>
                  </b-form-group>
                </div>


                <form class="edit-profile" @submit.prevent="onSubmit">
                  <div class="form-body">
                    <b-form-group class="mb-3" label="Имя / Фамилия" label-for="form-fullname">
                      <b-input v-model="form.fullname" trim required/>
                    </b-form-group>

                    <b-form-group class="mb-3" label="Дата рождения" label-for="form-dob">
                      <date-picker
                        v-model="form.dob"
                        ref="datepicker"
                        input-class="form-control"
                        type="date"
                        format="DD.MM.YYYY"
                        lang="ru"
                        width="100%"
                        value-type="format"
                        :input-attr="{required: true}"
                        :value-type="formatDate"
                        :shortcuts="false"
                        :clearable="false"
                        :first-day-of-week="1"
                        :default-value="getYear()"
                        :not-after="getYear()">
                        <template slot="calendar-icon">&nbsp;</template>
                      </date-picker>
                    </b-form-group>

                    <b-form-group class="mb-3" label="Адрес электронной почты" label-for="form-email">
                      <b-input v-model="form.email" type="email" trim required autocomplete="new-password"/>
                    </b-form-group>

                    <b-form-group class="mb-3" label="Пароль" label-for="form-password">
                      <b-input v-model="form.password" type="password" trim autocomplete="new-password"/>
                    </b-form-group>

                    <b-form-group class="mb-3" label="Номер телефона" label-for="form-phone">
                      <phone-input
                        v-model="form.phone"
                        type="tel"
                        defaultCountry="ru"
                        placeholder=""
                        inputClasses="form-control"
                        :onlyCountries="['ru', 'ua', 'by', 'kz']"
                        :validCharactersOnly="true"/>
                    </b-form-group>

                    <b-form-group class="mb-3" label="Аккаунт Instagram" label-for="form-instagram">
                      <b-input v-model="form.instagram" trim placeholder="@"/>
                    </b-form-group>

                    <!--<b-form-group class="mb-3" label="Компания" label-for="form-company">
                      <b-input v-model="form.company" trim/>
                    </b-form-group>

                    <b-form-group class="mb-3" label="О себе" label-for="form-company">
                      <b-textarea v-model="form.description" rows="5" trim no-resize/>
                    </b-form-group>-->

                    <b-form-group class="mb-3" label="Ваш промокод" label-for="form-promo" description="Если ваш друг использует этот код при регистрации, он получит скидку на первую покупку, а вы - крафтики!">
                      <b-input-group>
                        <b-form-input :value="$auth.user.promo" readonly style="background: transparent"/>
                        <b-input-group-append>
                          <b-button variant="outline-primary" @click="copyLink">
                            <fa :icon="['fad', 'copy']"/>
                          </b-button>
                        </b-input-group-append>
                      </b-input-group>
                    </b-form-group>

                    <div class="children-form mb-5">
                      <h4 class="text-muted">Ваши дети</h4>

                      <div class="row mb-3 justify-content-center" v-if="form.children.length">
                        <b-badge pill variant="primary" v-for="(child, idx) in form.children" :key="idx" class="ml-3 mb-2 child-badge" @click="removeChild(idx)">
                          <fa :icon="['fad', 'female']" class="mr-2" v-if="child.gender > 0"/>
                          <fa :icon="['fad', 'male']" class="mr-2" v-else/>
                          {{child.name}}, {{child.dob | years}} {{child.dob | years | noun('год|года|лет')}}
                          <fa :icon="['fal', 'times']" class="ml-2"/>
                        </b-badge>
                      </div>

                      <transition name="fade">
                        <div v-if="children_form">
                          <b-form-group
                            label-cols-md="4"
                            label="Имя"
                            label-align="right">
                            <b-form-input v-model="child.name" autofocus :required="children_form"/>
                          </b-form-group>
                          <b-form-group
                            label-cols-md="4"
                            label="Дата рождения"
                            label-align="right">
                            <date-picker
                              v-model="child.dob"
                              ref="child-datepicker"
                              input-class="form-control"
                              type="date"
                              format="DD.MM.YYYY"
                              lang="ru"
                              width="100%"
                              value-type="format"
                              :input-attr="{required: children_form}"
                              :value-type="formatDate"
                              :shortcuts="false"
                              :clearable="false"
                              :first-day-of-week="1"
                              :default-value="getYear(6)"
                              :not-after="getYear(2)">
                              <template slot="calendar-icon">&nbsp;</template>
                            </date-picker>
                          </b-form-group>
                          <b-form-group
                            label-cols-md="4"
                            label="Пол ребенка"
                            label-align="right">
                            <b-form-radio-group v-model="child.gender" :required="children_form">
                              <b-form-radio value="0">Мальчик</b-form-radio>
                              <br/>
                              <b-form-radio value="1">Девочка</b-form-radio>
                            </b-form-radio-group>
                          </b-form-group>
                        </div>
                      </transition>

                      <div class="form-group" v-if="form.children.length < 4">
                        <b-button variant="default" class="d-flex align-items-center" v-if="!children_form" @click.prevent="addChild">
                          <fa :icon="['fad', 'plus-circle']" class="mr-2"/>
                          <span v-if="!form.children.length">Добавить ребёнка</span>
                          <span v-else>Добавить еще одного ребёнка</span>
                        </b-button>
                        <div class="d-flex justify-content-between" v-else>
                          <b-button variant="default" class="d-flex align-items-center" @click.prevent="addChild">
                            <fa :icon="['fad', 'check-circle']" class="mr-2"/>
                            Ок
                          </b-button>
                          <b-button variant="default" class="d-flex align-items-center" @click.prevent="children_form = false">
                            Отмена
                            <fa :icon="['fad', 'times-circle']" class="ml-2"/>
                          </b-button>
                        </div>
                      </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-center">
                      <button class="button" type="submit" aria-label="submit">Сохранить</button>
                    </div>
                  </div>
                </form>
              </client-only>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>


<script>
    import Vue from 'vue'
    import {faVk, faInstagram} from '@fortawesome/free-brands-svg-icons'
    import VueClipboard from 'vue-clipboard2'
    import {faCopy, faPlusCircle, faCheckCircle, faTimesCircle, faMale, faFemale} from '@fortawesome/pro-duotone-svg-icons'
    import {faTimes} from '@fortawesome/pro-light-svg-icons'

    VueClipboard.config.autoSetContainer = true; // add this line
    Vue.use(VueClipboard);

    export default {
        auth: true,
        data() {
            const formatDate = {
                value2date: value => {
                    return value ? this.$moment(new Date(value), 'DD.MM.YYYY').toDate() : null;
                },
                date2value: date => {
                    return date ? this.$moment(date).format('YYYY-MM-DD') : null;
                }
            };

            let user = JSON.parse(JSON.stringify(this.$auth.user));

            return {
                children_form: false,
                child: {
                    name: null,
                    dob: null,
                    gender: null
                },
                form: {
                    fullname: user.fullname,
                    dob: user.dob,
                    email: user.email,
                    phone: user.phone,
                    instagram: user.instagram,
                    password: '',
                    children: user.children || [],
                    company: user.company,
                    description: user.description,
                },
                formatDate,
            }
        },
        computed: {
            promo: {
                get() {
                    return 'https://krafti.ru/p/' + this.$auth.user.promo;
                }
            },
        },
        asyncData({app, params}) {
            return app.$axios.get('user/profile')
                .then((res) => {
                    app.$auth.setUser(res.data.user);
                }).catch(() => {
                    app.$auth.logout()
                })
        },
        methods: {
            getYear(minus = 12) {
                const d = new Date();

                return d.setFullYear(d.getFullYear() - minus);
            },
            onSubmit() {
                this.$axios.patch('user/profile', this.form)
                    .then((res) => {
                        this.$auth.setUser(res.data.user);
                        this.updateForm();
                        this.$notify.success({message: 'Изменения успешно сохранены'})
                    })
                    .catch(() => {
                    })
            },
            updateForm() {
                for (let i in this.$auth.user) {
                    if (this.$auth.user.hasOwnProperty(i) && this.form[i] !== undefined) {
                        this.form[i] = this.$auth.user[i];
                    }
                }
            },
            copyLink() {
                this.$copyText(this.promo)
                    .then(() => {
                        this.$notify.success({message: 'Ссылка успешно скопирована в буфер обмена!'})
                    })
            },
            onLink(provider) {
                const x = screen.width / 2 - 700 / 2;
                const y = screen.height / 2 - 450 / 2;

                let oauth2 = this.$axios.defaults.baseURL + 'security/oauth2?provider=' + provider;
                const win = window.open(oauth2, 'AuthPopup', 'width=700,height=450,modal=yes,alwaysRaised=yes,left=' + x + ',top=' + y);

                let timer = this.setInterval(() => {
                    try {
                        let data = JSON.parse(win.document.body.innerText);
                        win.close();
                        if (data.success) {
                            this.$notify.success({message: 'Вы успешно привязали ' + (provider == 'instagram' ? 'Instagram' : 'Вконтакте')});
                            this.$auth.fetchUser()
                                .then(() => {
                                    this.updateForm()
                                });
                        } else if (data.error) {
                            this.$notify.error({message: data.error});
                        }
                    } catch (e) {
                        //console.error(e)
                    }
                    if (win && win.closed) {
                        this.clearInterval(timer);
                    }
                }, 500)
            },
            onUnlink(provider) {
                this.$axios.delete('security/oauth2', {params: {provider: provider}})
                    .then(() => {
                        this.$notify.info({message: 'Вы успешно отвязали ' + (provider == 'instagram' ? 'Instagram' : 'Вконтакте')});
                        this.$auth.fetchUser()
                            .then(() => {
                                this.updateForm()
                            });
                    })
            },
            addChild() {
                if (this.children_form === true) {
                    let i;
                    for (i in this.child) {
                        if (this.child.hasOwnProperty(i)) {
                            if (this.child[i] === null) {
                                return;
                            }
                        }
                    }

                    if (this.form.children.length < 4) {
                        this.form.children.push(JSON.parse(JSON.stringify(this.child)));
                        for (i in this.child) {
                            if (this.child.hasOwnProperty(i)) {
                                this.child[i] = null;
                            }
                        }
                    }
                    this.children_form = false
                } else {
                    this.children_form = true
                }
            },
            removeChild(idx) {
                let newArr = [];
                this.form.children.forEach((v, i) => {
                    if (i !== idx) {
                        newArr.push(v)
                    }
                });
                this.form.children = newArr;
            },
        },
        head() {
            return {
                title: 'Крафти / Личный кабинет / Профиль',
            }
        },
        created() {
            this.$app.header_image.set(false);
            this.$fa.add(faCopy, faPlusCircle, faCheckCircle, faTimesCircle, faTimes, faVk, faInstagram, faMale, faFemale);
        },
    }
</script>

<style lang="scss">
  .children-form {

    .fade-enter-active, .fade-leave-active {
      transition: opacity .5s;
    }

    .fade-enter, .fade-leave-to {
      opacity: 0;
    }

    .child-badge {
      font-size: 16px;
      cursor: pointer;
      padding: 10px;
      font-weight: 400;

      .fa-times {
        font-size: 14px;
      }

      &:hover {
        opacity: 0.8;
      }
    }

    .col-form-label {
      height: auto !important;
      padding-top: calc(.4rem + 1px);
      padding-bottom: calc(.4rem + 1px);
    }

    .form-control {
      padding: .5rem .6rem !important;
      height: auto !important;
    }

    .custom-control-label {
      color: #000;
    }

    button {
      color: #6c757d;
      padding-left: 0;
      padding-right: 0;

      .svg-inline--fa {
        font-size: 30px;
        --fa-primary-color: #fff;
        --fa-secondary-color: #6c757d;
        border: 1px solid #fff;
        border-radius: 30px;
        box-shadow: 0 .125rem .25rem rgba(#000, .075);
      }

      &:hover {
        .svg-inline--fa {
          box-shadow: 0 .25rem .5rem rgba(#000, .15);
        }
      }
    }
  }
</style>
