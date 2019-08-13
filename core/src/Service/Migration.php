<?php

namespace App\Service;

use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration
{
    /** @var \Illuminate\Database\Capsule\Manager $capsule */
    public $capsule;
    /** @var \Illuminate\Database\Schema\Builder $capsule */
    public $schema;


    public function init()
    {
        $container = new \App\Container();
        $this->capsule = $container->capsule;
        $this->schema = $container->capsule->schema();
    }
}