{extends 'email/default.tpl'}

{block 'content'}
    <h1>Ссылка для входа</h1>
    <p>
        Вы успешно зарегистрировались на <a href="https://krafti.ru">Krafti.ru</a>,
        указав email <a href="mailto:{$email}">{$email}</a>.
    </p>
    <p>Теперь вам нужно <a href="{$link}">пройти по ссылке</a>, чтобы его проверить.</p>

    <small>Если вы не знаете, о чем идёт речь, просто удалите это письмо.</small>
{/block}
