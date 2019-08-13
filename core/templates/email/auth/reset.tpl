{extends 'email/default.tpl'}

{block 'content'}
    <h1>Сброс пароля</h1>
    <p>
        Вы запросили сброс пароля на <a href="https://krafti.ru">Krafti.ru</a>,
        указав email <a href="mailto:{$email}">{$email}</a>.
    </p>
    <p>Теперь вам нужно <a href="{$link}">пройти по ссылке</a>, чтобы его подтвердить.</p>

    <small>Если вы не знаете, о чем идёт речь, просто удалите это письмо.</small>
{/block}
