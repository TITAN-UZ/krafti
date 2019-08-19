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
          <div class="row edit-profile__form">
            <div class="col-12">
              <no-ssr>
                <form class="edit-profile" @submit.prevent="onSubmit">
                  <div class="form-body">
                    <div class="form-group mb-3">
                      <div class="avatar__box">
                        <upload-photo :size="150" class="mb-3"/>
                      </div>
                      <label class="text-center"><span>Сменить фото профиля</span></label>
                    </div>

                    <b-form-group class="mb-3" label="Имя / Фамилия" label-for="form-fullname">
                      <b-input v-model="form.fullname" trim/>
                    </b-form-group>

                    <b-form-group class="mb-3" label="Дата рождения" label-for="form-dob">
                      <date-picker v-model="form.dob" ref="datepicker"
                                   input-class="form-control"
                                   type="date"
                                   format="DD.MM.YYYY"
                                   lang="ru"
                                   width="100%"
                                   value-type="format"
                                   :value-type="formatDate"
                                   :shortcuts="false"
                                   :clearable="false"
                                   :first-day-of-week="1"
                                   :default-value="getYear()"
                                   :not-after="getYear()">
                        <template slot="calendar-icon">&nbsp;</template>
                      </date-picker>
                    </b-form-group>

                    <b-form-group class="mb-3" label="Адрес электронной почты"
                                  label-for="form-email">
                      <b-input v-model="form.email" type="email" trim autocomplete="new-password"/>
                    </b-form-group>

                    <b-form-group class="mb-3" label="Пароль" label-for="form-password">
                      <b-input v-model="form.password" type="password" trim autocomplete="new-password"/>
                    </b-form-group>

                    <b-form-group class="mb-3" label="Номер телефона" label-for="form-phone">
                      <phone-input v-model="form.phone" type="tel"
                                   defaultCountry="ru"
                                   :onlyCountries="['ru', 'ua', 'by', 'kz']"
                                   placeholder=""
                                   inputClasses="form-control"
                                   :validCharactersOnly="true"/>
                    </b-form-group>

                    <b-form-group class="mb-3" label="Аккаунт Instagram" label-for="form-instagram">
                      <b-input v-model="form.instagram" trim placeholder="@"/>
                    </b-form-group>

                    <!--<b-form-group class="mb-3" label="Компания" label-for="form-company">
                      <b-input v-model="form.company" trim/>
                    </b-form-group>-->

                    <b-form-group class="mb-3" label="О себе" label-for="form-company">
                      <b-textarea v-model="form.description" rows="5" trim no-resize/>
                    </b-form-group>

                    <!--
                    <div class="form-group mb-3">
                        <div class="form-group-title mb-1">Имя ребенка</div>
                        <label class="mb-0" aria-label="Имя ребенка">
                            <b-input v-model="" type="text">
                        </label>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-group-title mb-1">Дата рождения ребенка</div>
                        <label class="mb-0" aria-label="Дата рождения ребенка">
                            <b-input v-model="" type="date">
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="form-group-title">Пол ребенка</div>
                        <div class="d-flex">
                            <div class="custom-control custom-radio mr-2">
                                <input class="custom-control-input ml-2" id="boy" type="radio">
                                <label class="custom-control-label" for="boy">Мальчик</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" id="girl" type="radio">
                                <label class="custom-control-label" for="girl">Девочка</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group-wrap d-flex align-content-center">
                            <button class="add__baby">
                                <img src="~assets/images/general/ic_btn_add.svg" alt="">
                            </button>
                        </div>
                    </div>
                    -->
                    <div class="d-flex align-items-center justify-content-center">
                      <button class="button" type="submit" aria-label="submit">Сохранить</button>
                    </div>
                  </div>
                </form>
              </no-ssr>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>


<script>
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

            return {
                form: {
                    fullname: this.$auth.user.fullname,
                    dob: this.$auth.user.dob,
                    email: this.$auth.user.email,
                    phone: this.$auth.user.phone,
                    instagram: this.$auth.user.instagram,
                    password: '',
                    children: this.$auth.user.children || [],
                    company: this.$auth.user.company,
                    description: this.$auth.user.description,
                },
                formatDate,
            }
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
            getYear() {
                const d = new Date();

                return d.setFullYear(d.getFullYear() - 12);
            },
            onSubmit() {
                this.$axios.patch('user/profile', this.form)
                    .then((res) => {
                        for (let i in res.data.user) {
                            if (res.data.user.hasOwnProperty(i) && this.form[i] !== undefined) {
                                this.form[i] = res.data.user[i];
                            }
                        }
                        this.$auth.setUser(res.data.user);
                        this.$notify.success({message: 'Изменения успешно сохранены'})
                    })
                    .catch(() => {
                    })
            },
        },
        head() {
            return {
                title: 'Крафти / Личный кабинет / Профиль',
            }
        },
        mounted() {
            document.getElementsByTagName('header')[0].classList.remove('header_img');
        },
    }
</script>
