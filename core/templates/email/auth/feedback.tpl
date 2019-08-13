{extends 'email/default.tpl'}

{block 'content'}
    <h1>Вам пришло новое сообщение с сайта:</h1>
    <pre>{$message | strip_tags}</pre>
    <br>
    <table style="width:300px;">
        <tr>
            <td>Отправитель:</td>
            <td>{$name}</td>
        </tr>
        <tr>
            <td>Email:</td>
            <td>{$email}</td>
        </tr>
        <tr>
            <td>Телефон:</td>
            <td>{$phone}</td>
        </tr>
    </table>
{/block}
