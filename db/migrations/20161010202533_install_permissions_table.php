<?php

use Phinx\Migration\AbstractMigration;


class InstallPermissionsTable extends AbstractMigration
{
    public function up()
    {
        $this->table('permissions')
            ->addColumn('key', 'string', ['length' => 50, 'null' => false])
            ->addColumn('label', 'string', ['length' => 100, 'null' => false])
            ->addIndex('key', ['unique' => true, 'name' => 'k_permissions_key'])
            ->create();
    }

    public function down()
    {
        $this->dropTable('permissions');
    }
}
