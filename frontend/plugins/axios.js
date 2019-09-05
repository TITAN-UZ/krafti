export default function ({app, $axios}) {
  /*$axios.onRequest(config => {
    console.log('Making request to ' + config.url)
  })*/

  let isRefreshing = false;
  let failedQueue = [];

  const processQueue = (error, token = null) => {
    failedQueue.forEach(prom => {
      if (error) {
        prom.reject(error);
      } else {
        prom.resolve(token);
      }
    });

    failedQueue = [];
  };

  $axios.onError(error => {
    if (error.response) {
      switch (error.response.status) {
        case 422:
          app.$notify.error({
            message: error.response.data
          });
          break;
        case 401:
          /*if (process.client) {
            const originalRequest = error.config;

            if (isRefreshing) {
              return new Promise(function (resolve, reject) {
                failedQueue.push({resolve, reject})
              }).then(token => {
                originalRequest.headers['Authorization'] = 'Bearer ' + token;
                return $axios(originalRequest);
              }).catch(err => {
                return err
              })
            }

            originalRequest._retry = true;
            isRefreshing = true;
            //const refresh_token = store.state.user.refreshToken;
            return new Promise(function (resolve, reject) {
              $axios.get('security/refresh'/!*, {refresh_token}*!/)
                .then(res => {
                  if (res.data.token) {
                    app.$auth.setToken('local', res.data.token);
                    $axios.defaults.headers.common['Authorization'] = 'Bearer ' + res.data.token;
                    originalRequest.headers['Authorization'] = 'Bearer ' + res.data.token;
                    processQueue(null, res.data.token);
                    resolve($axios(originalRequest));
                  }
                })
                .catch(err => {
                  processQueue(err, null);
                  //app.$auth.logout();
                  reject(error);
                })
                .then(() => {
                  isRefreshing = false
                })
            })
          }*/

          const token = app.$auth.getToken('local');
          if (token) {
            const now = Date.now().valueOf() / 1000;
            const jwt = require('jwt-decode')(token);
            if (token && now > jwt.exp) {
              app.$notify.error({message: 'Ваша сессия закончилась'});
              app.$auth.logout();
            }
          } else {
            app.$notify.error({message: error.response.data});
            ctx.$auth.redirect('login');
          }
          break;
        default:
          app.$notify.error({
            message: error.response.data
          });
      }
    } else {
      app.$notify.error({
        message: 'Неизвестная ошибка'
      });
    }

    return Promise.reject(error.response);
  })
}
