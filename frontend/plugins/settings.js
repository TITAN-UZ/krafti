import Vue from 'vue'

let settings = {
  copyright: 'Крафти © 2019. Все права защищены',
  links: {
    instagram: 'https://www.instagram.com/krafti.ru/',
    //whatsapp: 'https://whatsapp.com',
    email: 'mailto://info@krafti.ru',
    favorites: '/profile/favorites',
    profile: '/profile/update',
  },
  menu: {
    header: [
      /*{
          to: '/',
          title: 'Главная',
          scope: false,
      },*/
      {
        to: '/courses',
        title: 'Курсы',
        scope: false,
      }, {
        to: '/office',
        title: 'Личный кабинет',
        scope: 'profile',
      }, {
        to: '/teachers',
        title: 'Наша команда',
        scope: false,
      }, {
        to: '/contacts',
        title: 'Контакты',
        scope: false,
      }, {
        to: '/admin',
        title: 'Админка',
        scope: 'admin',
      }],
    footer: {
      left: [{
        to: '/',
        title: 'Главная',
        auth: false,
      }, {
        to: '/courses',
        title: 'Курсы',
        auth: false,
      }, {
        to: '/office/store',
        title: 'Личный кабинет',
        auth: true,
      }],
      center: [{
        to: '/teachers',
        title: 'Преподаватели',
        auth: false,
      }, {
        to: '/reviews',
        title: 'Отзывы',
        auth: false,
      }, {
        to: '/contacts',
        title: 'Контакты',
        auth: false,
      }],
      /*right: [],*/
    }
  },
};

settings.menu.footer.right = [{
  to: settings.links.email,
  title: 'Почта',
}, /*{
  to: settings.links.whatsapp,
  title: 'WhatsApp',
},*/ {
  to: settings.links.instagram,
  title: 'Instagram',
}];

Vue.$settings = Vue.prototype.$settings = settings;
