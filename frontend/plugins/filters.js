import Vue from 'vue'
import {noun, verb} from 'plural-ru'

export default ({app}, inject) => {

    inject('password', (length) => {
        if (!length) {
            length = 12;
        }
        let charset = 'abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let password = '';

        for (let i = 0, n = charset.length; i < length; ++i) {
            password += charset.charAt(Math.floor(Math.random() * n));
        }

        return password;
    });

    Vue.filter('price', (price, discount = 0) => {
        if (typeof discount == 'string') {
            const x = discount.length - 1;
            if (discount[x] == '%') {
                discount = discount.slice(0, x);
                discount = price * (discount / 100);
            }
        }
        if (discount > 0) {
            price -= discount
        }
        return price;
    });

    Vue.filter('number', (value) => {
        return value
            ? value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
            : 0;
    });

    Vue.filter('noun', (num, forms) => {
        let tmp = forms.split('|');

        return noun(num, tmp[0], tmp[1], tmp[2]);
    });

    Vue.filter('verb', (num, forms) => {
        let tmp = forms.split('|');

        return verb(num, tmp[0], tmp[1], tmp[2]);
    });

    Vue.filter('date', (value, format = 'DD.MM.YYYY') => {
        return app.$moment(value, 'YYYY-MM-DDTHH:mm:ssZ').format(format);
    });

    Vue.filter('datetime', (value, format = 'DD.MM.YYYY HH:mm') => {
        return app.$moment(value, 'YYYY-MM-DDTHH:mm:ssZ').format(format);
    });

    Vue.filter('dateago', (value) => {
        return app.$moment(value, 'YYYY-MM-DDTHH:mm:ssZ').fromNow();
    });

    Vue.filter('years', (value) => {
      return app.$moment().diff(app.$moment(value, 'YYYY-MM-DD'), 'years')
    });

    Vue.filter('phone', (value) => {
        return value.replace(/^(\d{3})(\d{3})(\d{4})$/g, '$1 $2 $3')
    });

    Vue.filter('duration', (value) => {
        let duration = app.$moment.duration(value, 'seconds');

        return app.$moment.utc(duration.asMilliseconds()).format('HH:mm:ss')
    });

}
