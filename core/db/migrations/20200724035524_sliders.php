<?php

use App\Model\Slider;
use App\Model\SliderItem;
use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Eloquent;
use Vesp\Services\Migration;

class Sliders extends Migration
{
    protected const DATA = [
        [
            'title' => 'Слайдер на главной странице',
        ],
    ];

    public function up(): void
    {
        $this->schema->create('sliders', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->tinyInteger('timeout')->default(5)->unsigned();
        });

        $this->schema->create('slider_items', static function (Blueprint $table) {
            $table->increments('id');
            $table->integer('slider_id')->unsigned();
            $table->integer('image_id')->unsigned();
            $table->integer('video_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->default('image')->index(); // image, video
            $table->string('link')->nullable();
            $table->integer('rank')->unsigned()->nullable()->index();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $eloquent = (new Eloquent())->getConnection();
            $table->index([$eloquent->raw('title, description(100)')], 'slider_items_title_description_index');
            $table->index(['slider_id', 'active']);

            $table->foreign('slider_id')
                ->references('id')->on('sliders')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('image_id')
                ->references('id')->on('files')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('video_id')
                ->references('id')->on('videos')
                ->onUpdate('restrict')
                ->onDelete('cascade');
        });

        foreach (self::DATA as $item) {
            (new Slider())->fill($item)->save();
        }
    }

    public function down(): void
    {
        /** @var SliderItem $item */
        foreach (SliderItem::query()->whereNotNull('image_id')->get() as $item) {
            $item->delete();
        }
        $this->schema->drop('slider_items');
        $this->schema->drop('sliders');
    }
}
