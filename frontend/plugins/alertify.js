import AlertifyJs from 'alertifyjs'

export default ({app}, inject) => {

    inject('message', {
        success(message, delay) {
            if (message !== '') {
                AlertifyJs.notify(message, 'success', delay || 5);
            }
        },

        failure(message, delay) {
            if (message !== '') {
                AlertifyJs.notify(message, 'failure', delay || 5);
            }
        },

        error(message, delay) {
            this.failure(message, delay);
        },

        info(message, delay) {
            if (message !== '') {
                AlertifyJs.notify(message, 'info', delay || 5);
            }
        },

        confirm(message, ok, cancel, title) {
            AlertifyJs.confirm(
                title || 'Требуется подтверждение',
                message,
                ok,
                cancel
            ).setting({
                reverseButtons: true,
                labels: {ok: 'Да', cancel: 'Нет'},
                modal: true,
                closable: true,
                maximizable: false,
                movable: false,
                resizable: false,
                transition: false,
            })
        },

        prompt(message, ok, cancel, placeholder) {
            AlertifyJs.prompt().set({
                onshow() {
                    const input = document.getElementsByClassName('ajs-input')[0];
                    input.required = true;
                    input.placeholder = placeholder || 'Комментарий';
                }
            });
            AlertifyJs.prompt(
                '',
                message,
                '',
                ok,
                cancel
            ).setting({
                reverseButtons: true,
                labels: {ok: 'Сохранить', cancel: 'Отмена'},
                modal: true,
                closable: true,
                maximizable: false,
                movable: false,
                resizable: false,
                transition: false,
            });
        }
    });
}