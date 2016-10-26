<?php

use Phinx\Migration\AbstractMigration;

class InstallSocialPlatformsTable extends AbstractMigration
{
    public function up()
    {
        $this->table('social_platforms')
            ->addColumn('key', 'string', ['length' => 50, 'null' => false])
            ->addColumn('label', 'string', ['length' => 100, 'null' => false])
            ->addIndex('key', ['unique' => true, 'name' => 'k_social_platforms_key'])
            ->create();
    }

    public function down()
    {
        $this->dropTable('social_platforms');
    }
}
