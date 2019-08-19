export default ctx => {
  ctx.$axios.interceptors.response.use(function (config) {
    return config;
  }, function (error) {
    if (error.response) {
      switch (error.response.status) {
        case 422:
          ctx.app.$notify.error({
            message: error.response.data
          });
          break;
        case 401:
          const token = ctx.app.$auth.getToken('local');
          if (token) {
            const now = Date.now().valueOf() / 1000;
            const jwt = require('jwt-decode')(token);
            if (token && now > jwt.exp) {
              ctx.app.$notify.error({message: 'Ваша сессия закончилась'});
              ctx.app.$auth.logout();
            }
          } else {
            ctx.app.$notify.error({message: error.response.data});
            ctx.$auth.redirect('login');
          }
          break;
        default:
          ctx.app.$notify.error({
            message: error.response.data
          });
      }
    } else {
      ctx.app.$notify.error({
        message: 'Неизвестная ошибка'
      });
    }

    return Promise.reject(error.response);
  });
}
