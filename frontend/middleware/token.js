export default function ({app, route}) {
    if (!app.$auth.loggedIn) {
        app.$notify.error({message: 'Требуется авторизация'});
        app.$auth.redirect('login')
    } else {
        const token = app.$auth.getToken('local');
        const now = Date.now().valueOf() / 1000;
        const jwt = require('jwt-decode')(token);
        if (now > jwt.exp) {
            app.$notify.error({message: 'Ваша сессия закончилась'});
            app.$auth.logout();
        }
    }
}
