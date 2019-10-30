export default ({app, $axios}, inject) => {
  let settings = {
    image_url: $axios.defaults.baseURL.replace('api/', 'image'),
    copyright: 'KRAFTi © 2019. Продажа творческих, спортивных онлайн-курсов для детей и взрослых. Все права защищены.',
    video: {
      index: 359213295,
    },
    links: {
      instagram: 'https://www.instagram.com/krafti.ru/',
      whatsapp: 'https://api.whatsapp.com/send?phone=+79137206478',
      email: 'mailto:info@krafti.ru',
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
          to: '/reviews',
          title: 'Отзывы',
          scope: false,
        }, {
          to: '/office',
          title: 'Личный кабинет',
          scope: 'profile',
        }, {
          to: '/team',
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
          to: '/courses',
          title: 'Курсы',
          auth: false,
        }, {
          to: '/office',
          title: 'Личный кабинет',
          auth: true,
        }, {
          to: '/team',
          title: 'Наша команда',
          auth: false,
        }],
        center: [{
          to: '/reviews',
          title: 'Отзывы',
          auth: false,
        }, {
          to: '/contacts',
          title: 'Контакты',
          auth: false,
        }],
        right: [{
          to: '/info/agreement',
          title: 'Пользовательское соглашение',
          auth: false,
        },
          {
            to: '/info/offer',
            title: 'Публичная оферта',
            auth: false,
          },
          {
            to: '/info/privacy',
            title: 'Политика конфиденциальности',
            auth: false,
          }],
      }
    },
  };
  /*
  settings.menu.footer.right = [{
    to: settings.links.email,
    title: 'Почта',
  }, {
    to: settings.links.whatsapp,
    title: 'WhatsApp',
  }, {
    to: settings.links.instagram,
    title: 'Instagram',
  }];
  */

  inject('settings', settings);
}
