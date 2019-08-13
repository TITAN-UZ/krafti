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
                    ctx.app.$notify.error({
                        message: error.response.data
                    });
                    if (ctx.$auth.loggedIn) {
                        ctx.$auth.logout();
                    } else {
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
