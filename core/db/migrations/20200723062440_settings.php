<?php

use App\Model\Setting;
use App\Model\UserRole;
use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

class Settings extends Migration
{
    protected const DATA = [
        [
            'key' => 'contacts_text_bottom',
            'type' => 'text',
            'value' => 'Если у вас возникли проблемы с нашим сайтом, вы можете обратиться непосредственно к специалистам нашей службы технической поддержки.',
            'title' => 'Текст на странице контактов, снизу',
        ],
        [
            'key' => 'contacts_text_top',
            'type' => 'text',
            'value' => 'Хотите поделиться впечатлениями, задать вопросы или предложить тему для творчества?

Свяжитесь с нами любым удобным способом:',
            'title' => 'Текст на странице контактов, сверху',
        ],
        [
            'key' => 'email',
            'type' => 'email',
            'value' => 'info@krafti.ru',
            'title' => 'Основной email',
        ],
        [
            'key' => 'intro_text',
            'type' => 'text',
            'value' => 'Привет! Мы — KRAFTi, новое слово в онлайн-обучении. Мы верим, что в каждом ребёнке живёт настоящий творец, а в каждом взрослом — настоящий ребёнок. Именно поэтому творческое и интеллектуальное развитие взрослых и детей стало нашей страстью!

Мы не просто обучаем, мы раскрываем потенциал. Мы создаём невероятно увлекательные и насыщенные курсы по уникальной методике. Мы назвали её «Методика непрерывного обучения». Мы верим, что интересное обучение не только обогащает знаниями, но и способно изменить мировоззрение. Создать для детей преимущество в предстоящей большой жизни и вернуть взрослым ощущение творческого полёта.

Учитесь, творите, развивайтесь, вдохновляйте окружающих!',
            'title' => 'Текст на главной странице',
        ],
        [
            'key' => 'intro_video',
            'type' => 'number',
            'value' => '359213295',
            'title' => 'Id видео на главной странице',
        ],
        [
            'key' => 'link_instagram',
            'type' => 'url',
            'value' => 'https://www.instagram.com/krafti.ru/',
            'title' => 'Ссылка на Instagram',
        ],
        [
            'key' => 'link_whatsapp',
            'type' => 'url',
            'value' => 'https://api.whatsapp.com/send?phone=+79137206478',
            'title' => 'Ссылка на Whatsapp',
        ],
        [
            'key' => 'support_phone',
            'type' => 'phone',
            'value' => '+7 913-720-64-78',
            'title' => 'Телефон поддержки',
        ],
        [
            'key' => 'support_time',
            'type' => 'string',
            'value' => 'с 10:00 до 18:00',
            'title' => 'Время работы поддержки',
        ],
        [
            'key' => 'legal_info',
            'type' => 'text',
            'value' => 'ИП Матюшкин А. А.
ИНН 542512143999 / ОГРН 318547600033813

630047, г. Новосибирск, ул. Кузьмы Минина, 9/1, кв. 163
Контактное лицо: Матюшкин Антон',
            'title' => 'Информация о юр. лице',
        ],
        [
            'key' => 'copyright',
            'type' => 'text',
            'value' => 'Продажа творческих, спортивных онлайн-курсов для детей и взрослых. Все права защищены.',
            'title' => 'Копирайт',
        ],
    ];

    public function up(): void
    {
        $this->schema->create('settings', static function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('type')->default('string'); // phone, email, url, text, array
            $table->text('value')->nullable();
            $table->string('title')->nullable();
            $table->timestamps();
        });

        /** @var UserRole $role */
        if ($role = UserRole::query()->find(1)) {
            $scope = $role->scope;
            $scope[] = 'settings';
            $role->scope = array_unique($scope);
            $role->save();
        }

        foreach (self::DATA as $item) {
            (new Setting())->fill($item)->save();
        }
    }

    public function down(): void
    {
        $this->schema->drop('settings');
        /** @var UserRole $role */
        if ($role = UserRole::query()->find(1)) {
            $scope = $role->scope;
            $key = array_search('settings', $scope, true);
            if ($key !== -1) {
                unset($scope[$key]);
                $role->scope = $scope;
                $role->save();
            }
        }
    }
}
